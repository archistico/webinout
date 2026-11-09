<?php

namespace App\Tests\Controller;

use App\Controller\PrevisioniController;
use App\Controller\ProgressivoController;
use App\Kernel;
use App\Repository\MovimentoRepository;
use App\Service\ForecastPeriod;
use App\Service\ProgressivoSummary;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;

class ReportingRenderTest extends KernelTestCase
{
    protected static function getKernelClass(): string
    {
        return Kernel::class;
    }

    private function request(string $route): void
    {
        self::bootKernel();
        $request = Request::create('http://localhost/admin');
        $request->attributes->set('_route', $route);
        $request->setSession(self::getContainer()->get('session.factory')->createSession());
        self::getContainer()->get('request_stack')->push($request);
    }

    public function testProgressivoDatesAndDifferencesAreRenderedInRome(): void
    {
        $this->request('app_progressivo');
        $periods = (new ProgressivoSummary())->periods(new \DateTimeImmutable('2024-02-29 12:00:00 Europe/Rome'));
        $service = $this->getMockBuilder(ProgressivoSummary::class)->onlyMethods(['periods'])->getMock();
        $service->method('periods')->willReturn($periods);
        $repository = $this->createMock(MovimentoRepository::class);
        $row = ['CategoriaId' => 1, 'MacroNome' => 'Uscita', 'MesoNome' => 'Casa', 'MicroNome' => 'Utenze'];
        $calls = 0;
        $repository->expects(self::exactly(2))->method('listaSommaPerCategoriePeriodo')->willReturnCallback(
            function (\DateTimeInterface $start, \DateTimeInterface $end) use (&$calls, $row): array {
                $calls++;
                self::assertSame($calls === 1 ? '2023-01-01' : '2024-01-01', $start->format('Y-m-d'));
                self::assertSame($calls === 1 ? '2023-03-01' : '2024-03-01', $end->format('Y-m-d'));
                return [array_merge($row, ['Totale' => $calls === 1 ? 10.29 : 10.58])];
            }
        );
        $html = self::getContainer()->get(ProgressivoController::class)->progressivo($repository, $service)->getContent();
        $crawler = new Crawler($html);
        foreach ($crawler->filter('table') as $table) {
            $headers = (new Crawler($table))->filter('th');
            self::assertSame('01/01/2023 - 28/02/2023', $headers->eq(1)->text());
            self::assertSame('01/01/2024 - 29/02/2024', $headers->eq(2)->text());
        }
        $cells = $crawler->filter('table')->eq(1)->filter('tbody tr td');
        $formatter = new \NumberFormatter('it', \NumberFormatter::CURRENCY);
        self::assertSame('+'.$formatter->formatCurrency(0.29, 'EUR'), $cells->eq(3)->text());
        self::assertStringContainsString('text-danger', $cells->eq(3)->attr('class'));
        self::assertSame(1, substr_count($html, 'Nessun movimento nei periodi confrontati'));
    }

    public function testEmptyProgressivoRendersBothSections(): void
    {
        $this->request('app_progressivo');
        $repository = $this->createMock(MovimentoRepository::class);
        $repository->expects(self::exactly(2))->method('listaSommaPerCategoriePeriodo')->willReturn([]);
        $html = self::getContainer()->get(ProgressivoController::class)->progressivo($repository, new ProgressivoSummary())->getContent();
        self::assertSame(2, substr_count($html, 'Nessun movimento nei periodi confrontati'));
    }

    public function testForecastDatesAreRenderedInRome(): void
    {
        $this->request('app_previsioni');
        $repository = $this->createMock(MovimentoRepository::class);
        foreach (['listaPrevisione', 'listaPrevisioneMeso', 'listaPrevisioneCategoria'] as $method) {
            $repository->method($method)->willReturn([]);
        }
        $service = $this->createMock(ForecastPeriod::class);
        $service->method('calculate')->willReturn((new ForecastPeriod())->calculate(new \DateTimeImmutable('2026-11-09 Europe/Rome')));
        $html = self::getContainer()->get(PrevisioniController::class)->index($repository, $service)->getContent();
        self::assertStringContainsString('dal 01/01/2024 al 31/10/2026.', $html);
        self::assertStringNotContainsString('31/12/2023', $html);
        self::assertSame(2, substr_count($html, 'Nessun dato disponibile'));
    }
}
