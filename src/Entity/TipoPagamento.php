<?php

namespace App\Entity;

use App\Repository\TipoPagamentoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TipoPagamentoRepository::class)]
class TipoPagamento
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Descrizione = null;

    #[ORM\OneToMany(mappedBy: 'Tipo', targetEntity: Movimento::class, orphanRemoval: true)]
    private Collection $movimenti;

    public function __construct()
    {
        $this->movimenti = new ArrayCollection();
    }

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
            $movimenti->setTipo($this);
        }

        return $this;
    }

    public function removeMovimenti(Movimento $movimenti): static
    {
        if ($this->movimenti->removeElement($movimenti)) {
            // set the owning side to null (unless already changed)
            if ($movimenti->getTipo() === $this) {
                $movimenti->setTipo(null);
            }
        }

        return $this;
    }
}
