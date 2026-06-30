<?php

namespace App\Entity;

use App\Repository\IndiceeldRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IndiceeldRepository::class)]
#[ORM\Table(name: "indiceeld")]
class Indiceeld
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id_IndiceEld", type: "integer")]
    private ?int $id = null;

    #[ORM\Column(name: "Indice_Ct", length: 9, nullable: true)]
    private ?string $indiceCt = null;

    #[ORM\Column(name: "Categorie", length: 9, nullable: true)]
    private ?string $categorie = null;

    #[ORM\Column(name: "Majoration", length: 10, nullable: true)]
    private ?string $majoration = null;

    #[ORM\Column(name: "Indice", type: "integer", nullable: true)]
    private ?int $indice = null;

    // ======================
    // GETTERS & SETTERS
    // ======================

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIndiceCt(): ?string
    {
        return $this->indiceCt;
    }

    public function setIndiceCt(?string $indiceCt): self
    {
        $this->indiceCt = $indiceCt;
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

    public function getMajoration(): ?string
    {
        return $this->majoration;
    }

    public function setMajoration(?string $majoration): self
    {
        $this->majoration = $majoration;
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
