<?php

namespace App\Controller;

use App\Form\MicroType;
use App\Repository\MicroRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class MicroController extends AbstractController
{
    #[Route('/admin/micro', name: 'app_categoria_micro_lista')]
    public function Lista(MicroRepository $microRepository): Response
    {
        $elementi = $microRepository->findAll();

        return $this->render('micro/lista.html.twig', [
            'lista' => $elementi,
        ]);
    }

    #[Route('/admin/micro/nuovo', name: 'app_categoria_micro_nuovo')]
    public function Nuovo(Request $request, EntityManagerInterface $em): Response
    {
        $elemento = null;
        $form = $this->createForm(MicroType::class);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            $elemento = $form->getData();
            
            $em->persist($elemento);
            $em->flush();
            
            $this->addFlash('success', "Una nuova categoria micro è stato aggiunta");

            return $this->redirectToRoute('app_categoria_micro_lista');
        }

        if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', "Ci sono degli errori nell'inserimento della categoria micro");
        }

        return $this->render('micro/nuovo.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/micro/modifica/{id}', name: 'app_categoria_micro_modifica')]
    public function Modifica($id, Request $request, EntityManagerInterface $em, MicroRepository $microRepository): Response
    {
        $elemento = $microRepository->find($id);

        $form = $this->createForm(MicroType::class, $elemento);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            $elemento = $form->getData();
            
            $em->persist($elemento);
            $em->flush();

            $this->addFlash('success', 'I dati della categoria micro sono stati modificati');
            
            return $this->redirectToRoute('app_categoria_micro_lista', [
                'id' => $elemento->getId(),
            ]);
        }

        if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', "Ci sono degli errori nella modifica della categoria micro");
        }

        return $this->render('micro/modifica.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/micro/cancella/{id}/ok', name: 'app_categoria_micro_cancella_ok')]
    public function Cancella($id, MicroRepository $microRepository, EntityManagerInterface $em): Response
    {
        $elemento = $microRepository->findOneBy(['id' => $id]);

        $em->remove($elemento);
        $em->flush();
        
        return $this->redirectToRoute('app_categoria_micro_lista');
    }
}
