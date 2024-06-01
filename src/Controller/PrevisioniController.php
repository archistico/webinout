<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MovimentoRepository;

class PrevisioniController extends AbstractController
{
    #[Route('/admin/previsioni', name: 'app_previsioni')]
    public function index(MovimentoRepository $movimentoRepository): Response
    {
        $datainizio = new \DateTime('2024-01-01');
        $oggi = new \DateTime();
        $difference = $oggi->diff($datainizio);
        $giorni_mese = 30.5;
        $mesi = round(($difference->days)/$giorni_mese);

        $lista = $movimentoRepository->listaPrevisione($datainizio, $mesi);

        $entrate = $movimentoRepository->listaPrevisioneCategoria($datainizio, $mesi, "Entrata")[0];
        $uscite = $movimentoRepository->listaPrevisioneCategoria($datainizio, $mesi, "Uscita")[0];

        return $this->render('previsioni/previsioni.html.twig', [
            'lista' => $lista,
            'datainizio' => $datainizio,
            'entrate' => $entrate,
            'uscite' => $uscite,
        ]);
    }
}
