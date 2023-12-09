<?php

namespace App\Entity;

use App\Repository\MicroRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\OneToMany(mappedBy: 'Categoria', targetEntity: Movimento::class, orphanRemoval: true)]
    private Collection $movimenti;

    public function __construct()
    {
        $this->movimenti = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Movimento>
     */
    public function getMovimenti(): Collection
    {
        return $this->movimenti;
    }

    public function addMovimenti(Movimento $movimenti): static
    {
        if (!$this->movimenti->contains($movimenti)) {
            $this->movimenti->add($movimenti);
            $movimenti->setCategoria($this);
        }

        return $this;
    }

    public function removeMovimenti(Movimento $movimenti): static
    {
        if ($this->movimenti->removeElement($movimenti)) {
            // set the owning side to null (unless already changed)
            if ($movimenti->getCategoria() === $this) {
                $movimenti->setCategoria(null);
            }
        }

        return $this;
    }
}
