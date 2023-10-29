<?php

namespace App\Entity;

use App\Repository\MicroRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MicroRepository::class)]
class Micro
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'micros')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Meso $Padre = null;

    #[ORM\Column(length: 255)]
    private ?string $Nome = null;

    #[ORM\Column]
    private ?bool $Invio = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPadre(): ?Meso
    {
        return $this->Padre;
    }

    public function setPadre(?Meso $Padre): static
    {
        $this->Padre = $Padre;

        return $this;
    }

    public function getNome(): ?string
    {
        return $this->Nome;
    }

    public function setNome(string $Nome): static
    {
        $this->Nome = $Nome;

        return $this;
    }

    public function isInvio(): ?bool
    {
        return $this->Invio;
    }

    public function setInvio(bool $Invio): static
    {
        $this->Invio = $Invio;

        return $this;
    }
}
