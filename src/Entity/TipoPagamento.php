<?php

namespace App\Entity;

use App\Repository\TipoPagamentoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TipoPagamentoRepository::class)]
class TipoPagamento
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Descrizione = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescrizione(): ?string
    {
        return $this->Descrizione;
    }

    public function setDescrizione(string $Descrizione): static
    {
        $this->Descrizione = $Descrizione;

        return $this;
    }
}
