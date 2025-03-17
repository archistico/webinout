<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ImpostazioniController extends AbstractController
{
    #[Route('/admin/impostazioni', name: 'app_impostazioni_home')]
    public function Home(): Response
    {
        return $this->render('impostazioni/index.html.twig', [
        ]);
    }

    #[Route('/admin/impostazioni/configurazioni', name: 'app_impostazioni_configurazioni_lista')]
    public function ConfigurazioniLista(): Response
    {
        return $this->render('impostazioni/index.html.twig', [
        ]);
    }

    #[Route('/admin/impostazioni/utenti', name: 'app_impostazioni_utenti_lista')]
    public function UtentiLista(): Response
    {
        return $this->render('impostazioni/index.html.twig', [
        ]);
    }
}
