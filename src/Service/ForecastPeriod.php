<?php

namespace App\Service;

class ForecastPeriod
{
    public function calculate(\DateTimeImmutable $today): array
    {
        $timezone = new \DateTimeZone('Europe/Rome');
        $start = new \DateTimeImmutable('2024-01-01', $timezone);
        $end = $today->setTimezone($timezone)->modify('first day of this month')->setTime(0, 0);
        $months = $end <= $start ? 0 : (int) $start->diff($end)->format('%y') * 12 + (int) $start->diff($end)->format('%m');

        return ['datainizio' => $start, 'datafine' => $end, 'mesi' => $months];
    }
}
