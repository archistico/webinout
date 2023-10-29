<?php

namespace App\Entity;

use App\Repository\MacroRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MacroRepository::class)]
class Macro
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Nome = null;

    #[ORM\Column]
    private ?bool $Invio = null;

    #[ORM\OneToMany(mappedBy: 'Padre', targetEntity: Meso::class)]
    private Collection $mesos;

    public function __construct()
    {
        $this->mesos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection<int, Meso>
     */
    public function getMesos(): Collection
    {
        return $this->mesos;
    }

    public function addMeso(Meso $meso): static
    {
        if (!$this->mesos->contains($meso)) {
            $this->mesos->add($meso);
            $meso->setPadre($this);
        }

        return $this;
    }

    public function removeMeso(Meso $meso): static
    {
        if ($this->mesos->removeElement($meso)) {
            // set the owning side to null (unless already changed)
            if ($meso->getPadre() === $this) {
                $meso->setPadre(null);
            }
        }

        return $this;
    }
}
