<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MovimentoRepository;
use App\Service\ForecastPeriod;

class PrevisioniController extends AbstractController
{
    #[Route('/admin/previsioni', name: 'app_previsioni')]
    public function index(MovimentoRepository $movimentoRepository, ForecastPeriod $forecastPeriod): Response
    {
        $period = $forecastPeriod->calculate(new \DateTimeImmutable());
        ['datainizio' => $datainizio, 'datafine' => $datafine, 'mesi' => $mesi] = $period;

        $lista = $mesi > 0 ? $movimentoRepository->listaPrevisione($datainizio, $mesi, $datafine) : [];
        $listaMeso = $mesi > 0 ? $movimentoRepository->listaPrevisioneMeso($datainizio, $mesi, $datafine) : [];

        $listaEntrate = $mesi > 0 ? $movimentoRepository->listaPrevisioneCategoria($datainizio, $mesi, 'Entrata', $datafine) : [];
        $listaUscite = $mesi > 0 ? $movimentoRepository->listaPrevisioneCategoria($datainizio, $mesi, 'Uscita', $datafine) : [];

        $entrate = ['Totale' => (float) ($listaEntrate[0]['Totale'] ?? 0)];
        $uscite = ['Totale' => (float) ($listaUscite[0]['Totale'] ?? 0)];

        return $this->render('previsioni/previsioni.html.twig', [
            'lista' => $lista,
            'listaMeso' => $listaMeso,
            'datainizio' => $datainizio,
            'datafine' => $datafine->modify('-1 day'),
            'mesi' => $mesi,
            'entrate' => $entrate,
            'uscite' => $uscite,
        ]);
    }
}
