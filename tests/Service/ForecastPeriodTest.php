<?php

namespace App\Tests\Service;

use App\Service\ForecastPeriod;
use PHPUnit\Framework\TestCase;

class ForecastPeriodTest extends TestCase
{
    /** @dataProvider periods */
    public function testCompleteMonths(string $now, int $months, string $end): void
    {
        $period = (new ForecastPeriod())->calculate(new \DateTimeImmutable($now));
        self::assertSame($months, $period['mesi']);
        self::assertSame('2024-01-01 00:00:00', $period['datainizio']->format('Y-m-d H:i:s'));
        self::assertSame($end.' 00:00:00', $period['datafine']->format('Y-m-d H:i:s'));
    }

    public function periods(): array
    {
        return [
            ['2023-12-20 Europe/Rome', 0, '2023-12-01'],
            ['2024-01-31 Europe/Rome', 0, '2024-01-01'],
            ['2024-02-29 Europe/Rome', 1, '2024-02-01'],
            ['2024-03-31 Europe/Rome', 2, '2024-03-01'],
            ['2026-11-09 Europe/Rome', 34, '2026-11-01'],
            ['2026-11-30 Europe/Rome', 34, '2026-11-01'],
            ['2026-12-31 23:30:00 UTC', 36, '2027-01-01'],
        ];
    }
}
