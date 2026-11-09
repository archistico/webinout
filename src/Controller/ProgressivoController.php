<?php

namespace App\Controller;

use App\Repository\MovimentoRepository;
use App\Service\ProgressivoSummary;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProgressivoController extends AbstractController
{
    #[Route('/admin/progressivo', name: 'app_progressivo')]
    public function progressivo(MovimentoRepository $movimentoRepository, ProgressivoSummary $summary): Response
    {
        $periods = $summary->periods(new \DateTimeImmutable());
        $previous = $movimentoRepository->listaSommaPerCategoriePeriodo($periods['inizioAnnoPassato'], $periods['limiteAnnoPassato']);
        $current = $movimentoRepository->listaSommaPerCategoriePeriodo($periods['inizioAnnoInCorso'], $periods['limiteAnnoInCorso']);

        return $this->render('progressivo/progressivo.html.twig', array_merge($periods, $summary->compare($previous, $current)));
    }
}
