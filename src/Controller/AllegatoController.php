<?php

namespace App\Controller;

use App\Form\TipoPagamentoType;
use App\Repository\AllegatoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;

class AllegatoController extends AbstractController
{
    #[Route('/allegato', name: 'app_allegato_lista')]
    public function Lista(AllegatoRepository $allegatoRepository): Response
    {
        $elementi = $allegatoRepository->findAll();

        return $this->render('allegato/lista.html.twig', [
            'lista' => $elementi,
        ]);
    }

    #[Route('/allegato/nuovo', name: 'app_allegato_nuovo')]
    public function Nuovo(Request $request, EntityManagerInterface $em): Response
    {
        $elemento = null;
        $form = $this->createForm(TipoPagamentoType::class);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            $elemento = $form->getData();
            
            $em->persist($elemento);
            $em->flush();
            
            $this->addFlash('success', "Un nuovo allegato è stato aggiunto");

            return $this->redirectToRoute('app_allegato_lista');
        }

        if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', "Ci sono degli errori nell'inserimento del allegato");
        }

        return $this->render('allegato/nuovo.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/allegato/modifica/{id}', name: 'app_allegato_modifica')]
    public function Modifica($id, Request $request, EntityManagerInterface $em, AllegatoRepository $allegatoRepository): Response
    {
        $elemento = $allegatoRepository->find($id);

        $form = $this->createForm(TipoPagamentoType::class, $elemento);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            $elemento = $form->getData();
            
            $em->persist($elemento);
            $em->flush();

            $this->addFlash('success', 'I dati del allegato sono stati modificati');
            
            return $this->redirectToRoute('app_allegato_lista', [
                'id' => $elemento->getId(),
            ]);
        }

        if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', "Ci sono degli errori nella modifica del allegato");
        }

        return $this->render('allegato/modifica.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/allegato/cancella/{id}/ok', name: 'app_allegato_cancella_ok')]
    public function Cancella($id, AllegatoRepository $allegatoRepository, EntityManagerInterface $em, Filesystem $filesystem): Response
    {
        $elemento = $allegatoRepository->findOneBy(['id' => $id]);

        $filename = $this->getParameter('allegati_directory').'/'.$elemento->getNomefile();
        if ($filesystem->exists($filename)) {
            $filesystem->remove($filename);
        }

        $em->remove($elemento);
        $em->flush();
        
        return $this->redirectToRoute('app_allegato_lista');
    }
}
