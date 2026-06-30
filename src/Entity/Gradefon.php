<?php

namespace App\Entity;

use App\Repository\GradefonRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GradefonRepository::class)]
#[ORM\Table(name: "gradefon")]
class Gradefon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "Id_Grade", type: "integer")]
    private ?int $id = null;

    #[ORM\Column(name: "Code_Grade", length: 4, nullable: true)]
    private ?string $codeGrade = null;

    #[ORM\Column(name: "Libelle_Grade", length: 50, nullable: true)]
    private ?string $libelleGrade = null;

    // ======================
    // GETTERS & SETTERS
    // ======================

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodeGrade(): ?string
    {
        return $this->codeGrade;
    }

    public function setCodeGrade(?string $codeGrade): self
    {
        $this->codeGrade = $codeGrade;
        return $this;
    }

    public function getLibelleGrade(): ?string
    {
        return $this->libelleGrade;
    }

    public function setLibelleGrade(?string $libelleGrade): self
    {
        $this->libelleGrade = $libelleGrade;
        return $this;
    }
}
