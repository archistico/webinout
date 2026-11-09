<?php

namespace App\Service;

use App\Entity\Movimento;

class HomeSummary
{
    /** @param iterable<Movimento> $movimenti */
    public function calculate(iterable $movimenti, \DateTimeImmutable $today): array
    {
        $today = $today->setTimezone(new \DateTimeZone('Europe/Rome'));
        $totals = ['entrate_mensili' => 0, 'uscite_mensili' => 0, 'entrate_annuali' => 0, 'uscite_annuali' => 0];
        $periods = [];

        foreach ($movimenti as $movimento) {
            $date = $movimento->getData();
            $period = $date->format('Y-m');
            $type = $movimento->getCategoria()->getPadre()->getPadre()->getNome();
            $periods[$period] ??= ['entrate' => 0, 'uscite' => 0];
            if (!in_array($type, ['Entrata', 'Uscita'], true)) {
                continue;
            }

            // Sum currency in cents to avoid floating-point drift in balances.
            $amount = (int) round(($movimento->getImporto() ?? 0) * 100);
            $key = $type === 'Entrata' ? 'entrate' : 'uscite';
            $periods[$period][$key] += $amount;
            if ($date->format('Y') === $today->format('Y')) {
                $totals[$key.'_annuali'] += $amount;
                if ($period === $today->format('Y-m')) {
                    $totals[$key.'_mensili'] += $amount;
                }
            }
        }

        ksort($periods);
        return array_merge(array_map(static fn (int $cents): float => $cents / 100, $totals), [
            'periodi' => array_keys($periods),
            'entrate_periodi' => array_map(static fn (array $period): float => $period['entrate'] / 100, array_values($periods)),
            'uscite_periodi' => array_map(static fn (array $period): float => $period['uscite'] / 100, array_values($periods)),
        ]);
    }
}
