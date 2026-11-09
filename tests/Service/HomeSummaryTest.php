<?php

namespace App\Tests\Service;

use App\Entity\{Macro, Meso, Micro, Movimento};
use App\Service\HomeSummary;
use PHPUnit\Framework\TestCase;

class HomeSummaryTest extends TestCase
{
    private function movement(string $date, string $type, ?float $amount): Movimento
    {
        $macro = (new Macro())->setNome($type);
        $meso = (new Meso())->setPadre($macro);
        $micro = (new Micro())->setPadre($meso);
        return (new Movimento())->setData(new \DateTime($date))->setCategoria($micro)->setImporto($amount);
    }

    public function testTotalsChartOrderAndCalendarBoundaries(): void
    {
        $summary = (new HomeSummary())->calculate([
            $this->movement('2026-02-01', 'Uscita', 4.29),
            $this->movement('2026-01-31', 'Entrata', 0.1),
            $this->movement('2025-12-31', 'Entrata', 100),
            $this->movement('2026-01-01', 'Entrata', 0.2),
            $this->movement('2026-01-31', 'Uscita', 0.29),
            $this->movement('2026-12-31', 'Uscita', 10),
        ], new \DateTimeImmutable('2026-01-31 23:30:00 UTC'));

        // In Rome it is February; annual totals cover the whole calendar year.
        self::assertSame(0.0, $summary['entrate_mensili']);
        self::assertSame(4.29, $summary['uscite_mensili']);
        self::assertSame(0.3, $summary['entrate_annuali']);
        self::assertSame(14.58, $summary['uscite_annuali']);
        self::assertSame(['2025-12', '2026-01', '2026-02', '2026-12'], $summary['periodi']);
        self::assertSame([100.0, 0.3, 0.0, 0.0], $summary['entrate_periodi']);
        self::assertSame([0.0, 0.29, 4.29, 10.0], $summary['uscite_periodi']);
    }

    public function testEmptyDatabaseAndMissingAmount(): void
    {
        $service = new HomeSummary();
        $date = new \DateTimeImmutable('2026-01-01');
        $empty = $service->calculate([], $date);
        self::assertSame([], $empty['periodi']);
        self::assertSame([], $empty['entrate_periodi']);
        self::assertSame([], $empty['uscite_periodi']);
        foreach (['entrate_mensili', 'uscite_mensili', 'entrate_annuali', 'uscite_annuali'] as $key) {
            self::assertSame(0.0, $empty[$key]);
        }
        $summary = $service->calculate([$this->movement('2026-01-01', 'Entrata', null)], $date);
        self::assertSame([0.0], $summary['entrate_periodi']);
    }

    public function testRomeNewYearExcludesPreviousYear(): void
    {
        $summary = (new HomeSummary())->calculate([
            $this->movement('2025-12-31', 'Entrata', 999),
            $this->movement('2026-01-01', 'Entrata', 12.34),
        ], new \DateTimeImmutable('2025-12-31 23:30:00 UTC'));
        self::assertSame(12.34, $summary['entrate_mensili']);
        self::assertSame(12.34, $summary['entrate_annuali']);
    }
}
