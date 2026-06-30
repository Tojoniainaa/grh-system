<?php

namespace App\Entity;

use App\Repository\BudgetRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BudgetRepository::class)]
#[ORM\Table(name: "budget")]
class Budget
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "Id_Budget", type: "integer")]
    private ?int $id = null;

    #[ORM\Column(name: "Code_Budget", length: 8, nullable: true)]
    private ?string $codeBudget = null;

    #[ORM\Column(name: "Libelle_Budget", length: 10, nullable: true)]
    private ?string $libelleBudget = null;

    // ======================
    // GETTERS & SETTERS
    // ======================

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodeBudget(): ?string
    {
        return $this->codeBudget;
    }

    public function setCodeBudget(?string $codeBudget): self
    {
        $this->codeBudget = $codeBudget;
        return $this;
    }

    public function getLibelleBudget(): ?string
    {
        return $this->libelleBudget;
    }

    public function setLibelleBudget(?string $libelleBudget): self
    {
        $this->libelleBudget = $libelleBudget;
        return $this;
    }
}
