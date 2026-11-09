<?php

namespace App\Tests\Service;

use App\Service\ProgressivoSummary;
use PHPUnit\Framework\TestCase;

class ProgressivoSummaryTest extends TestCase
{
    /** @dataProvider dates */
    public function testCalendarPeriods(string $now, string $current, string $previous, string $currentLimit, string $previousLimit): void
    {
        $date = new \DateTimeImmutable($now);
        $periods = (new ProgressivoSummary())->periods($date);
        self::assertSame($current, $periods['fineAnnoInCorso']->format('Y-m-d'));
        self::assertSame($previous, $periods['fineAnnoPassato']->format('Y-m-d'));
        self::assertSame($currentLimit, $periods['limiteAnnoInCorso']->format('Y-m-d'));
        self::assertSame($previousLimit, $periods['limiteAnnoPassato']->format('Y-m-d'));
        self::assertSame(substr($current, 0, 4).'-01-01', $periods['inizioAnnoInCorso']->format('Y-m-d'));
        self::assertSame(substr($previous, 0, 4).'-01-01', $periods['inizioAnnoPassato']->format('Y-m-d'));
        self::assertSame('00:00:00', $periods['limiteAnnoInCorso']->format('H:i:s'));
        self::assertSame('Europe/Rome', $periods['limiteAnnoInCorso']->getTimezone()->getName());
        self::assertSame((new \DateTimeImmutable($now))->format('c'), $date->format('c'));
    }

    public function dates(): array
    {
        return [
            ['2026-11-09 08:00:00 Europe/Rome', '2026-11-09', '2025-11-09', '2026-11-10', '2025-11-10'],
            ['2024-02-29 12:00:00 Europe/Rome', '2024-02-29', '2023-02-28', '2024-03-01', '2023-03-01'],
            ['2025-02-28 12:00:00 Europe/Rome', '2025-02-28', '2024-02-28', '2025-03-01', '2024-02-29'],
            ['2025-03-01 12:00:00 Europe/Rome', '2025-03-01', '2024-03-01', '2025-03-02', '2024-03-02'],
            ['2026-03-29 01:00:00 UTC', '2026-03-29', '2025-03-29', '2026-03-30', '2025-03-30'],
            ['2026-10-25 02:00:00 UTC', '2026-10-25', '2025-10-25', '2026-10-26', '2025-10-26'],
            ['2026-12-31 23:30:00 UTC', '2027-01-01', '2026-01-01', '2027-01-02', '2026-01-02'],
            ['2026-12-31 12:00:00 Europe/Rome', '2026-12-31', '2025-12-31', '2027-01-01', '2026-01-01'],
        ];
    }

    private function row(int $id, ?float $amount, string $type = 'Uscita'): array
    {
        return ['CategoriaId' => $id, 'MacroNome' => $type, 'MesoNome' => 'Stesso nome', 'MicroNome' => 'Stesso nome', 'Totale' => $amount];
    }

    public function testUnionByIdentityCentsAndClassification(): void
    {
        $summary = (new ProgressivoSummary())->compare(
            [$this->row(1, 20.01), $this->row(2, 10.29)],
            [$this->row(2, 10), $this->row(3, 0.1, 'Entrata'), $this->row(3, 0.2, 'Entrata'), $this->row(4, null, 'Entrata extra')]
        );
        self::assertCount(2, $summary['uscite']);
        self::assertSame([1, 2], array_column($summary['uscite'], 'CategoriaId'));
        self::assertEquals([0, 10], array_column($summary['uscite'], 'TotaleAnnoInCorso'));
        self::assertEquals([-20.01, -0.29], array_column($summary['uscite'], 'Differenza'));
        self::assertCount(1, $summary['entrate']);
        self::assertEquals(0.3, $summary['entrate'][0]['TotaleAnnoInCorso']);
        self::assertEquals(0.3, $summary['entrate'][0]['Differenza']);
        self::assertCount(1, $summary['altre']);
        self::assertEquals(0, $summary['altre'][0]['Differenza']);
    }

    public function testEmptyAndOneSidedYears(): void
    {
        $service = new ProgressivoSummary();
        self::assertSame(['entrate' => [], 'uscite' => [], 'altre' => []], $service->compare([], []));
        self::assertEquals(-1.29, $service->compare([$this->row(1, 1.29)], [])['uscite'][0]['Differenza']);
        self::assertEquals(1.29, $service->compare([], [$this->row(1, 1.29)])['uscite'][0]['Differenza']);
    }
}
