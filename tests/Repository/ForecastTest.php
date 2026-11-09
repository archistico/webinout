<?php

namespace App\Tests\Repository;

use App\Controller\PrevisioniController;
use App\Repository\MovimentoRepository;
use App\Service\ForecastPeriod;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class ForecastTest extends TestCase
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
        $connection->executeStatement('CREATE TABLE movimento (id INTEGER PRIMARY KEY, data TEXT, importo REAL, categoria_id INTEGER)');
        foreach ([1 => 'Entrata', 2 => 'Uscita', 3 => 'Entrata'] as $id => $name) {
            $connection->insert('macro', ['id' => $id, 'nome' => $name]);
            $connection->insert('meso', ['id' => $id, 'nome' => 'Stesso nome', 'padre_id' => $id]);
            $connection->insert('micro', ['id' => $id, 'nome' => 'Stesso nome', 'padre_id' => $id]);
        }
    }

    protected function tearDown(): void
    {
        $this->manager->close();
        $this->manager->getConnection()->close();
    }

    private function movement(string $date, ?float $amount, int $category): void
    {
        $this->manager->getConnection()->insert('movimento', ['data' => $date, 'importo' => $amount, 'categoria_id' => $category]);
    }

    public function testBoundsGroupingAndPreciseDivision(): void
    {
        $this->movement('2023-12-31 23:59:59', 900, 1);
        $this->movement('2024-01-01 00:00:00', 100.25, 1);
        $this->movement('2024-02-29 23:59:59', 1, 1);
        $this->movement('2024-02-29 23:59:59', 40, 2);
        $this->movement('2024-02-01 00:00:00', 20, 3);
        $this->movement('2024-03-01 00:00:00', 900, 1);
        $this->movement('2027-01-01 00:00:00', 900, 1);
        $start = new \DateTimeImmutable('2024-01-01');
        $end = new \DateTimeImmutable('2024-03-01');
        foreach (['listaPrevisione', 'listaPrevisioneMeso'] as $method) {
            $rows = $this->repository->$method($start, 2, $end);
            self::assertCount(3, $rows, 'Categories with identical names retain separate identities');
            self::assertSame([50.625, 20.0, 10.0], array_map(fn ($row) => (float) $row['Totale'], $rows));
            self::assertSame([], $this->repository->$method($start, 0, $end));
        }
        $income = $this->repository->listaPrevisioneCategoria($start, 2, 'Entrata', $end);
        self::assertCount(1, $income, 'All income categories contribute to the summary');
        self::assertSame(60.625, (float) $income[0]['Totale']);
        self::assertSame([], $this->repository->listaPrevisioneCategoria($start, 0, 'Entrata', $end));
    }

    /** @dataProvider oneSided */
    public function testControllerKeepsIndependentTotals(?int $category, float $income, float $expense): void
    {
        if ($category !== null) {
            $this->movement('2024-01-10 00:00:00', 100.25, $category);
        }
        $period = $this->createMock(ForecastPeriod::class);
        $period->method('calculate')->willReturn((new ForecastPeriod())->calculate(new \DateTimeImmutable('2024-03-01')));
        $controller = new class extends PrevisioniController {
            public array $data;
            protected function render(string $view, array $parameters = [], ?Response $response = null): Response
            {
                $this->data = $parameters;
                return new Response();
            }
        };
        $controller->index($this->repository, $period);
        self::assertSame($income, $controller->data['entrate']['Totale']);
        self::assertSame($expense, $controller->data['uscite']['Totale']);
    }

    public function oneSided(): array
    {
        return [[1, 50.125, 0.0], [2, 0.0, 50.125], [null, 0.0, 0.0]];
    }

    public function testNullAmountsBecomeZeroAndEmptyPeriodDoesNotQuery(): void
    {
        $this->movement('2024-01-10 00:00:00', null, 1);
        $rows = $this->repository->listaPrevisione(new \DateTimeImmutable('2024-01-01'), 2, new \DateTimeImmutable('2024-03-01'));
        self::assertSame(0.0, (float) $rows[0]['Totale']);

        $repository = $this->createMock(MovimentoRepository::class);
        foreach (['listaPrevisione', 'listaPrevisioneMeso', 'listaPrevisioneCategoria'] as $method) {
            $repository->expects(self::never())->method($method);
        }
        $period = $this->createMock(ForecastPeriod::class);
        $period->method('calculate')->willReturn((new ForecastPeriod())->calculate(new \DateTimeImmutable('2024-01-31')));
        $controller = new class extends PrevisioniController {
            public array $data;
            protected function render(string $view, array $parameters = [], ?Response $response = null): Response
            {
                $this->data = $parameters;
                return new Response();
            }
        };
        $controller->index($repository, $period);
        self::assertSame([], $controller->data['lista']);
        self::assertSame([], $controller->data['listaMeso']);
        self::assertSame(0.0, $controller->data['entrate']['Totale']);
        self::assertSame(0.0, $controller->data['uscite']['Totale']);
    }
}
