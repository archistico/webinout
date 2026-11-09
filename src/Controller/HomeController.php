<?php

namespace App\Controller;

use App\Repository\MovimentoRepository;
use App\Repository\ScadenzaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\HomeSummary;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class HomeController extends AbstractController
{
    #[Route('/admin', name: 'app_home')]
    public function Home(MovimentoRepository $movimentoRepository, ScadenzaRepository $scadezaRepository, ChartBuilderInterface $chartBuilder, HomeSummary $summary): Response
    {
        $listaScadenze = $scadezaRepository->listaUltime();

        $totals = $summary->calculate($movimentoRepository->findAll(), new \DateTimeImmutable());

        $chart = $chartBuilder->createChart(Chart::TYPE_BAR);
        $chart->setData([
            'labels' => $totals['periodi'],
            'datasets' => [
                [
                    'label' => 'Entrate',
                    'backgroundColor' => 'rgb(30, 250, 132)',
                    'borderColor' => 'rgb(30, 250, 132)',
                    'data' => $totals['entrate_periodi'],
                ],
                [
                    'label' => 'Uscite',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => $totals['uscite_periodi'],
                ],
            ],
        ]);

        $chart->setOptions([
            'scales' => [
                'x' => [
                    'stacked' => true,
                ],
                'y' => [
                    'stacked' => true,
                ],
            ],
        ]);

        $listaUltimiMovimenti = $movimentoRepository->listaUltimiMovimenti(5);

        return $this->render('home.html.twig', [
            'entrate_mensili' => $totals['entrate_mensili'],
            'uscite_mensili' => $totals['uscite_mensili'],
            'entrate_annuali' => $totals['entrate_annuali'],
            'uscite_annuali' => $totals['uscite_annuali'],
            'listaScadenze' => $listaScadenze,
            'chart' => $chart,
            'listaUltimiMovimenti' => $listaUltimiMovimenti,
        ]);
    }
}
