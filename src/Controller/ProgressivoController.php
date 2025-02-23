<?php

namespace App\Controller;

use App\Repository\MovimentoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class ProgressivoController extends AbstractController
{
    #[Route('/admin/progressivo', name: 'app_progressivo')]
    public function progressivo(MovimentoRepository $movimentoRepository, ChartBuilderInterface $chartBuilder): Response
    {
        
        // Analisi tipo Graf con le somme degli importi rispetto all'anno passato
        $formatoData = 'Y-m-d H:i:s';
        $inizioAnnoPassato = \DateTime::createFromFormat($formatoData, date('Y') . '-01-01 00:00:00');
        $fineAnnoPassato = \DateTime::createFromFormat($formatoData, date($formatoData));
        $inizioAnnoPassato->sub(new \DateInterval('P1Y'));
        $fineAnnoPassato->sub(new \DateInterval('P1Y'));
        $listaAnnoPassato = $movimentoRepository->listaSommaPerCategoriePeriodo($inizioAnnoPassato, $fineAnnoPassato);

        $inizioAnnoInCorso = \DateTime::createFromFormat($formatoData, date('Y') . '-01-01 00:00:00');
        $fineAnnoInCorso = \DateTime::createFromFormat($formatoData, date($formatoData));
        $listaAnnoInCorso = $movimentoRepository->listaSommaPerCategoriePeriodo($inizioAnnoInCorso, $fineAnnoInCorso);

        $progressivo = [];

        foreach($listaAnnoPassato as $scorso) 
        {
            $categoria = $scorso['MacroNome']. " | " . $scorso['MesoNome']. " | " . $scorso['MicroNome'];
            $totaleAnnoScorso = $scorso['Totale'];
            $totaleAnnoInCorso = 0;
            
            foreach($listaAnnoInCorso as $odierno) 
            {
                $categoriaOdierna = $odierno['MacroNome']. " | " . $odierno['MesoNome']. " | " . $odierno['MicroNome'];
                if ($categoria == $categoriaOdierna)
                {
                    $totaleAnnoInCorso = $odierno['Totale'];
                    break;
                }
            }

            $progressivo[] = ['Categoria' => $categoria, 'TotaleAnnoScorso' => $totaleAnnoScorso, 'TotaleAnnoInCorso' => $totaleAnnoInCorso, 'Differenza' => 0];
        }

        // Cerca le categorie che non sono state valorizzate l'anno scorso
        foreach($listaAnnoInCorso as $odierno) 
        {
            $categoria = $odierno['MacroNome']. " | " . $odierno['MesoNome']. " | " . $odierno['MicroNome'];
            $totaleAnnoScorso = 0;
            $totaleAnnoInCorso = $odierno['Totale'];
            
            $presente = false;
            foreach($progressivo as $p) 
            {
                if ($p['Categoria'] == $categoria)
                {
                    $presente = true;
                }
            }

            if (!$presente) {
                $progressivo[] = ['Categoria' => $categoria, 'TotaleAnnoScorso' => $totaleAnnoScorso, 'TotaleAnnoInCorso' => $totaleAnnoInCorso, 'Differenza' => 0];
            }
        }

        for($i = 0; $i<count($progressivo); $i++) {
            $progressivo[$i]['Differenza'] = $progressivo[$i]['TotaleAnnoInCorso'] - $progressivo[$i]['TotaleAnnoScorso'];
        }

        //dd($progressivo);

        return $this->render('progressivo/progressivo.html.twig', [
            'inizioAnnoPassato' => $inizioAnnoPassato,
            'fineAnnoPassato' => $fineAnnoPassato,
            'inizioAnnoInCorso' => $inizioAnnoInCorso,
            'fineAnnoInCorso' => $fineAnnoInCorso,
            'progressivo' => $progressivo,
        ]);
    }
}
