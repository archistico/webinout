<?php

namespace App\Controller;

use App\Form\TipoPagamentoType;
use App\Repository\TipoPagamentoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class TipoPagamentoController extends AbstractController
{
    #[Route('/tipopagamento', name: 'app_tipopagamento_lista')]
    public function medicoLista(TipoPagamentoRepository $tipoPagamentoRepository): Response
    {
        $elementi = $tipoPagamentoRepository->findAll();

        return $this->render('tipopagamento/lista.html.twig', [
            'lista' => $elementi,
        ]);
    }

    #[Route('/tipopagamento/nuovo', name: 'app_tipopagamento_nuovo')]
    public function TipoPagamentoNuovo(Request $request, EntityManagerInterface $em): Response
    {
        $elemento = null;
        $form = $this->createForm(TipoPagamentoType::class);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            $elemento = $form->getData();
            
            $em->persist($elemento);
            $em->flush();
            
            $this->addFlash('success', "Un nuovo tipo di pagamento è stato aggiunto");

            return $this->redirectToRoute('app_tipopagamento_lista');
        }

        if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', "Ci sono degli errori nell'inserimento del tipo di pagamento");
        }

        return $this->render('tipopagamento/nuovo.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/tipopagamento/modifica/{id}', name: 'app_tipopagamento_modifica')]
    public function medicoModifica($id, Request $request, EntityManagerInterface $em, TipoPagamentoRepository $tipoPagamentoRepository): Response
    {
        $elemento = $tipoPagamentoRepository->find($id);

        $form = $this->createForm(TipoPagamentoType::class, $elemento);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            $elemento = $form->getData();
            
            $em->persist($elemento);
            $em->flush();

            $this->addFlash('success', 'I dati del tipo di pagamento sono stati modificati');
            
            return $this->redirectToRoute('app_tipopagamento_lista', [
                'id' => $elemento->getId(),
            ]);
        }

        if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', "Ci sono degli errori nella modifica del medico");
        }

        return $this->render('tipopagamento/modifica.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/tipopagamento/cancella/{id}/ok', name: 'app_tipopagamento_cancella_ok')]
    public function TipoPagamentoCancella($id, TipoPagamentoRepository $tipoPagamentoRepository, EntityManagerInterface $em): Response
    {
        $elemento = $tipoPagamentoRepository->findOneBy(['id' => $id]);

        $em->remove($elemento);
        $em->flush();
        
        return $this->redirectToRoute('app_tipopagamento_lista');
    }
}
