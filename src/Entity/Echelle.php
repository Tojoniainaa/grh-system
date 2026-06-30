<?php

namespace App\Entity;

use App\Repository\EchelleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EchelleRepository::class)]
#[ORM\Table(name: "echelle")]
class Echelle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id_Echelle", type: "integer")]
    private ?int $id = null;

    #[ORM\Column(name: "Categorie", type: "integer", nullable: true)]
    private ?int $categorie = null;

    #[ORM\Column(name: "Echelle", length: 9, nullable: true)]
    private ?string $echelle = null;

    // ======================
    // GETTERS & SETTERS
    // ======================

    public function getId(): ?int
    {
        return $this->id;
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

    public function getEchelle(): ?string
    {
        return $this->echelle;
    }

    public function setEchelle(?string $echelle): self
    {
        $this->echelle = $echelle;
        return $this;
    }
}
