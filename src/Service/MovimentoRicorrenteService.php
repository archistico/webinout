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
}