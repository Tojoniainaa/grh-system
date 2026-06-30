<?php

namespace App\Entity;

use App\Repository\IndiceefaRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IndiceefaRepository::class)]
#[ORM\Table(name: "indiceefa")]
class Indiceefa
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id_IndiceEfa", type: "integer")]
    private ?int $id = null;

    #[ORM\Column(name: "Echelle", length: 9, nullable: true)]
    private ?string $echelle = null;

    #[ORM\Column(name: "Echelon", length: 8, nullable: true)]
    private ?string $echelon = null;

    #[ORM\Column(name: "Categorie", length: 10, nullable: true)]
    private ?string $categorie = null;

    #[ORM\Column(name: "Indice", type: "integer", nullable: true)]
    private ?int $indice = null;

    // ======================
    // GETTERS & SETTERS
    // ======================

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEchelle(): ?string
    {
        return $this->echelle;
    }

    public function setEchelle(?string $echelle): self
    {
        $this->echelle = $echelle;
        return $this;
    }

    public function getEchelon(): ?string
    {
        return $this->echelon;
    }

    public function setEchelon(?string $echelon): self
    {
        $this->echelon = $echelon;
        return $this;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(?string $categorie): self
    {
        $this->categorie = $categorie;
        return $this;
    }

    public function getIndice(): ?int
    {
        return $this->indice;
    }

    public function setIndice(?int $indice): self
    {
        $this->indice = $indice;
        return $this;
    }
}
