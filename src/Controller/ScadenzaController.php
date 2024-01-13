<?php

namespace App\Controller;

use App\Form\ScadenzaType;
use App\Repository\ScadenzaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class ScadenzaController extends AbstractController
{
    #[Route('/admin/scadenza', name: 'app_scadenza_lista')]
    public function Lista(ScadenzaRepository $repo): Response
    {
        $elementi = $repo->lista();

        return $this->render('scadenza/lista.html.twig', [
            'lista' => $elementi,
        ]);
    }

    #[Route('/admin/scadenza/nuovo', name: 'app_scadenza_nuovo')]
    public function Nuovo(Request $request, EntityManagerInterface $em): Response
    {
        $elemento = null;
        $form = $this->createForm(ScadenzaType::class);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            $elemento = $form->getData();
            
            $em->persist($elemento);
            $em->flush();
            
            $this->addFlash('success', "Una nuova scadenza è stata aggiunta");

            return $this->redirectToRoute('app_scadenza_lista');
        }

        if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', "Ci sono degli errori nell'inserimento della scadenza");
        }

        return $this->render('scadenza/nuovo.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/scadenza/modifica/{id}', name: 'app_scadenza_modifica')]
    public function Modifica($id, Request $request, EntityManagerInterface $em, ScadenzaRepository $repo): Response
    {
        $elemento = $repo->find($id);

        $form = $this->createForm(ScadenzaType::class, $elemento);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            $elemento = $form->getData();
            
            $em->persist($elemento);
            $em->flush();

            $this->addFlash('success', 'I dati della scadenza sono stati modificati');
            
            return $this->redirectToRoute('app_scadenza_lista', [
                'id' => $elemento->getId(),
            ]);
        }

        if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', "Ci sono degli errori nella modifica della scadenza");
        }

        return $this->render('scadenza/modifica.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/scadenza/cancella/{id}/ok', name: 'app_scadenza_cancella_ok')]
    public function Cancella($id, ScadenzaRepository $repo, EntityManagerInterface $em): Response
    {
        $elemento = $repo->findOneBy(['id' => $id]);

        $em->remove($elemento);
        $em->flush();
        
        return $this->redirectToRoute('app_scadenza_lista');
    }

    #[Route('/admin/scadenza/mostra/{id}', name: 'app_scadenza_mostra')]
    public function Mostra($id, ScadenzaRepository $repo, EntityManagerInterface $em): Response
    {
        $elemento = $repo->findOneBy(['id' => $id]);        

        return $this->render('scadenza/mostra.html.twig', [
            'elemento' => $elemento,
        ]);
    }
}