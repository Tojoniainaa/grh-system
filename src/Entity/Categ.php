<?php

namespace App\Entity;

use App\Repository\CategRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategRepository::class)]
#[ORM\Table(name: 'categ')]
class Categ
{
    #[ORM\Id]
    #[ORM\Column(length: 4)]
    private ?string $codeCategorie = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $libelleCategorie = null;

    public function getCodeCategorie(): ?string
    {
        return $this->codeCategorie;
    }

    public function setCodeCategorie(string $codeCategorie): self
    {
        $this->codeCategorie = $codeCategorie;
        return $this;
    }

    public function getLibelleCategorie(): ?string
    {
        return $this->libelleCategorie;
    }

    public function setLibelleCategorie(?string $libelleCategorie): self
    {
        $this->libelleCategorie = $libelleCategorie;
        return $this;
    }

    public function __toString(): string
    {
        return $this->libelleCategorie ?? $this->codeCategorie ?? '';
    }
}
