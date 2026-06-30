<?php

namespace App\Entity;

use App\Repository\CorpheeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CorpheeRepository::class)]
#[ORM\Table(name: "corphee")]
class Corphee
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id_CorpHee", type: "integer")]
    private ?int $id = null;

    #[ORM\Column(name: "Code_Corps", length: 4, nullable: true)]
    private ?string $codeCorps = null;

    #[ORM\Column(name: "Rang_Corps", length: 2, nullable: true)]
    private ?string $rangCorps = null;

    #[ORM\Column(name: "Libelle_Corps", length: 22, nullable: true)]
    private ?string $libelleCorps = null;

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
}
