<?php

namespace App\Tests\Entity;

use App\Entity\Scadenza;
use PHPUnit\Framework\TestCase;

class ScadenzaTest extends TestCase
{
    /** @dataProvider dates */
    public function testCalendarDays(string $now, string $due, int $expected): void
    {
        $scadenza = (new Scadenza())->setDataScadenza(new \DateTime($due));
        self::assertSame($expected, $scadenza->getDifferenzaGiorni(new \DateTimeImmutable($now)));
    }

    public function dates(): array
    {
        return [
            ['2026-11-09 00:01:00 Europe/Rome', '2026-11-09', 0],
            ['2026-11-09 23:59:00 Europe/Rome', '2026-11-09', 0],
            ['2026-11-09 10:00:00 Europe/Rome', '2026-11-10', 1],
            ['2026-11-09 10:00:00 Europe/Rome', '2026-11-08', -1],
            ['2026-11-09 23:30:00 UTC', '2026-11-10', 0],
            ['2026-03-28 23:00:00 Europe/Rome', '2026-03-30', 2],
            ['2026-10-24 23:00:00 Europe/Rome', '2026-10-26', 2],
            ['2026-12-31 23:00:00 Europe/Rome', '2027-01-01', 1],
        ];
    }
}
