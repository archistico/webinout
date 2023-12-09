<?php

namespace App\Controller;

use App\Form\MesoType;
use App\Repository\MesoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class MesoController extends AbstractController
{
    #[Route('/meso', name: 'app_meso_lista')]
    public function Lista(MesoRepository $mesoRepository): Response
    {
        $elementi = $mesoRepository->findAll();

        return $this->render('meso/lista.html.twig', [
            'lista' => $elementi,
        ]);
    }

    #[Route('/meso/nuovo', name: 'app_meso_nuovo')]
    public function Nuovo(Request $request, EntityManagerInterface $em): Response
    {
        $elemento = null;
        $form = $this->createForm(MesoType::class);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            $elemento = $form->getData();
            
            $em->persist($elemento);
            $em->flush();
            
            $this->addFlash('success', "Una nuova categoria meso è stato aggiunta");

            return $this->redirectToRoute('app_meso_lista');
        }

        if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', "Ci sono degli errori nell'inserimento della categoria meso");
        }

        return $this->render('meso/nuovo.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/meso/modifica/{id}', name: 'app_meso_modifica')]
    public function Modifica($id, Request $request, EntityManagerInterface $em, MesoRepository $mesoRepository): Response
    {
        $elemento = $mesoRepository->find($id);

        $form = $this->createForm(MesoType::class, $elemento);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            $elemento = $form->getData();
            
            $em->persist($elemento);
            $em->flush();

            $this->addFlash('success', 'I dati della categoria meso sono stati modificati');
            
            return $this->redirectToRoute('app_meso_lista', [
                'id' => $elemento->getId(),
            ]);
        }

        if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', "Ci sono degli errori nella modifica della categoria meso");
        }

        return $this->render('meso/modifica.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/meso/cancella/{id}/ok', name: 'app_meso_cancella_ok')]
    public function Cancella($id, MesoRepository $mesoRepository, EntityManagerInterface $em): Response
    {
        $elemento = $mesoRepository->findOneBy(['id' => $id]);

        $em->remove($elemento);
        $em->flush();
        
        return $this->redirectToRoute('app_meso_lista');
    }
}
