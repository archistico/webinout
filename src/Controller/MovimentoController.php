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
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

class MovimentoController extends AbstractController
{
    #[Route('/admin/movimento/totali', name: 'app_movimento_totali')]
    public function Totali(MovimentoRepository $movimentoRepository): Response
    {
        $elementi = $movimentoRepository->SommaImportiAnni();
        //dd($elementi);

        return $this->render('movimento/totali.html.twig', [
            'lista' => $elementi,
        ]);
    }

    #[Route('/admin/movimento', name: 'app_movimento_lista')]
    public function Lista(MovimentoRepository $movimentoRepository): Response
    {
        $elementi = $movimentoRepository->lista();
        
        return $this->render('movimento/lista.html.twig', [
            'lista' => $elementi,
        ]);
    }

    #[Route('/admin/movimento/nuovo', name: 'app_movimento_nuovo')]
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
                    
                    //$originalFilename = pathinfo($allegato->getClientOriginalName(), PATHINFO_FILENAME);
                    $filedata = $elemento->getData()->format('Y-m-d');
                    $fileanno = $elemento->getData()->format('Y');
                    $fileimporto = $elemento->getImporto();
                    $filemovimentoid = $elemento->getId();

                    $filemicro = $elemento->getCategoria()->getNome();
                    $filemeso = $elemento->getCategoria()->getPadre()->getNome();
                    $filemacro = $elemento->getCategoria()->getPadre()->getPadre()->getNome();

                    $safeFilename = $slugger->slug($filedata . "-".$filemacro."-".$filemeso."-".$filemicro. "-Importo-".$fileimporto."-Mov-".$filemovimentoid."-N-".$conteggio);
                    $newFilename = $fileanno.'/'.$safeFilename.'-'.uniqid().'.'.$allegato->guessExtension();
                    
                    $a = (new Allegato())
                    ->setMovimento($elemento)
                    ->setNomefile($newFilename)
                    ;

                    try {
                        $allegato->move(
                            $this->getParameter('allegati_directory').'/'.$fileanno,
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

    #[Route('/admin/movimento/modifica/{id}', name: 'app_movimento_modifica')]
    public function Modifica($id, Request $request, EntityManagerInterface $em, MovimentoRepository $movimentoRepository, SluggerInterface $slugger): Response
    {
        $elemento = $movimentoRepository->find($id);

        $form = $this->createForm(MovimentoType::class, $elemento);

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
                    
                    //$originalFilename = pathinfo($allegato->getClientOriginalName(), PATHINFO_FILENAME);
                    $filedata = $elemento->getData()->format('Y-m-d');
                    $fileanno = $elemento->getData()->format('Y');
                    $fileimporto = $elemento->getImporto();
                    $filemovimentoid = $elemento->getId();

                    $filemicro = $elemento->getCategoria()->getNome();
                    $filemeso = $elemento->getCategoria()->getPadre()->getNome();
                    $filemacro = $elemento->getCategoria()->getPadre()->getPadre()->getNome();

                    $safeFilename = $slugger->slug($filedata . "-".$filemacro."-".$filemeso."-".$filemicro. "-Importo-".$fileimporto."-Mov-".$filemovimentoid."-N-".$conteggio);
                    $newFilename = $fileanno.'/'.$safeFilename.'-'.uniqid().'.'.$allegato->guessExtension();
                    
                    $a = (new Allegato())
                    ->setMovimento($elemento)
                    ->setNomefile($newFilename)
                    ;

                    try {
                        $allegato->move(
                            $this->getParameter('allegati_directory').'/'.$fileanno,
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

    #[Route('/admin/movimento/cancella/ok/{id}', name: 'app_movimento_cancella_ok')]
    public function CancellaOK($id, MovimentoRepository $movimentoRepository, EntityManagerInterface $em, Filesystem $filesystem): Response
    {
        $movimento = $movimentoRepository->findOneBy(['id' => $id]);
        $allegati_directory = $this->getParameter('allegati_directory');

        return $this->render('movimento/cancella.html.twig', [
            'movimento' => $movimento,
            'allegati_directory' => $allegati_directory,
        ]);
    }

    #[Route('/admin/movimento/cancella/{id}', name: 'app_movimento_cancella')]
    public function Cancella($id, MovimentoRepository $movimentoRepository, EntityManagerInterface $em, Filesystem $filesystem): Response
    {
        $elemento = $movimentoRepository->findOneBy(['id' => $id]);
        $fileanno = $elemento->getData()->format('Y');
        $allegati = $elemento->getAllegati();

        foreach($allegati as $allegato)
        {
            $filename = $this->getParameter('allegati_directory').'/'.$allegato->getNomefile();
            if ($filesystem->exists($filename)) {
                $filesystem->remove($filename);
            }
        }

        $em->remove($elemento);
        $em->flush();
        
        return $this->redirectToRoute('app_movimento_lista');
    }

    #[Route('/admin/movimento/mostra/{id}', name: 'app_movimento_mostra')]
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
