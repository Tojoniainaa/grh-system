<?php

namespace App\Entity;

use App\Repository\CorpseldRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CorpseldRepository::class)]
#[ORM\Table(name: "corpseld")]
class Corpseld
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id_CorpsEld", type: "integer")]
    private ?int $id = null;

    #[ORM\Column(name: "Code_Corps", type: "integer", nullable: true)]
    private ?int $codeCorps = null;

    #[ORM\Column(name: "Rang_Corps", length: 2, nullable: true)]
    private ?string $rangCorps = null;

    #[ORM\Column(name: "Libelle_Corps", length: 33, nullable: true)]
    private ?string $libelleCorps = null;

    #[ORM\Column(name: "Categorie", type: "integer", nullable: true)]
    private ?int $categorie = null;

    // ======================
    // GETTERS & SETTERS
    // ======================

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodeCorps(): ?int
    {
        return $this->codeCorps;
    }

    public function setCodeCorps(?int $codeCorps): self
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
}
