<?php

namespace App\Entity;

use App\Repository\RipetutoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RipetutoRepository::class)]
class Ripetuto
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'ripetuti')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Micro $Categoria = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?TipoPagamento $Tipo = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $Inizio = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $Fine = null;

    #[ORM\Column(length: 255)]
    private ?string $Rinnovo = null;

    #[ORM\Column(nullable: true)]
    private ?int $Giorno = null;

    #[ORM\Column(nullable: true)]
    private ?int $Mese = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Note = null;

    #[ORM\OneToMany(mappedBy: 'RipetutoId', targetEntity: RipetutoConferma::class)]
    private Collection $ripetutoConferme;

    public function __construct()
    {
        $this->ripetutoConferme = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategoria(): ?Micro
    {
        return $this->Categoria;
    }

    public function setCategoria(?Micro $Categoria): static
    {
        $this->Categoria = $Categoria;

        return $this;
    }

    public function getTipo(): ?TipoPagamento
    {
        return $this->Tipo;
    }

    public function setTipo(?TipoPagamento $Tipo): static
    {
        $this->Tipo = $Tipo;

        return $this;
    }

    public function getInizio(): ?\DateTimeInterface
    {
        return $this->Inizio;
    }

    public function setInizio(\DateTimeInterface $Inizio): static
    {
        $this->Inizio = $Inizio;

        return $this;
    }

    public function getFine(): ?\DateTimeInterface
    {
        return $this->Fine;
    }

    public function setFine(\DateTimeInterface $Fine): static
    {
        $this->Fine = $Fine;

        return $this;
    }

    public function getRinnovo(): ?string
    {
        return $this->Rinnovo;
    }

    public function setRinnovo(string $Rinnovo): static
    {
        $this->Rinnovo = $Rinnovo;

        return $this;
    }

    public function getGiorno(): ?int
    {
        return $this->Giorno;
    }

    public function setGiorno(?int $Giorno): static
    {
        $this->Giorno = $Giorno;

        return $this;
    }

    public function getMese(): ?int
    {
        return $this->Mese;
    }

    public function setMese(?int $Mese): static
    {
        $this->Mese = $Mese;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->Note;
    }

    public function setNote(?string $Note): static
    {
        $this->Note = $Note;

        return $this;
    }

    /**
     * @return Collection<int, RipetutoConferma>
     */
    public function getRipetutoConferme(): Collection
    {
        return $this->ripetutoConferme;
    }

    public function addRipetutoConferme(RipetutoConferma $ripetutoConferme): static
    {
        if (!$this->ripetutoConferme->contains($ripetutoConferme)) {
            $this->ripetutoConferme->add($ripetutoConferme);
            $ripetutoConferme->setRipetutoId($this);
        }

        return $this;
    }

    public function removeRipetutoConferme(RipetutoConferma $ripetutoConferme): static
    {
        if ($this->ripetutoConferme->removeElement($ripetutoConferme)) {
            // set the owning side to null (unless already changed)
            if ($ripetutoConferme->getRipetutoId() === $this) {
                $ripetutoConferme->setRipetutoId(null);
            }
        }

        return $this;
    }
}
