<?php

namespace App\Entity;

use App\Repository\MovimentoRicorrenteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MovimentoRicorrenteRepository::class)]
class MovimentoRicorrente
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Descrizione = null;

    #[ORM\Column]
    private ?float $Importo = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $Inizio = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $Fine = null;

    #[ORM\Column(length: 255)]
    private ?string $Frequenza = null;

    #[ORM\Column]
    private ?bool $Attivo = null;

    #[ORM\ManyToOne(inversedBy: 'MovimentiRicorrenti')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Micro $Categoria = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?TipoPagamento $Tipo = null;

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

    public function getImporto(): ?float
    {
        return $this->Importo;
    }

    public function setImporto(float $Importo): static
    {
        $this->Importo = $Importo;

        return $this;
    }

    public function getInizio(): ?\DateTimeInterface
    {
        return $this->Inizio;
    }

    public function setInizio(\DateTimeInterface $Inizio): static
    {
        $this->Inizio = $Inizio;

        return $this;
    }

    public function getFine(): ?\DateTimeInterface
    {
        return $this->Fine;
    }

    public function setFine(?\DateTimeInterface $fine): static
    {
        $this->Fine = $fine;

        return $this;
    }

    public function getFrequenza(): ?string
    {
        return $this->Frequenza;
    }

    public function setFrequenza(string $Frequenza): static
    {
        $this->Frequenza = $Frequenza;

        return $this;
    }

    public function isAttivo(): ?bool
    {
        return $this->Attivo;
    }

    public function setAttivo(bool $Attivo): static
    {
        $this->Attivo = $Attivo;

        return $this;
    }

    public function getCategoria(): ?Micro
    {
        return $this->Categoria;
    }

    public function setCategoria(?Micro $Categoria): static
    {
        $this->Categoria = $Categoria;

        return $this;
    }

    public function getTipo(): ?TipoPagamento
    {
        return $this->Tipo;
    }

    public function setTipo(?TipoPagamento $Tipo): static
    {
        $this->Tipo = $Tipo;

        return $this;
    }
}
