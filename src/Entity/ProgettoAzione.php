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

    public function getDifferenzaOreAnnoCorrente(): float
    {
        if ($this->Inizio === null || $this->Fine === null) {
            return 0.0;
        }

        $adesso = new \DateTimeImmutable();
        $inizioAnno = new \DateTimeImmutable($adesso->format('Y-01-01 00:00:00'));
        $fineAnno = new \DateTimeImmutable($adesso->format('Y-12-31 23:59:59'));

        $inizio = $this->Inizio > $inizioAnno ? $this->Inizio : $inizioAnno;
        $fine = $this->Fine < $fineAnno ? $this->Fine : $fineAnno;

        if ($fine <= $inizio) {
            return 0.0;
        }

        $secondi = $fine->getTimestamp() - $inizio->getTimestamp();

        return round($secondi / 3600, 2);
    }
}
