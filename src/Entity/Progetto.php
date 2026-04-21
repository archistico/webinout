<?php

namespace App\Entity;

use App\Repository\ProgettoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProgettoRepository::class)]
class Progetto
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Descrizione = null;

    /**
     * @var Collection<int, ProgettoAzione>
     */
    #[ORM\OneToMany(targetEntity: ProgettoAzione::class, mappedBy: 'fkProgetto')]
    private Collection $Azioni;

    #[ORM\ManyToOne(inversedBy: 'Progetti')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ProgettoTipologia $fkProgettoTipologia = null;

    public function __construct()
    {
        $this->Azioni = new ArrayCollection();
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
     * @return Collection<int, ProgettoAzione>
     */
    public function getAzioni(): Collection
    {
        return $this->Azioni;
    }

    public function addAzioni(ProgettoAzione $azioni): static
    {
        if (!$this->Azioni->contains($azioni)) {
            $this->Azioni->add($azioni);
            $azioni->setFkProgetto($this);
        }

        return $this;
    }

    public function removeAzioni(ProgettoAzione $azioni): static
    {
        if ($this->Azioni->removeElement($azioni)) {
            // set the owning side to null (unless already changed)
            if ($azioni->getFkProgetto() === $this) {
                $azioni->setFkProgetto(null);
            }
        }

        return $this;
    }

    public function getFkProgettoTipologia(): ?ProgettoTipologia
    {
        return $this->fkProgettoTipologia;
    }

    public function setFkProgettoTipologia(?ProgettoTipologia $fkProgettoTipologia): static
    {
        $this->fkProgettoTipologia = $fkProgettoTipologia;

        return $this;
    }

    public function getTotaleOreAzioni(): float
    {
        $totale = 0.0;

        foreach ($this->Azioni as $azione) {
            $totale += $azione->getDifferenzaOre() ?? 0;
        }

        return round($totale, 2);
    }
}
