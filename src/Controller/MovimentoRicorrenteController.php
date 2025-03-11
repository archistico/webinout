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

    // #[Route('/admin/movimento/cancella/ok/{id}', name: 'app_movimento_cancella_ok')]
    // public function CancellaOK($id, MovimentoRepository $movimentoRepository, EntityManagerInterface $em, Filesystem $filesystem): Response
    // {
    //     $movimento = $movimentoRepository->findOneBy(['id' => $id]);
    //     $allegati_directory = $this->getParameter('allegati_directory');

    //     return $this->render('movimento/cancella.html.twig', [
    //         'movimento' => $movimento,
    //         'allegati_directory' => $allegati_directory,
    //     ]);
    // }

    // #[Route('/admin/movimento/cancella/{id}', name: 'app_movimento_cancella')]
    // public function Cancella($id, MovimentoRepository $movimentoRepository, EntityManagerInterface $em, Filesystem $filesystem): Response
    // {
    //     $elemento = $movimentoRepository->findOneBy(['id' => $id]);
    //     $fileanno = $elemento->getData()->format('Y');
    //     $allegati = $elemento->getAllegati();

    //     foreach($allegati as $allegato)
    //     {
    //         $filename = $this->getParameter('allegati_directory').'/'.$allegato->getNomefile();
    //         if ($filesystem->exists($filename)) {
    //             $filesystem->remove($filename);
    //         }
    //     }

    //     $em->remove($elemento);
    //     $em->flush();
        
    //     return $this->redirectToRoute('app_movimento_lista');
    // }

    #[Route('/admin/ricorrenti/mostra/{id}', name: 'app_ricorrenti_mostra')]
    public function Mostra($id, MovimentoRicorrenteRepository $movimentoRicorrenteRepository): Response
    {
        $movimento = $movimentoRicorrenteRepository->findOneBy(['id' => $id]);
       
        return $this->render('movimento_ricorrente/mostra.html.twig', [
            'movimento' => $movimento,
        ]);
    }

}
