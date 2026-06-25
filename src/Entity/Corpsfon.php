<?php

namespace App\Entity;

use App\Repository\CorpsfonRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CorpsfonRepository::class)]
#[ORM\Table(name: 'corpsfon')]
class Corpsfon
{
    #[ORM\Id]
    #[ORM\Column(length: 4)]
    private ?string $codeCorps = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $libelleCorps = null;

    #[ORM\Column(nullable: true)]
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
        return $this->libelleCorps ?? $this->codeCorps ?? '';
    }
}
