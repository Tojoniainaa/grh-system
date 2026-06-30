<?php

namespace App\Entity;

use App\Repository\CorpsefaRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CorpsefaRepository::class)]
#[ORM\Table(name: 'corpsefa')]
class Corpsefa
{
    #[ORM\Id]
    #[ORM\Column(name: 'Code_Corps', length: 4)]
    private ?string $codeCorps = null;

    #[ORM\Column(name: 'Rang_Corps', length: 2, nullable: true)]
    private ?string $rangCorps = null;

    #[ORM\Column(name: 'Libelle_Corps', length: 36, nullable: true)]
    private ?string $libelleCorps = null;

    #[ORM\Column(name: 'Categorie', nullable: true)]
    private ?int $categorie = null;

    public function getCodeCorps(): ?string
    {
        return $this->codeCorps;
    }

    public function setCodeCorps(string $codeCorps): self
    {
        $this->codeCorps = $codeCorps;
        return $this;
    }

    public function getRangCorps(): ?string
    {
        return $this->rangCorps;
    }

    public function setRangCorps(?string $rangCorps): self
    {
        $this->rangCorps = $rangCorps;
        return $this;
    }

    public function getLibelleCorps(): ?string
    {
        return $this->libelleCorps;
    }

    public function setLibelleCorps(?string $libelleCorps): self
    {
        $this->libelleCorps = $libelleCorps;
        return $this;
    }

    public function getCategorie(): ?int
    {
        return $this->categorie;
    }

    public function setCategorie(?int $categorie): self
    {
        $this->categorie = $categorie;
        return $this;
    }

    public function __toString(): string
    {
        return $this->libelleCorps ?? '';
    }
}
