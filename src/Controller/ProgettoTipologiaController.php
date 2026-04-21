<?php

namespace App\Controller;

use App\Form\ProgettoTipologiaType;
use App\Repository\ProgettoTipologiaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class ProgettoTipologiaController extends AbstractController
{
    #[Route('/admin/progettotipologia', name: 'app_progettotipologia_lista')]
    public function Lista(ProgettoTipologiaRepository $progettoTipologiaRepository): Response
    {
        $elementi = $progettoTipologiaRepository->findAll();

        return $this->render('progetto_tipologia/lista.html.twig', [
            'lista' => $elementi,
        ]);
    }

    #[Route('/admin/progettotipologia/nuovo', name: 'app_progettotipologia_nuovo')]
    public function Nuovo(Request $request, EntityManagerInterface $em): Response
    {
        $elemento = null;
        $form = $this->createForm(ProgettoTipologiaType::class);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $elemento = $form->getData();

            $em->persist($elemento);
            $em->flush();

            $this->addFlash('success', 'Una nuova tipologia progetto e stata aggiunta');

            return $this->redirectToRoute('app_progettotipologia_lista');
        }

        if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', "Ci sono degli errori nell'inserimento della tipologia progetto");
        }

        return $this->render('progetto_tipologia/nuovo.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/progettotipologia/modifica/{id}', name: 'app_progettotipologia_modifica')]
    public function Modifica($id, Request $request, EntityManagerInterface $em, ProgettoTipologiaRepository $progettoTipologiaRepository): Response
    {
        $elemento = $progettoTipologiaRepository->find($id);

        $form = $this->createForm(ProgettoTipologiaType::class, $elemento);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $elemento = $form->getData();

            $em->persist($elemento);
            $em->flush();

            $this->addFlash('success', 'I dati della tipologia progetto sono stati modificati');

            return $this->redirectToRoute('app_progettotipologia_lista', [
                'id' => $elemento->getId(),
            ]);
        }

        if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Ci sono degli errori nella modifica della tipologia progetto');
        }

        return $this->render('progetto_tipologia/modifica.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/progettotipologia/cancella/{id}/ok', name: 'app_progettotipologia_cancella_ok')]
    public function Cancella($id, ProgettoTipologiaRepository $progettoTipologiaRepository, EntityManagerInterface $em): Response
    {
        $elemento = $progettoTipologiaRepository->findOneBy(['id' => $id]);

        $em->remove($elemento);
        $em->flush();

        return $this->redirectToRoute('app_progettotipologia_lista');
    }
}
