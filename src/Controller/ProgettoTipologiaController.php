<?php

namespace App\Controller;

use App\Repository\ProgettoTipologiaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProgettoTipologiaController extends AbstractController{

    #[Route('/admin/progettotipologia', name: 'app_progettotipologia_lista')]
    public function Lista(ProgettoTipologiaRepository $progettoTipologiaRepository): Response
    {
        $elementi = $progettoTipologiaRepository->findAll();

        return $this->render('progetto_tipologia/lista.html.twig', [
            'lista' => $elementi,
        ]);
    }
}
