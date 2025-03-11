<?php

namespace App\Controller;

use App\Entity\Allegato;
use App\Form\MovimentoType;
use App\Form\MovimentoRicorrenteType;
use App\Pdf\PdfMovimentoService;
use App\Repository\AllegatoRepository;
use App\Repository\MovimentoRepository;
use App\Repository\MovimentoRicorrenteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class MovimentoRicorrenteController extends AbstractController
{
    #[Route('/admin/ricorrenti', name: 'app_ricorrenti_lista')]
    public function Lista(MovimentoRicorrenteRepository $movimentoRicorrenteRepository): Response
    {
        $elementi = $movimentoRicorrenteRepository->lista();
        
        return $this->render('movimento_ricorrente/lista.html.twig', [
            'lista' => $elementi,
        ]);
    }

    #[Route('/admin/ricorrenti/nuovo', name: 'app_ricorrenti_nuovo')]
    public function Nuovo(Request $request, EntityManagerInterface $em): Response
    {
        $elemento = null;
        $form = $this->createForm(MovimentoRicorrenteType::class);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            $elemento = $form->getData();
            
            $em->persist($elemento);
            $em->flush();
            
            $this->addFlash('success', "Un nuovo movimento ricorrente è stato aggiunto");

            return $this->redirectToRoute('app_ricorrenti_lista');
        }

        if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', "Ci sono degli errori nell'inserimento del movimento ricorrente");
        }

        return $this->render('movimento_ricorrente/nuovo.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/ricorrenti/modifica/{id}', name: 'app_ricorrenti_modifica')]
    public function Modifica($id, Request $request, EntityManagerInterface $em, MovimentoRicorrenteRepository $movimentoRicorrenteRepository): Response
    {
        $elemento = $movimentoRicorrenteRepository->find($id);

        $form = $this->createForm(MovimentoRicorrenteType::class, $elemento);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            $elemento = $form->getData();
            
            $em->persist($elemento);
            $em->flush();

            $this->addFlash('success', 'I dati del movimento ricorrente sono stati modificati');
            
            return $this->redirectToRoute('app_ricorrenti_lista', [
                'id' => $elemento->getId(),
            ]);
        }

        if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', "Ci sono degli errori nella modifica del movimento ricorrente");
        }

        return $this->render('movimento_ricorrente/modifica.html.twig', [
            'form' => $form->createView(),
            'elementoid' => $id,
        ]);
    }

    #[Route('/admin/ricorrenti/cancella/ok/{id}', name: 'app_ricorrenti_cancella_ok')]
    public function CancellaOK($id, MovimentoRicorrenteRepository $movimentoRicorrenteRepository): Response
    {
        $movimento = $movimentoRicorrenteRepository->findOneBy(['id' => $id]);
        
        return $this->render('movimento_ricorrente/cancella.html.twig', [
            'movimento' => $movimento,
        ]);
    }

    #[Route('/admin/ricorrenti/cancella/{id}', name: 'app_ricorrenti_cancella')]
    public function Cancella($id, MovimentoRicorrenteRepository $movimentoRicorrenteRepository, EntityManagerInterface $em): Response
    {
        $elemento = $movimentoRicorrenteRepository->findOneBy(['id' => $id]);

        $em->remove($elemento);
        $em->flush();
        
        return $this->redirectToRoute('app_ricorrenti_lista');
    }

    #[Route('/admin/ricorrenti/mostra/{id}', name: 'app_ricorrenti_mostra')]
    public function Mostra($id, MovimentoRicorrenteRepository $movimentoRicorrenteRepository): Response
    {
        $movimento = $movimentoRicorrenteRepository->findOneBy(['id' => $id]);

        $inizio = $movimento->getInizio();
        $fine = $movimento->getFine();
        $frequenza = $movimento->getFrequenza();

        switch ($frequenza) {
            case "Settimanale":
                $intervallo = new \DateInterval('P1W');
                break;
            case "Mensile":
                $intervallo = new \DateInterval('P1M');
                break;
            case "Trimestrale":
                $intervallo = new \DateInterval('P3M');
                break;
            case "Quadrimestrale":
                $intervallo = new \DateInterval('P4M');    
                break;
            case "Semestrale":
                $intervallo = new \DateInterval('P6M');
                break;
            case "Annuale":
                $intervallo = new \DateInterval('P1Y');
                break;
        }

        if ($fine == null) {
            $fine = new \DateTime('now');
            $fine->add($intervallo);
        }

        $listaPagamenti = [];
        $nextDate = \DateTime::createFromFormat('Y-m-d', $inizio->format('Y-m-d'));
        do {
            $listaPagamenti[] = $nextDate;
            $nextDate = \DateTime::createFromFormat('Y-m-d', $nextDate->format('Y-m-d'));
            $nextDate = $nextDate->add($intervallo);            
        } while ($nextDate->format('Y-m-d') <= $fine->format('Y-m-d'));

        return $this->render('movimento_ricorrente/mostra.html.twig', [
            'movimento' => $movimento,
            'listaPagamenti' => $listaPagamenti,
        ]);
    }

}
