<?php

namespace App\Service;

class ProgressivoSummary
{
    public function periods(\DateTimeImmutable $today): array
    {
        $today = $today->setTimezone(new \DateTimeZone('Europe/Rome'))->setTime(0, 0);
        $year = (int) $today->format('Y');
        $month = (int) $today->format('n');
        $previousMonth = $today->setDate($year - 1, $month, 1);
        $previousDay = min((int) $today->format('j'), (int) $previousMonth->format('t'));
        $previous = $previousMonth->setDate($year - 1, $month, $previousDay);

        return [
            'inizioAnnoPassato' => $today->setDate($year - 1, 1, 1),
            'fineAnnoPassato' => $previous,
            'limiteAnnoPassato' => $previous->modify('+1 day'),
            'inizioAnnoInCorso' => $today->setDate($year, 1, 1),
            'fineAnnoInCorso' => $today,
            'limiteAnnoInCorso' => $today->modify('+1 day'),
        ];
    }

    public function compare(array $previous, array $current): array
    {
        $categories = [];
        foreach (['TotaleAnnoScorso' => $previous, 'TotaleAnnoInCorso' => $current] as $column => $rows) {
            foreach ($rows as $row) {
                $id = $row['CategoriaId'];
                $categories[$id] ??= [
                    'Categoria' => $row['MacroNome'].' | '.$row['MesoNome'].' | '.$row['MicroNome'],
                    'MacroNome' => $row['MacroNome'],
                    'CategoriaId' => $id,
                    'TotaleAnnoScorso' => 0,
                    'TotaleAnnoInCorso' => 0,
                ];
                $categories[$id][$column] += (int) round((float) $row['Totale'] * 100);
            }
        }

        $result = ['entrate' => [], 'uscite' => [], 'altre' => []];
        foreach ($categories as $row) {
            $row['Differenza'] = ($row['TotaleAnnoInCorso'] - $row['TotaleAnnoScorso']) / 100;
            $row['TotaleAnnoScorso'] /= 100;
            $row['TotaleAnnoInCorso'] /= 100;
            $section = match ($row['MacroNome']) {
                'Entrata' => 'entrate',
                'Uscita' => 'uscite',
                default => 'altre',
            };
            $result[$section][] = $row;
        }
        foreach ($result as &$rows) {
            usort($rows, static fn (array $a, array $b): int => ($a['Differenza'] <=> $b['Differenza'])
                ?: strcmp($a['Categoria'], $b['Categoria'])
                ?: ($a['CategoriaId'] <=> $b['CategoriaId']));
        }
        unset($rows);

        return $result;
    }
}
