<?php

namespace App\Controller;

use App\Form\ProgettoAzioneType;
use App\Repository\ProgettoAzioneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class ProgettoAzioneController extends AbstractController
{
    #[Route('/admin/progettoazione', name: 'app_progetto_azione_lista')]
    public function Lista(ProgettoAzioneRepository $progettoAzioneRepository): Response
    {
        $elementi = $progettoAzioneRepository->findBy([], ['Inizio' => 'ASC']);

        return $this->render('progetto_azione/lista.html.twig', [
            'lista' => $elementi,
        ]);
    }

    #[Route('/admin/progettoazione/nuovo', name: 'app_progetto_azione_nuovo')]
    public function Nuovo(Request $request, EntityManagerInterface $em): Response
    {
        $elemento = null;
        $form = $this->createForm(ProgettoAzioneType::class);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $elemento = $form->getData();

            $em->persist($elemento);
            $em->flush();

            $this->addFlash('success', 'Una nuova azione progetto e stata aggiunta');

            return $this->redirectToRoute('app_progetto_azione_lista');
        }

        if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', "Ci sono degli errori nell'inserimento dell'azione progetto");
        }

        return $this->render('progetto_azione/nuovo.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/progettoazione/modifica/{id}', name: 'app_progetto_azione_modifica')]
    public function Modifica($id, Request $request, EntityManagerInterface $em, ProgettoAzioneRepository $progettoAzioneRepository): Response
    {
        $elemento = $progettoAzioneRepository->find($id);

        $form = $this->createForm(ProgettoAzioneType::class, $elemento);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $elemento = $form->getData();

            $em->persist($elemento);
            $em->flush();

            $this->addFlash('success', "I dati dell'azione progetto sono stati modificati");

            return $this->redirectToRoute('app_progetto_azione_lista', [
                'id' => $elemento->getId(),
            ]);
        }

        if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', "Ci sono degli errori nella modifica dell'azione progetto");
        }

        return $this->render('progetto_azione/modifica.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/progettoazione/cancella/{id}/ok', name: 'app_progetto_azione_cancella_ok')]
    public function Cancella($id, ProgettoAzioneRepository $progettoAzioneRepository, EntityManagerInterface $em): Response
    {
        $elemento = $progettoAzioneRepository->findOneBy(['id' => $id]);

        $em->remove($elemento);
        $em->flush();

        return $this->redirectToRoute('app_progetto_azione_lista');
    }
}
