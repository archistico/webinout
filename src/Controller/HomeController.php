<?php

namespace App\Controller;

use App\Repository\MovimentoRepository;
use App\Repository\ScadenzaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class HomeController extends AbstractController
{
    #[Route('/admin', name: 'app_home')]
    public function Home(MovimentoRepository $movimentoRepository, ScadenzaRepository $scadezaRepository, ChartBuilderInterface $chartBuilder): Response
    {
        $listaScadenze = $scadezaRepository->listaUltime();

        $movimenti = $movimentoRepository->findAll();
        $entrate_mensili = 0;
        $uscite_mensili = 0;
        $entrate_annuali = 0;
        $uscite_annuali = 0;

        $listaPeriodi = [];
        $EntratePeriodi = [];
        $UscitePeriodi = [];

        foreach($movimenti as $m) 
        {
            $annoInCorso = date("Y");
            $meseInCorso = date("m");

            $periodo = $m->getData()->format('Y') . "-" . $m->getData()->format('m');

            if (!in_array($periodo, $listaPeriodi)) {
                $listaPeriodi[] = $periodo;
            }

            $filemacro = $m->getCategoria()->getPadre()->getPadre()->getNome();

            if ($filemacro == "Entrata") {
                if (!array_key_exists($periodo, $EntratePeriodi)) {
                    $EntratePeriodi[$periodo] = $m->getImporto();
                } else {
                    $EntratePeriodi[$periodo] = $EntratePeriodi[$periodo] + $m->getImporto();
                }
            }
            if ($filemacro == "Uscita") {
                if (!array_key_exists($periodo, $UscitePeriodi)) {
                    $UscitePeriodi[$periodo] = $m->getImporto();
                } else {
                    $UscitePeriodi[$periodo] = $UscitePeriodi[$periodo] + $m->getImporto();
                }
            }

            if ($m->getData()->format('Y') == $annoInCorso) {
                if ($filemacro == "Entrata") {
                    $entrate_annuali += $m->getImporto();
                }
                if ($filemacro == "Uscita") {
                    $uscite_annuali += $m->getImporto();
                }

                if ($m->getData()->format('m') == $meseInCorso) {
                    if ($filemacro == "Entrata") {
                        $entrate_mensili += $m->getImporto();
                    }
                    if ($filemacro == "Uscita") {
                        $uscite_mensili += $m->getImporto();
                    }
                }
            }
        }

        sort($listaPeriodi);

        foreach ($listaPeriodi as $p) {
            if (!array_key_exists($p, $EntratePeriodi)) {
                $EntratePeriodi[$p] = 0;
            }
            if (!array_key_exists($p, $UscitePeriodi)) {
                $UscitePeriodi[$p] = 0;
            }
        }

        ksort($EntratePeriodi);
        ksort($UscitePeriodi);

        //dump($EntratePeriodi);
        //dump($UscitePeriodi);
        //dd($listaPeriodi);

        $movimentiSomme = $movimentoRepository->listaSommaPerCategorie();

        $chart = $chartBuilder->createChart(Chart::TYPE_BAR);
        $chart->setData([
            'labels' => $listaPeriodi,
            'datasets' => [
                [
                    'label' => 'Entrate',
                    'backgroundColor' => 'rgb(30, 250, 132)',
                    'borderColor' => 'rgb(30, 250, 132)',
                    'data' => array_values($EntratePeriodi),
                ],
                [
                    'label' => 'Uscite',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => array_values($UscitePeriodi),
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
            'entrate_mensili' => $entrate_mensili,
            'uscite_mensili' => $uscite_mensili,
            'entrate_annuali' => $entrate_annuali,
            'uscite_annuali' => $uscite_annuali,
            'listaScadenze' => $listaScadenze,
            'movimentiSomme' => $movimentiSomme,
            'chart' => $chart,
            'listaUltimiMovimenti' => $listaUltimiMovimenti,
        ]);
    }
}
