<?php

namespace App\Entity;

use App\Repository\Corpsefa4Repository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: Corpsefa4Repository::class)]
#[ORM\Table(name: "corpsefa4")]
class Corpsefa4
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id_CorpEfa", type: "integer")]
    private ?int $id = null;

    #[ORM\Column(name: "Code_Corps", length: 4, nullable: true)]
    private ?string $codeCorps = null;

    #[ORM\Column(name: "Libelle_Corps", length: 50, nullable: true)]
    private ?string $libelleCorps = null;

    #[ORM\Column(name: "Categorie", type: "integer", nullable: true)]
    private ?int $categorie = null;

    // GETTERS / SETTERS

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodeCorps(): ?string
    {
        return $this->codeCorps;
    }

    public function setCodeCorps(?string $codeCorps): self
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
}
