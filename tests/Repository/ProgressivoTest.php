<?php

namespace App\Tests\Repository;

use App\Controller\ProgressivoController;
use App\Repository\MovimentoRepository;
use App\Service\ProgressivoSummary;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class ProgressivoTest extends TestCase
{
    public function testPeriodQueriesAndControllerIncludeFullDayAndDistinctCategories(): void
    {
        $dbalConfig = new \Doctrine\DBAL\Configuration();
        $dbalConfig->setSchemaManagerFactory(new \Doctrine\DBAL\Schema\DefaultSchemaManagerFactory());
        $connection = DriverManager::getConnection(['driver' => 'pdo_sqlite', 'memory' => true], $dbalConfig);
        $manager = new EntityManager($connection, ORMSetup::createAttributeMetadataConfiguration([dirname(__DIR__, 2).'/src/Entity'], true));
        try {
            $registry = $this->createMock(ManagerRegistry::class);
            $registry->method('getManagerForClass')->willReturn($manager);
            $repository = new MovimentoRepository($registry);
            $connection->executeStatement('CREATE TABLE macro (id INTEGER PRIMARY KEY, nome TEXT)');
            $connection->executeStatement('CREATE TABLE meso (id INTEGER PRIMARY KEY, nome TEXT, padre_id INTEGER)');
            $connection->executeStatement('CREATE TABLE micro (id INTEGER PRIMARY KEY, nome TEXT, padre_id INTEGER)');
            $connection->executeStatement('CREATE TABLE movimento (id INTEGER PRIMARY KEY, data TEXT, importo REAL, categoria_id INTEGER)');
            $connection->insert('macro', ['id' => 1, 'nome' => 'Entrata']);
            $connection->insert('macro', ['id' => 2, 'nome' => 'Uscita']);
            foreach ([1 => 1, 2 => 2, 3 => 2] as $id => $macro) {
                $connection->insert('meso', ['id' => $id, 'nome' => 'Omonima', 'padre_id' => $macro]);
                $connection->insert('micro', ['id' => $id, 'nome' => 'Omonima', 'padre_id' => $id]);
            }
            foreach ([
                ['2024-12-31 23:59:59', 999, 1],
                ['2025-01-01 00:00:00', 0.1, 1],
                ['2025-11-09 23:59:59', 0.2, 1],
                ['2025-11-09 23:59:59', 10.29, 2],
                ['2025-11-10 00:00:00', 999, 1],
                ['2025-12-31 23:59:59', 999, 1],
                ['2026-01-01 00:00:00', 100, 1],
                ['2026-11-09 23:59:59', 20, 3],
                ['2026-11-10 00:00:00', 999, 1],
            ] as [$date, $amount, $category]) {
                $connection->insert('movimento', ['data' => $date, 'importo' => $amount, 'categoria_id' => $category]);
            }
            $summary = $this->getMockBuilder(ProgressivoSummary::class)->onlyMethods(['periods'])->getMock();
            $periods = (new ProgressivoSummary())->periods(new \DateTimeImmutable('2026-11-09 01:00:00 Europe/Rome'));
            $summary->method('periods')->willReturn($periods);
            $controller = new class extends ProgressivoController {
                public array $data;
                protected function render(string $view, array $parameters = [], ?Response $response = null): Response
                {
                    $this->data = $parameters;
                    return new Response();
                }
            };
            $controller->progressivo($repository, $summary);
            $data = $controller->data;
            self::assertCount(1, $data['entrate']);
            self::assertEquals(0.3, $data['entrate'][0]['TotaleAnnoScorso']);
            self::assertEquals(100, $data['entrate'][0]['TotaleAnnoInCorso']);
            self::assertEquals(99.7, $data['entrate'][0]['Differenza']);
            self::assertCount(2, $data['uscite']);
            self::assertEquals([-10.29, 20], array_column($data['uscite'], 'Differenza'));
            self::assertSame([], $data['altre']);
            $empty = $repository->listaSommaPerCategoriePeriodo(new \DateTimeImmutable('2030-01-01'), new \DateTimeImmutable('2031-01-01'));
            self::assertSame([], $empty);
        } finally {
            $manager->close();
            $connection->close();
        }
    }
}
