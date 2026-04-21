<?php

namespace App\Entity;

use App\Repository\ProgettoTipologiaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProgettoTipologiaRepository::class)]
class ProgettoTipologia
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Descrizione = null;

    /**
     * @var Collection<int, Progetto>
     */
    #[ORM\OneToMany(targetEntity: Progetto::class, mappedBy: 'fkProgettoTipologia')]
    private Collection $Progetti;

    public function __construct()
    {
        $this->Progetti = new ArrayCollection();
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
     * @return Collection<int, Progetto>
     */
    public function getProgetti(): Collection
    {
        return $this->Progetti;
    }

    public function addProgetti(Progetto $progetti): static
    {
        if (!$this->Progetti->contains($progetti)) {
            $this->Progetti->add($progetti);
            $progetti->setFkProgettoTipologia($this);
        }

        return $this;
    }

    public function removeProgetti(Progetto $progetti): static
    {
        if ($this->Progetti->removeElement($progetti)) {
            // set the owning side to null (unless already changed)
            if ($progetti->getFkProgettoTipologia() === $this) {
                $progetti->setFkProgettoTipologia(null);
            }
        }

        return $this;
    }

    public function getTotaleOreProgetti(): float
    {
        $totale = 0.0;

        foreach ($this->Progetti as $progetto) {
            $totale += $progetto->getTotaleOreAzioni();
        }

        return round($totale, 2);
    }
}
