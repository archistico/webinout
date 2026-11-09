<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\LogoutException;

class AuthenticationSessionSubscriber implements EventSubscriberInterface
{
    public function __construct(private TokenStorageInterface $tokens, private UrlGeneratorInterface $urls)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::RESPONSE => 'preventSessionPageCaching', KernelEvents::EXCEPTION => ['handleExpiredLogout', 2]];
    }

    public function preventSessionPageCaching(ResponseEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }
        $path = $event->getRequest()->getPathInfo();
        if (!in_array($path, ['/login', '/logout', '/admin'], true) && !str_starts_with($path, '/admin/')) {
            return;
        }
        $response = $event->getResponse();
        $response->setPrivate();
        $response->headers->addCacheControlDirective('no-store');
        $response->headers->addCacheControlDirective('no-cache');
        $response->headers->addCacheControlDirective('max-age', 0);
        $response->headers->set('Pragma', 'no-cache');
        $response->setExpires(new \DateTimeImmutable('@0'));
    }

    public function handleExpiredLogout(ExceptionEvent $event): void
    {
        // An expired session is already logged out. Do not show a token error for it.
        // Authenticated requests with invalid tokens remain rejected by the firewall.
        if ($event->isMainRequest()
            && $event->getRequest()->attributes->get('_route') === 'app_logout'
            && $event->getThrowable() instanceof LogoutException
            && $this->tokens->getToken() === null) {
            $event->setResponse(new RedirectResponse($this->urls->generate('app_login')));
        }
    }
}
