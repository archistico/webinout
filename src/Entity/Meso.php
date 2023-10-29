<?php

namespace App\Entity;

use App\Repository\MesoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MesoRepository::class)]
class Meso
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'mesos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Macro $Padre = null;

    #[ORM\Column(length: 255)]
    private ?string $Nome = null;

    #[ORM\Column]
    private ?bool $Invio = null;

    #[ORM\OneToMany(mappedBy: 'Padre', targetEntity: Micro::class)]
    private Collection $micros;

    public function __construct()
    {
        $this->micros = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPadre(): ?Macro
    {
        return $this->Padre;
    }

    public function setPadre(?Macro $Padre): static
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

    /**
     * @return Collection<int, Micro>
     */
    public function getMicros(): Collection
    {
        return $this->micros;
    }

    public function addMicro(Micro $micro): static
    {
        if (!$this->micros->contains($micro)) {
            $this->micros->add($micro);
            $micro->setPadre($this);
        }

        return $this;
    }

    public function removeMicro(Micro $micro): static
    {
        if ($this->micros->removeElement($micro)) {
            // set the owning side to null (unless already changed)
            if ($micro->getPadre() === $this) {
                $micro->setPadre(null);
            }
        }

        return $this;
    }
}
