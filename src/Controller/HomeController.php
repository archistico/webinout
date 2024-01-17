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

        foreach($movimenti as $m) 
        {
            $annoInCorso = date("Y");
            $meseInCorso = date("m");

            if ($m->getData()->format('Y') == $annoInCorso) {
                $filemacro = $m->getCategoria()->getPadre()->getPadre()->getNome();
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
            //$filemicro = $m->getCategoria()->getNome();
            //$filemeso = $m->getCategoria()->getPadre()->getNome();
            
        }

        $movimentiSomme = $movimentoRepository->listaSommaPerCategorie();

        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);
        $chart->setData([
            'labels' => ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            'datasets' => [
                [
                    'label' => 'Sales!',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => [522, 1500, 2250, 2197, 2345, 3122, 3099],
                ],
            ],
        ]);

        return $this->render('home.html.twig', [
            'entrate_mensili' => $entrate_mensili,
            'uscite_mensili' => $uscite_mensili,
            'entrate_annuali' => $entrate_annuali,
            'uscite_annuali' => $uscite_annuali,
            'listaScadenze' => $listaScadenze,
            'movimentiSomme' => $movimentiSomme,
            'chart' => $chart,
        ]);
    }
}
