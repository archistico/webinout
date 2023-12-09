<?php

namespace App\Entity;

use App\Repository\MovimentoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MovimentoRepository::class)]
class Movimento
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $Data = null;

    #[ORM\ManyToOne(inversedBy: 'movimenti')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Micro $Categoria = null;

    #[ORM\Column(nullable: true)]
    private ?float $Importo = null;

    #[ORM\ManyToOne(inversedBy: 'movimenti')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TipoPagamento $Tipo = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $Note = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getData(): ?\DateTimeInterface
    {
        return $this->Data;
    }

    public function setData(\DateTimeInterface $Data): static
    {
        $this->Data = $Data;

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

    public function getImporto(): ?float
    {
        return $this->Importo;
    }

    public function setImporto(?float $Importo): static
    {
        $this->Importo = $Importo;

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

    public function getNote(): ?string
    {
        return $this->Note;
    }

    public function setNote(?string $Note): static
    {
        $this->Note = $Note;

        return $this;
    }
}
