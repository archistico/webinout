<?php

namespace App\Controller;

use App\Entity\Eseguito;
use App\Form\EseguitoType;
use App\Form\ScadenzaEseguitoType;
use App\Repository\EseguitoRepository;
use App\Repository\ScadenzaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class EseguitoController extends AbstractController
{
    #[Route('/admin/eseguito', name: 'app_eseguito_lista')]
    public function Lista(EseguitoRepository $repo): Response
    {
        $elementi = $repo->findAll();

        return $this->render('eseguito/lista.html.twig', [
            'lista' => $elementi,
        ]);
    }

    #[Route('/admin/eseguito/nuovo', name: 'app_eseguito_nuovo')]
    public function Nuovo(Request $request, EntityManagerInterface $em): Response
    {
        $elemento = null;
        $form = $this->createForm(EseguitoType::class);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            $elemento = $form->getData();
            
            $em->persist($elemento);
            $em->flush();
            
            $this->addFlash('success', "Una nuova scadenza è stata eseguita");

            return $this->redirectToRoute('app_eseguito_lista');
        }

        if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', "Ci sono degli errori nell'inserimento della scadenza eseguita");
        }

        return $this->render('eseguito/nuovo.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/eseguito/modifica/{id}', name: 'app_eseguito_modifica')]
    public function Modifica($id, Request $request, EntityManagerInterface $em, EseguitoRepository $repo): Response
    {
        $elemento = $repo->find($id);

        $form = $this->createForm(EseguitoType::class, $elemento);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            $elemento = $form->getData();
            
            $em->persist($elemento);
            $em->flush();

            $this->addFlash('success', 'I dati della scadenza eseguita sono stati modificati');
            
            return $this->redirectToRoute('app_eseguito_lista', [
                'id' => $elemento->getId(),
            ]);
        }

        if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', "Ci sono degli errori nella modifica della scadenza eseguita");
        }

        return $this->render('eseguito/modifica.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/eseguito/cancella/{id}/ok', name: 'app_eseguito_cancella_ok')]
    public function Cancella($id, EseguitoRepository $repo, EntityManagerInterface $em): Response
    {
        $elemento = $repo->findOneBy(['id' => $id]);

        $em->remove($elemento);
        $em->flush();
        
        return $this->redirectToRoute('app_eseguito_lista');
    }

    #[Route('/admin/eseguito/mostra/{id}', name: 'app_eseguito_mostra')]
    public function Mostra($id, EseguitoRepository $repo, EntityManagerInterface $em): Response
    {
        $elemento = $repo->findOneBy(['id' => $id]);        

        return $this->render('eseguito/mostra.html.twig', [
            'elemento' => $elemento,
        ]);
    }

    #[Route('/admin/eseguito/nuovo/{id}', name: 'app_eseguito_nuovo_scadenza')]
    public function NuovoDaScadenza($id, Request $request, EntityManagerInterface $em, EseguitoRepository $repo, ScadenzaRepository $repoScadenza): Response
    {
        $elemento = $repoScadenza->find($id);
        $eseguito = new Eseguito();
        $eseguito
            ->setAttivita($elemento->getAttivita())
            ->setDataScadenza($elemento->getDataScadenza());

        $form = $this->createForm(ScadenzaEseguitoType::class, $eseguito);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            $params = $request->request->all();
            $differisci = $params['scadenza_eseguito']['Differisci'];

            /*
            'Anno'
            'Biennio'
            'Decennio'
            'Mese'
            'Semestre'
            */

            $intervalloQt = '';
            switch ($differisci) {
                case 'Anno':
                    $intervalloQt = 'P1Y';
                    break;
                case 'Biennio':
                    $intervalloQt = 'P2Y';
                    break;
                case 'Decennio':
                    $intervalloQt = 'P10Y';
                    break;
                case 'Semestre':
                    $intervalloQt = 'P6M';
                    break;
                case 'Mese':
                    $intervalloQt = 'P1M';
                    break;
                case 'Settimana':
                    $intervalloQt = 'P7D';
                    break;
                
                default:
                    $intervalloQt = 'P1D';
                    break;
            }

            $dataScadenza = \DateTime::createFromInterface($elemento->getDataScadenza());
            $intervallo = new \DateInterval($intervalloQt);
            $dataScadenza->add($intervallo);

            $elemento->setDataScadenza($dataScadenza);
            $em->persist($elemento);

            $eseguito = $form->getData();
            
            $em->persist($eseguito);
            $em->flush();

            $this->addFlash('success', 'I dati della scadenza eseguita sono stati modificati');
            
            return $this->redirectToRoute('app_eseguito_lista', [
                'id' => $eseguito->getId(),
            ]);
        }

        if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', "Ci sono degli errori nella modifica della scadenza eseguita");
        }

        return $this->render('eseguito/nuovo.scadenza.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
