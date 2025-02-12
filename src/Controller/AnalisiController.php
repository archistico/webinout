<?php

namespace App\Controller;

use App\Repository\MovimentoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class AnalisiController extends AbstractController
{
    #[Route('/admin/analisi', name: 'app_analisi')]
    public function analisi(MovimentoRepository $movimentoRepository, ChartBuilderInterface $chartBuilder): Response
    {
        $movimenti = $movimentoRepository->findAll();
        
        $entrate_totali = 0;
        $uscite_totali = 0;

        $listaUsciteMeso = [];
        
        foreach($movimenti as $m) 
        {
            $filemacro = $m->getCategoria()->getPadre()->getPadre()->getNome();

            if ($filemacro == "Entrata") {
                $entrate_totali += $m->getImporto();
            }
            if ($filemacro == "Uscita") {
                $uscite_totali += $m->getImporto();

                $filemeso = $m->getCategoria()->getPadre()->getNome();

                if (!array_key_exists($filemeso, $listaUsciteMeso)) {
                    $listaUsciteMeso[$filemeso] = $m->getImporto();
                } else {
                    $listaUsciteMeso[$filemeso] += $m->getImporto();
                }
            }
        }

        //dd($listaUsciteMeso);

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
            'labels' => array_keys($listaUsciteMeso),
            'datasets' => [
                [
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'data' => array_values($listaUsciteMeso),
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
        ]);
    }
}
