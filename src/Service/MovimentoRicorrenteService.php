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

    public function processRecurringMovements(\DateTime $date): void
    {
        $lista_ricorrenti = $this->repo->findRicorrenti($date);

        foreach ($lista_ricorrenti as $movimento_ricorrente) {
            $this->aggiuntiMovimentoRicorrente($movimento_ricorrente, $date);
        }

        $this->entityManager->flush();
    }

    private function aggiuntiMovimentoRicorrente(MovimentoRicorrente $ricorrente, \DateTime $date): void
    {
        $movimento = new Movimento();
        $movimento->setData($date);
        $movimento->setCategoria($ricorrente->getCategoria());
        $movimento->setImporto($ricorrente->getImporto());
        $movimento->setTipo($ricorrente->getTipo());
        $movimento->setNote($ricorrente->getDescrizione());
        
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
            $fine = new \DateTime('now');
            $fine->add($intervallo);
        }

        $listaPagamenti = [];
        $nextDate = \DateTime::createFromFormat('Y-m-d', $inizio->format('Y-m-d'));
        do {
            $listaPagamenti[] = $nextDate;
            $nextDate = \DateTime::createFromFormat('Y-m-d', $nextDate->format('Y-m-d'));
            $nextDate = $nextDate->add($intervallo);            
        } while ($nextDate->format('Y-m-d') <= $fine->format('Y-m-d'));

        return $listaPagamenti;
    }
}