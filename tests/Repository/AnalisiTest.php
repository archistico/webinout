<?php

namespace App\Tests\Repository;

use App\Controller\AnalisiController;
use App\Repository\MovimentoRepository;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\UX\Chartjs\Builder\ChartBuilder;

class AnalisiTest extends TestCase
{
    private EntityManager $manager;
    private MovimentoRepository $repository;

    protected function setUp(): void
    {
        $dbalConfig = new \Doctrine\DBAL\Configuration();
        $dbalConfig->setSchemaManagerFactory(new \Doctrine\DBAL\Schema\DefaultSchemaManagerFactory());
        $connection = DriverManager::getConnection(['driver' => 'pdo_sqlite', 'memory' => true], $dbalConfig);
        $this->manager = new EntityManager($connection, ORMSetup::createAttributeMetadataConfiguration([dirname(__DIR__, 2).'/src/Entity'], true));
        $registry = $this->createMock(ManagerRegistry::class);
        $registry->method('getManagerForClass')->willReturn($this->manager);
        $this->repository = new MovimentoRepository($registry);
        $connection->executeStatement('CREATE TABLE macro (id INTEGER PRIMARY KEY, nome TEXT)');
        $connection->executeStatement('CREATE TABLE meso (id INTEGER PRIMARY KEY, nome TEXT, padre_id INTEGER)');
        $connection->executeStatement('CREATE TABLE micro (id INTEGER PRIMARY KEY, nome TEXT, padre_id INTEGER)');
        $connection->executeStatement('CREATE TABLE movimento (id INTEGER PRIMARY KEY, importo REAL, categoria_id INTEGER)');
        $connection->insert('macro', ['id' => 1, 'nome' => 'Entrata']);
        $connection->insert('macro', ['id' => 2, 'nome' => 'Uscita']);
        foreach ([1 => 1, 2 => 2, 3 => 2] as $id => $macro) {
            $connection->insert('meso', ['id' => $id, 'nome' => 'Omonima', 'padre_id' => $macro]);
            $connection->insert('micro', ['id' => $id, 'nome' => 'Omonima', 'padre_id' => $id]);
        }
    }

    protected function tearDown(): void
    {
        $this->manager->close();
        $this->manager->getConnection()->close();
    }

    private function data(): array
    {
        $controller = new class extends AnalisiController {
            public array $data;
            protected function render(string $view, array $parameters = [], ?Response $response = null): Response
            {
                $this->data = $parameters;
                return new Response();
            }
        };
        $controller->analisi($this->repository, new ChartBuilder());
        return $controller->data;
    }

    public function testChartsAgreeWithTablesAndPreserveNamesakes(): void
    {
        $connection = $this->manager->getConnection();
        foreach ([[1, 0.1], [1, 0.2], [2, 10.29], [3, 20.01]] as [$category, $amount]) {
            $connection->insert('movimento', ['importo' => $amount, 'categoria_id' => $category]);
        }
        $data = $this->data();
        self::assertCount(2, $data['listaMacro']);
        self::assertCount(3, $data['listaMeso']);
        self::assertCount(3, $data['listaMicro']);
        foreach (['listaMacro', 'listaMeso', 'listaMicro'] as $level) {
            foreach ($data[$level] as $row) {
                self::assertArrayNotHasKey(0, $row, 'Aggregates contain only scalars, not a random movement');
            }
            self::assertEqualsWithDelta(30.6, array_sum(array_column($data[$level], 'Totale')), 0.000001);
        }
        self::assertSame([0.3, 30.3], $data['chart_analisi_1']->getData()['datasets'][0]['data']);
        $chart = $data['chart_analisi_2']->getData();
        self::assertSame(['Omonima', 'Omonima'], $chart['labels']);
        $values = $chart['datasets'][0]['data'];
        sort($values);
        self::assertSame([10.29, 20.01], $values);
        self::assertTrue($data['haUscite']);
    }

    /** @dataProvider sparseData */
    public function testSparseData(?int $category, ?float $amount, array $expected, bool $hasExpenses): void
    {
        if ($category !== null) {
            $this->manager->getConnection()->insert('movimento', ['importo' => $amount, 'categoria_id' => $category]);
        }
        $data = $this->data();
        self::assertEquals($expected, $data['chart_analisi_1']->getData()['datasets'][0]['data']);
        self::assertSame($hasExpenses, $data['haUscite']);
        if (!$hasExpenses) {
            self::assertSame([], $data['chart_analisi_2']->getData()['labels']);
            self::assertSame([], $data['chart_analisi_2']->getData()['datasets'][0]['data']);
        }
    }

    public function sparseData(): array
    {
        return [[null, null, [0.0, 0.0], false], [1, 12.34, [12.34, 0.0], false], [2, 12.34, [0.0, 12.34], true], [2, null, [0.0, 0.0], true]];
    }
}
