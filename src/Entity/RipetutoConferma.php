<?php

namespace App\Entity;

use App\Repository\RipetutoConfermaRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RipetutoConfermaRepository::class)]
class RipetutoConferma
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'ripetutoConferme')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Ripetuto $RipetutoId = null;

    #[ORM\ManyToOne]
    private ?Movimento $MovimentoId = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $Data = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRipetutoId(): ?Ripetuto
    {
        return $this->RipetutoId;
    }

    public function setRipetutoId(?Ripetuto $RipetutoId): static
    {
        $this->RipetutoId = $RipetutoId;

        return $this;
    }

    public function getMovimentoId(): ?Movimento
    {
        return $this->MovimentoId;
    }

    public function setMovimentoId(?Movimento $MovimentoId): static
    {
        $this->MovimentoId = $MovimentoId;

        return $this;
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
}
