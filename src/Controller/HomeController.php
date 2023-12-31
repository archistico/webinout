<?php

namespace App\Controller;

use App\Repository\MovimentoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function Home(MovimentoRepository $movimentoRepository): Response
    {
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

        return $this->render('home.html.twig', [
            'entrate_mensili' => $entrate_mensili,
            'uscite_mensili' => $uscite_mensili,
            'entrate_annuali' => $entrate_annuali,
            'uscite_annuali' => $uscite_annuali,
        ]);
    }
}
