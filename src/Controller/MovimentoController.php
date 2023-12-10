<?php

namespace App\Controller;

use App\Entity\Allegato;
use App\Form\MovimentoType;
use App\Repository\AllegatoRepository;
use App\Repository\MovimentoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

class MovimentoController extends AbstractController
{
    #[Route('/movimento', name: 'app_movimento_lista')]
    public function Lista(MovimentoRepository $movimentoRepository): Response
    {
        $elementi = $movimentoRepository->findAll();

        return $this->render('movimento/lista.html.twig', [
            'lista' => $elementi,
        ]);
    }

    #[Route('/movimento/nuovo', name: 'app_movimento_nuovo')]
    public function Nuovo(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $elemento = null;
        $form = $this->createForm(MovimentoType::class);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            $elemento = $form->getData();
            $allegati = $form->get('Allegati')->getData();
            
            $em->persist($elemento);
            $em->flush();

            $conteggio = 0;
            foreach($allegati as $allegato)
            {
                if ($allegato) {
                    
                    $originalFilename = pathinfo($allegato->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug("Data-".$elemento->getData()->format('Y-m-d') . "-Importo-".$elemento->getImporto()."-Movimento-".$elemento->getId()."-".$conteggio);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$allegato->guessExtension();
                    
                    $a = (new Allegato())
                    ->setMovimento($elemento)
                    ->setNomefile($newFilename)
                    ;

                    try {
                        $allegato->move(
                            $this->getParameter('allegati_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                    }

                    $em->persist($a);
                    $em->flush();

                    $conteggio += 1;
                }
            }
            
            $this->addFlash('success', "Un nuovo movimento è stato aggiunto");

            return $this->redirectToRoute('app_movimento_lista');
        }

        if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', "Ci sono degli errori nell'inserimento del movimento");
        }

        return $this->render('movimento/nuovo.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/movimento/modifica/{id}', name: 'app_movimento_modifica')]
    public function Modifica($id, Request $request, EntityManagerInterface $em, MovimentoRepository $movimentoRepository): Response
    {
        $elemento = $movimentoRepository->find($id);

        $form = $this->createForm(MovimentoType::class, $elemento);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            $elemento = $form->getData();
            
            $em->persist($elemento);
            $em->flush();

            $this->addFlash('success', 'I dati del movimento sono stati modificati');
            
            return $this->redirectToRoute('app_movimento_lista', [
                'id' => $elemento->getId(),
            ]);
        }

        if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', "Ci sono degli errori nella modifica del movimento");
        }

        return $this->render('movimento/modifica.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/movimento/cancella/{id}/ok', name: 'app_movimento_cancella_ok')]
    public function Cancella($id, MovimentoRepository $movimentoRepository, EntityManagerInterface $em): Response
    {
        $elemento = $movimentoRepository->findOneBy(['id' => $id]);

        $em->remove($elemento);
        $em->flush();
        
        return $this->redirectToRoute('app_movimento_lista');
    }

    #[Route('/movimento/mostra/{id}', name: 'app_movimento_mostra')]
    public function Mostra($id, MovimentoRepository $movimentoRepository, AllegatoRepository $allegatoRepository, EntityManagerInterface $em): Response
    {
        $movimento = $movimentoRepository->findOneBy(['id' => $id]);
        $allegati_directory = $this->getParameter('allegati_directory');

        return $this->render('movimento/mostra.html.twig', [
            'movimento' => $movimento,
            'allegati_directory' => $allegati_directory,
        ]);
    }
}
