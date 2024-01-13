<?php

namespace App\Entity;

use App\Repository\EseguitoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EseguitoRepository::class)]
class Eseguito
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Attivita = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $DataScadenza = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $DataEseguito = null;

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

    public function getDataEseguito(): ?\DateTimeInterface
    {
        return $this->DataEseguito;
    }

    public function setDataEseguito(\DateTimeInterface $DataEseguito): static
    {
        $this->DataEseguito = $DataEseguito;

        return $this;
    }
}
