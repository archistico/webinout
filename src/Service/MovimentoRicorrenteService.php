<?php

namespace App\Service;

use App\Entity\Movimento;
use App\Entity\MovimentoRicorrente;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\MovimentoRicorrenteRepository;

class MovimentoRicorrenteService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private MovimentoRicorrenteRepository $repo
    ) {}

    public function processaMovimentoRicorrente(): void
    {
        $lista_ricorrenti = $this->repo->findAll();
        $oggi = new \DateTime('today');

        foreach ($lista_ricorrenti as $ricorrente) {
            if ($ricorrente->isAttivo()) {
                $listaPagamenti = $this->listaPagamenti($ricorrente);
                
                if (in_array($oggi, $listaPagamenti)){
                    $this->aggiuntiMovimentoRicorrente($ricorrente, $oggi);
                }
            }            
        }

        $this->entityManager->flush();
    }

    private function aggiuntiMovimentoRicorrente(MovimentoRicorrente $ricorrente, \DateTime $data): void
    {
        $movimento = new Movimento();
        $movimento->setData($data);
        $movimento->setCategoria($ricorrente->getCategoria());
        $movimento->setImporto($ricorrente->getImporto());
        $movimento->setTipo($ricorrente->getTipo());
        $movimento->setNote("Ricorrente: ".$ricorrente->getDescrizione());
        
        $this->entityManager->persist($movimento);
    }

    public function listaPagamenti(MovimentoRicorrente $movimento): array
    {
        $inizio = $movimento->getInizio();
        $fine = $movimento->getFine();
        $frequenza = $movimento->getFrequenza();

        switch ($frequenza) {
            case "Settimanale":
                $intervallo = new \DateInterval('P1W');
                break;
            case "Mensile":
                $intervallo = new \DateInterval('P1M');
                break;
            case "Trimestrale":
                $intervallo = new \DateInterval('P3M');
                break;
            case "Quadrimestrale":
                $intervallo = new \DateInterval('P4M');    
                break;
            case "Semestrale":
                $intervallo = new \DateInterval('P6M');
                break;
            case "Annuale":
                $intervallo = new \DateInterval('P1Y');
                break;
        }

        if ($fine == null) {
            $fine = new \DateTime('today');
            $fine->add($intervallo);
        }

        $listaPagamenti = [];
        $nextDate = \DateTime::createFromFormat('!Y-m-d', $inizio->format('Y-m-d')); // !Y-m-d crea senza time
        do {
            $listaPagamenti[] = $nextDate;
            $nextDate = \DateTime::createFromFormat('!Y-m-d', $nextDate->format('Y-m-d'));
            $nextDate = $nextDate->add($intervallo);            
        } while ($nextDate->format('Y-m-d') <= $fine->format('Y-m-d'));

        return $listaPagamenti;
    }
}