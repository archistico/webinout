<?php

namespace App\Controller;

use App\Form\ProgettoType;
use App\Repository\ProgettoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class ProgettoController extends AbstractController
{
    #[Route('/admin/progetto', name: 'app_progetto_lista')]
    public function Lista(ProgettoRepository $progettoRepository): Response
    {
        $elementi = $progettoRepository->findBy([], ['Descrizione' => 'ASC']);

        return $this->render('progetto/lista.html.twig', [
            'lista' => $elementi,
        ]);
    }

    #[Route('/admin/progetto/nuovo', name: 'app_progetto_nuovo')]
    public function Nuovo(Request $request, EntityManagerInterface $em): Response
    {
        $elemento = null;
        $form = $this->createForm(ProgettoType::class);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $elemento = $form->getData();

            $em->persist($elemento);
            $em->flush();

            $this->addFlash('success', 'Un nuovo progetto e stato aggiunto');

            return $this->redirectToRoute('app_progetto_lista');
        }

        if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', "Ci sono degli errori nell'inserimento del progetto");
        }

        return $this->render('progetto/nuovo.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/progetto/modifica/{id}', name: 'app_progetto_modifica')]
    public function Modifica($id, Request $request, EntityManagerInterface $em, ProgettoRepository $progettoRepository): Response
    {
        $elemento = $progettoRepository->find($id);

        $form = $this->createForm(ProgettoType::class, $elemento);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $elemento = $form->getData();

            $em->persist($elemento);
            $em->flush();

            $this->addFlash('success', 'I dati del progetto sono stati modificati');

            return $this->redirectToRoute('app_progetto_lista', [
                'id' => $elemento->getId(),
            ]);
        }

        if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Ci sono degli errori nella modifica del progetto');
        }

        return $this->render('progetto/modifica.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/progetto/cancella/{id}/ok', name: 'app_progetto_cancella_ok')]
    public function Cancella($id, ProgettoRepository $progettoRepository, EntityManagerInterface $em): Response
    {
        $elemento = $progettoRepository->findOneBy(['id' => $id]);

        $em->remove($elemento);
        $em->flush();

        return $this->redirectToRoute('app_progetto_lista');
    }
}
