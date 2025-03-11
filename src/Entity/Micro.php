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

    #[ORM\OneToMany(mappedBy: 'Categoria', targetEntity: Ripetuto::class)]
    private Collection $ripetuti;

    #[ORM\OneToMany(mappedBy: 'Categoria', targetEntity: MovimentoRicorrente::class)]
    private Collection $MovimentiRicorrenti;

    public function __construct()
    {
        $this->movimenti = new ArrayCollection();
        $this->ripetuti = new ArrayCollection();
        $this->MovimentiRicorrenti = new ArrayCollection();
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

    /**
     * @return Collection<int, Ripetuto>
     */
    public function getRipetuti(): Collection
    {
        return $this->ripetuti;
    }

    public function addRipetuti(Ripetuto $ripetuti): static
    {
        if (!$this->ripetuti->contains($ripetuti)) {
            $this->ripetuti->add($ripetuti);
            $ripetuti->setCategoria($this);
        }

        return $this;
    }

    public function removeRipetuti(Ripetuto $ripetuti): static
    {
        if ($this->ripetuti->removeElement($ripetuti)) {
            // set the owning side to null (unless already changed)
            if ($ripetuti->getCategoria() === $this) {
                $ripetuti->setCategoria(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, MovimentoRicorrente>
     */
    public function getMovimentiRicorrenti(): Collection
    {
        return $this->MovimentiRicorrenti;
    }

    public function addMovimentiRicorrenti(MovimentoRicorrente $movimentiRicorrenti): static
    {
        if (!$this->MovimentiRicorrenti->contains($movimentiRicorrenti)) {
            $this->MovimentiRicorrenti->add($movimentiRicorrenti);
            $movimentiRicorrenti->setCategoria($this);
        }

        return $this;
    }

    public function removeMovimentiRicorrenti(MovimentoRicorrente $movimentiRicorrenti): static
    {
        if ($this->MovimentiRicorrenti->removeElement($movimentiRicorrenti)) {
            // set the owning side to null (unless already changed)
            if ($movimentiRicorrenti->getCategoria() === $this) {
                $movimentiRicorrenti->setCategoria(null);
            }
        }

        return $this;
    }
}
