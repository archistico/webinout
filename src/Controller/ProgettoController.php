<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProgettoController extends AbstractController{
    #[Route('/progetto', name: 'app_progetto')]
    public function index(): Response
    {
        return $this->render('progetto/index.html.twig', [
            'controller_name' => 'ProgettoController',
        ]);
    }
}
