<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function Home(): Response
    {
        return $this->render('prima.html.twig', []);
    }

    #[Route('/seconda', name: 'app_seconda')]
    public function seconda(): Response
    {
        return $this->render('seconda.html.twig', []);
    }
}
