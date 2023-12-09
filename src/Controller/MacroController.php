<?php

namespace App\Controller;

use App\Form\MacroType;
use App\Repository\MacroRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;


class MacroController extends AbstractController
{
    #[Route('/macro', name: 'app_macro_lista')]
    public function Lista(MacroRepository $macroRepository): Response
    {
        $elementi = $macroRepository->findAll();

        return $this->render('macro/lista.html.twig', [
            'lista' => $elementi,
        ]);
    }

    #[Route('/macro/nuovo', name: 'app_macro_nuovo')]
    public function Nuovo(Request $request, EntityManagerInterface $em): Response
    {
        $elemento = null;
        $form = $this->createForm(MacroType::class);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            $elemento = $form->getData();
            
            $em->persist($elemento);
            $em->flush();
            
            $this->addFlash('success', "Una nuova categoria macro è stato aggiunta");

            return $this->redirectToRoute('app_macro_lista');
        }

        if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', "Ci sono degli errori nell'inserimento della categoria macro");
        }

        return $this->render('macro/nuovo.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/macro/modifica/{id}', name: 'app_macro_modifica')]
    public function Modifica($id, Request $request, EntityManagerInterface $em, MacroRepository $macroRepository): Response
    {
        $elemento = $macroRepository->find($id);

        $form = $this->createForm(MacroType::class, $elemento);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            $elemento = $form->getData();
            
            $em->persist($elemento);
            $em->flush();

            $this->addFlash('success', 'I dati della categoria macro sono stati modificati');
            
            return $this->redirectToRoute('app_macro_lista', [
                'id' => $elemento->getId(),
            ]);
        }

        if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', "Ci sono degli errori nella modifica della categoria macro");
        }

        return $this->render('macro/modifica.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/macro/cancella/{id}/ok', name: 'app_macro_cancella_ok')]
    public function Cancella($id, MacroRepository $macroRepository, EntityManagerInterface $em): Response
    {
        $elemento = $macroRepository->findOneBy(['id' => $id]);

        $em->remove($elemento);
        $em->flush();
        
        return $this->redirectToRoute('app_macro_lista');
    }
}
