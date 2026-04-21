<?php

namespace App\Entity;

use App\Repository\ProgettoAzioneRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProgettoAzioneRepository::class)]
class ProgettoAzione
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Descrizione = null;

    #[ORM\ManyToOne(inversedBy: 'Azioni')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Progetto $fkProgetto = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $Inizio = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $Fine = null;

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

    public function getFkProgetto(): ?Progetto
    {
        return $this->fkProgetto;
    }

    public function setFkProgetto(?Progetto $fkProgetto): static
    {
        $this->fkProgetto = $fkProgetto;

        return $this;
    }

    public function getInizio(): ?\DateTimeImmutable
    {
        return $this->Inizio;
    }

    public function setInizio(\DateTimeImmutable $Inizio): static
    {
        $this->Inizio = $Inizio;

        return $this;
    }

    public function getFine(): ?\DateTimeImmutable
    {
        return $this->Fine;
    }

    public function setFine(\DateTimeImmutable $Fine): static
    {
        $this->Fine = $Fine;

        return $this;
    }

    public function getDifferenzaOre(): ?float
    {
        if ($this->Inizio === null || $this->Fine === null) {
            return null;
        }

        $secondi = $this->Fine->getTimestamp() - $this->Inizio->getTimestamp();

        return round($secondi / 3600, 2);
    }
}
