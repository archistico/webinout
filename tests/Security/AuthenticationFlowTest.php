<?php

namespace App\Tests\Security;

use App\Entity\User;
use App\Kernel;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AuthenticationFlowTest extends WebTestCase
{
    private KernelBrowser $browser;
    private string $databasePath;
    private array $databaseEnvironment;
    private string $email;
    private const PASSWORD = 'OnlyForLocalAuthTests-42!';

    protected static function getKernelClass(): string
    {
        return Kernel::class;
    }

    protected function setUp(): void
    {
        $this->databaseEnvironment = [$_ENV['DATABASE_URL'] ?? null, $_SERVER['DATABASE_URL'] ?? null, getenv('DATABASE_URL')];
        $this->databasePath = dirname(__DIR__, 2).'/var/auth-test-'.bin2hex(random_bytes(8)).'.sqlite';
        $url = 'sqlite:///'.str_replace('\\', '/', $this->databasePath);
        $_ENV['DATABASE_URL'] = $_SERVER['DATABASE_URL'] = $url;
        putenv('DATABASE_URL='.$url);
        $this->email = 'auth-'.bin2hex(random_bytes(6)).'@example.test';
        $this->browser = self::createClient([], ['REMOTE_ADDR' => '192.0.2.'.random_int(1, 254)]);
        $manager = self::getContainer()->get('doctrine')->getManager();
        self::assertSame(str_replace('\\', '/', $this->databasePath), str_replace('\\', '/', $manager->getConnection()->getParams()['path']));
        (new SchemaTool($manager))->createSchema($manager->getMetadataFactory()->getAllMetadata());
        $user = (new User())->setEmail($this->email)->setNome('Test')->setCognome('Login')->setRoles(['ROLE_USER']);
        $user->setPassword(self::getContainer()->get('security.user_password_hasher')->hashPassword($user, self::PASSWORD));
        $manager->persist($user);
        $manager->flush();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        foreach (['_ENV', '_SERVER'] as $index => $name) {
            if ($this->databaseEnvironment[$index] === null) {
                unset($GLOBALS[$name]['DATABASE_URL']);
            } else {
                $GLOBALS[$name]['DATABASE_URL'] = $this->databaseEnvironment[$index];
            }
        }
        putenv($this->databaseEnvironment[2] === false ? 'DATABASE_URL' : 'DATABASE_URL='.$this->databaseEnvironment[2]);
        if (isset($this->databasePath) && is_file($this->databasePath)) {
            unlink($this->databasePath);
        }
    }

    private function token(): string
    {
        $crawler = $this->browser->request('GET', '/login');
        self::assertResponseIsSuccessful();
        return $crawler->filter('input[name="_csrf_token"]')->attr('value');
    }

    private function submit(string $token, ?string $password = null): void
    {
        $this->browser->request('POST', '/login', ['_username' => $this->email, '_password' => $password ?? self::PASSWORD, '_csrf_token' => $token]);
    }

    public function testLoginPageIsNeverCachedAndSessionCookieIsHttpOnly(): void
    {
        self::assertNotSame('', $this->token());
        self::assertTrue($this->browser->getResponse()->headers->hasCacheControlDirective('no-store'));
        self::assertFalse($this->browser->getResponse()->headers->hasCacheControlDirective('public'));
        $cookies = $this->browser->getCookieJar()->all();
        self::assertNotEmpty($cookies);
        self::assertTrue($cookies[0]->isHttpOnly());
    }

    public function testSuccessfulLoginRestoresRequestedPageAndLogoutRevokesAccess(): void
    {
        $this->browser->request('GET', '/admin/analisi');
        self::assertResponseRedirects('http://localhost/login');
        $crawler = $this->browser->followRedirect();
        $form = $crawler->selectButton('Entra')->form(['_username' => $this->email, '_password' => self::PASSWORD]);
        $oldSession = $this->browser->getCookieJar()->all()[0]->getValue();
        $this->browser->submit($form);
        self::assertResponseRedirects('http://localhost/admin/analisi');
        self::assertNotSame($oldSession, $this->browser->getCookieJar()->all()[0]->getValue(), 'Session ID changes after authentication');
        $crawler = $this->browser->followRedirect();
        self::assertResponseIsSuccessful();
        $logout = $crawler->filter('form[action="/logout"]');
        self::assertCount(1, $logout);
        $this->browser->submit($logout->form());
        self::assertResponseRedirects('/login');
        $this->browser->followRedirect();
        self::assertSelectorExists('input[name="_password"]');
        $this->browser->request('GET', '/admin/analisi');
        self::assertResponseRedirects('http://localhost/login');
    }

    public function testBothLoginTabsRemainValidWithinTheSameSession(): void
    {
        $first = $this->token();
        $this->token();
        $this->submit($first);
        self::assertResponseRedirects('/admin');
    }

    public function testExpiredSessionRejectsOldTokenThenFreshFormWorks(): void
    {
        $oldToken = $this->token();
        $this->browser->getCookieJar()->clear();
        $this->submit($oldToken);
        self::assertResponseRedirects('/login');
        $this->browser->followRedirect();
        self::assertSelectorTextContains('[role="alert"]', 'sessione');
        $freshToken = $this->browser->getCrawler()->filter('input[name="_csrf_token"]')->attr('value');
        $this->submit($freshToken);
        self::assertResponseRedirects('/admin');
    }

    public function testInvalidPasswordCanBeCorrectedUsingTheNewForm(): void
    {
        $this->submit($this->token(), 'wrong-password');
        self::assertResponseRedirects('/login');
        $this->browser->followRedirect();
        self::assertSelectorExists('[role="alert"]');
        self::assertInputValueSame('_username', $this->email);
        self::assertInputValueSame('_password', '');
        $this->submit($this->browser->getCrawler()->filter('input[name="_csrf_token"]')->attr('value'));
        self::assertResponseRedirects('/admin');
    }

    public function testMissingAndForgedCsrfTokensAreRejected(): void
    {
        $this->token();
        foreach (['', 'forged-token'] as $token) {
            $this->submit($token);
            self::assertResponseRedirects('/login');
            $this->browser->followRedirect();
            self::assertSelectorTextContains('[role="alert"]', 'sessione');
            $this->browser->request('GET', '/admin/analisi');
            self::assertResponseRedirects('http://localhost/login');
        }
    }

    public function testHttpsLoginKeepsTheCsrfNamespaceConsistent(): void
    {
        $crawler = $this->browser->request('GET', 'https://localhost/login');
        self::assertResponseIsSuccessful();
        $cookie = $this->browser->getCookieJar()->all()[0];
        // Mock session storage does not emulate the native cookie_secure=auto option.
        self::assertStringStartsWith('https://', $this->browser->getRequest()->getUri());
        self::assertTrue($cookie->isHttpOnly());
        self::assertSame('lax', $cookie->getSameSite());
        $this->browser->submit($crawler->selectButton('Entra')->form(['_username' => $this->email, '_password' => self::PASSWORD]));
        self::assertResponseRedirects('/admin');
        $this->browser->request('GET', 'https://localhost/login');
        self::assertResponseRedirects('/admin');
        self::assertTrue($this->browser->getResponse()->headers->hasCacheControlDirective('no-store'));
    }

    public function testForgedLogoutAndGetLogoutCannotEndTheSession(): void
    {
        $this->submit($this->token());
        $crawler = $this->browser->followRedirect();
        $logout = $crawler->filter('form[action="/logout"]')->form();
        self::assertTrue($this->browser->getResponse()->headers->hasCacheControlDirective('no-store'));
        $this->browser->request('GET', '/logout');
        self::assertResponseStatusCodeSame(405);
        $this->browser->request('POST', '/logout', ['_csrf_token' => 'forged-token']);
        self::assertResponseStatusCodeSame(403);
        $this->browser->request('GET', '/admin/analisi');
        self::assertResponseIsSuccessful();
        $this->browser->submit($logout);
        self::assertResponseRedirects('/login');
    }

    public function testLogoutAfterSessionExpiryReturnsToLogin(): void
    {
        $this->submit($this->token());
        $crawler = $this->browser->followRedirect();
        $logout = $crawler->filter('form[action="/logout"]')->form();
        $this->browser->getCookieJar()->clear();
        $this->browser->submit($logout);
        self::assertResponseRedirects('/login');
        $this->browser->followRedirect();
        self::assertSelectorNotExists('[role="alert"]');
        self::assertSelectorExists('input[name="_password"]');
    }

    public function testOldLoginTabAfterLogoutCanRecoverWithFreshForm(): void
    {
        $oldToken = $this->token();
        $this->submit($oldToken);
        $crawler = $this->browser->followRedirect();
        $this->browser->submit($crawler->filter('form[action="/logout"]')->form());
        self::assertResponseRedirects('/login');
        $this->submit($oldToken);
        self::assertResponseRedirects('/login');
        $this->browser->followRedirect();
        self::assertSelectorTextContains('[role="alert"]', 'sessione');
        $this->submit($this->browser->getCrawler()->filter('input[name="_csrf_token"]')->attr('value'));
        self::assertResponseRedirects('/admin');
    }

    public function testRepeatedWrongPasswordsAreThrottled(): void
    {
        for ($attempt = 0; $attempt < 4; ++$attempt) {
            $this->submit($this->token(), 'wrong-password');
            self::assertResponseRedirects('/login');
            $this->browser->followRedirect();
        }
        self::assertSelectorTextContains('[role="alert"]', 'tentativi');
        $this->submit($this->browser->getCrawler()->filter('input[name="_csrf_token"]')->attr('value'));
        self::assertResponseRedirects('/login', 302, 'Correct credentials cannot bypass an active rate limit');
    }
}
