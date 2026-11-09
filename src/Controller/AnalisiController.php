<?php

namespace App\Controller;

use App\Repository\MovimentoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class AnalisiController extends AbstractController
{
    #[Route('/admin/analisi', name: 'app_analisi')]
    public function analisi(MovimentoRepository $movimentoRepository, ChartBuilderInterface $chartBuilder): Response
    {
        $listaMacro = $movimentoRepository->listaTotaliPerMacro();
        $listaMeso = $movimentoRepository->listaTotaliPerMeso();
        $listaMicro = $movimentoRepository->listaTotaliPerMicro();

        $totals = ['Entrata' => 0, 'Uscita' => 0];
        foreach ($listaMacro as $row) {
            if (array_key_exists($row['MacroNome'], $totals)) {
                $totals[$row['MacroNome']] += (int) round((float) $row['Totale'] * 100);
            }
        }
        $entrate_totali = $totals['Entrata'] / 100;
        $uscite_totali = $totals['Uscita'] / 100;
        // Keep distinct category rows, even when their names are identical.
        $usciteMeso = array_values(array_filter($listaMeso, static fn (array $row): bool => $row['MacroNome'] === 'Uscita'));
        $nomiMeso = array_column($usciteMeso, 'MesoNome');
        $importiMeso = array_map(static fn (array $row): float => round((float) $row['Totale'], 2), $usciteMeso);

        $chart_analisi_1 = $chartBuilder->createChart(Chart::TYPE_BAR);
        $chart_analisi_1->setData([
            'labels' => ["Entrate", "Uscite"],
            'datasets' => [
                [
                    'backgroundColor' => ['rgb(30, 250, 132)', 'rgb(255, 99, 132)'],
                    'data' => [$entrate_totali, $uscite_totali],
                ],
            ],
        ]);

        $chart_analisi_2 = $chartBuilder->createChart(Chart::TYPE_BAR);
        $chart_analisi_2->setData([
            'labels' => $nomiMeso,
            'datasets' => [
                [
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'data' => $importiMeso,
                ],
            ],
        ]);

        $chart_analisi_1->setOptions([
            'plugins' => [
                'title' => [
                    'display' => true,
                    'text' => 'Entrate/Uscite totali',
                ],
                'legend' => [
                    'display' => false
                ], 
            ],          
        ]);

        $chart_analisi_2->setOptions([
            'plugins' => [
                'title' => [
                    'display' => true,
                    'text' => 'Uscite meso categorie',
                ],
                'legend' => [
                    'display' => false
                ],
            ],            
        ]);

        return $this->render('analisi/analisi.html.twig', [
            'chart_analisi_1' => $chart_analisi_1,
            'chart_analisi_2' => $chart_analisi_2,
            'haUscite' => count($usciteMeso) > 0,
            'listaMeso' => $listaMeso,
            'listaMacro' => $listaMacro,
            'listaMicro' => $listaMicro,
        ]);
    }
}
