<?php

namespace App\Entity;

use App\Repository\AllegatoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AllegatoRepository::class)]
class Allegato
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'allegati')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Movimento $Movimento = null;

    #[ORM\Column(length: 255)]
    private ?string $Nomefile = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMovimento(): ?Movimento
    {
        return $this->Movimento;
    }

    public function setMovimento(?Movimento $Movimento): static
    {
        $this->Movimento = $Movimento;

        return $this;
    }

    public function getNomefile(): ?string
    {
        return $this->Nomefile;
    }

    public function setNomefile(string $Nomefile): static
    {
        $this->Nomefile = $Nomefile;

        return $this;
    }
}
