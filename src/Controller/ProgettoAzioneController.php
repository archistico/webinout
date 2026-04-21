<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProgettoAzioneController extends AbstractController{
    #[Route('/progetto/azione', name: 'app_progetto_azione')]
    public function index(): Response
    {
        return $this->render('progetto_azione/index.html.twig', [
            'controller_name' => 'ProgettoAzioneController',
        ]);
    }
}
