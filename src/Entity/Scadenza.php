<?php

namespace App\Entity;

use App\Repository\ScadenzaRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ScadenzaRepository::class)]
class Scadenza
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Attivita = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $DataScadenza = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAttivita(): ?string
    {
        return $this->Attivita;
    }

    public function setAttivita(string $Attivita): static
    {
        $this->Attivita = $Attivita;

        return $this;
    }

    public function getDataScadenza(): ?\DateTimeInterface
    {
        return $this->DataScadenza;
    }

    public function setDataScadenza(\DateTimeInterface $DataScadenza): static
    {
        $this->DataScadenza = $DataScadenza;

        return $this;
    }
}
