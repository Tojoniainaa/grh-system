<?php

namespace App\Entity;

use App\Repository\StatusfonRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StatusfonRepository::class)]
#[ORM\Table(name: "statusfon")]
class Statusfon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id_StatusFon", type: "integer")]
    private ?int $id = null;

    #[ORM\Column(name: "Code_Corps", length: 10, nullable: true)]
    private ?string $codeCorps = null;

    #[ORM\Column(name: "Categorie", length: 9, nullable: true)]
    private ?string $categorie = null;

    #[ORM\Column(name: "Grade", length: 5, nullable: true)]
    private ?string $grade = null;

    #[ORM\Column(name: "Indice", type: "integer", nullable: true)]
    private ?int $indice = null;

    // ======================
    // GETTERS & SETTERS
    // ======================

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

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(?string $categorie): self
    {
        $this->categorie = $categorie;
        return $this;
    }

    public function getGrade(): ?string
    {
        return $this->grade;
    }

    public function setGrade(?string $grade): self
    {
        $this->grade = $grade;
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
