<?php
// src/Entity/AuditSituationAdm.php

namespace App\Entity;

use App\Repository\AuditSituationAdmRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AuditSituationAdmRepository::class)]
#[ORM\Table(name: 'audit_situationadminstra')]
class AuditSituationAdm
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 12)]
    private ?string $matricule = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $nouvelleCodeGrade = null;

    #[ORM\Column(nullable: true)]
    private ?int $nouvelleIndice = null;

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $nouvelleDateEffet = null;

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $nouvelleDateNotif = null;

    #[ORM\Column(length: 250, nullable: true)]
    private ?string $nouvelleRefNotification = null;

    #[ORM\Column(length: 15, nullable: true)]
    private ?string $nouvelleStatu = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $nouvelleCodeCorps = null;

    #[ORM\Column(nullable: true)]
    private ?int $nouvelleCategorie = null;

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $nouvelleDateTexte = null;
    #[ORM\Column(length: 30, nullable: true)]
    private ?string $nouvelleEchelle = null;
    #[ORM\Column(length: 30, nullable: true)]
    private ?string $nouvelleEchelon = null;

    #[ORM\Column(nullable: true)]
    private ?int $nouvelleIndiceCt = null;

    #[ORM\Column(nullable: true)]
    private ?int $nouvelleMajoration = null;

    // Optionnel mais très utile
    #[ORM\Column(type: 'datetime_immutable')]
    private ?\DateTimeImmutable $dateHistorisation = null;

    #[ORM\Column(length: 20)]
    private ?string $typeOperation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    public function setMatricule(string $matricule): static
    {
        $this->matricule = $matricule;

        return $this;
    }

    public function getNouvelleCodeGrade(): ?string
    {
        return $this->nouvelleCodeGrade;
    }

    public function setNouvelleCodeGrade(?string $nouvelleCodeGrade): static
    {
        $this->nouvelleCodeGrade = $nouvelleCodeGrade;

        return $this;
    }

    public function getNouvelleIndice(): ?int
    {
        return $this->nouvelleIndice;
    }

    public function setNouvelleIndice(?int $nouvelleIndice): static
    {
        $this->nouvelleIndice = $nouvelleIndice;

        return $this;
    }

    public function getNouvelleDateEffet(): ?\DateTimeInterface
    {
        return $this->nouvelleDateEffet;
    }

    public function setNouvelleDateEffet(?\DateTimeInterface $nouvelleDateEffet): static
    {
        $this->nouvelleDateEffet = $nouvelleDateEffet;

        return $this;
    }

    public function getNouvelleDateNotif(): ?\DateTimeInterface
    {
        return $this->nouvelleDateNotif;
    }

    public function setNouvelleDateNotif(?\DateTimeInterface $nouvelleDateNotif): static
    {
        $this->nouvelleDateNotif = $nouvelleDateNotif;

        return $this;
    }

    public function getNouvelleRefNotification(): ?string
    {
        return $this->nouvelleRefNotification;
    }

    public function setNouvelleRefNotification(?string $nouvelleRefNotification): static
    {
        $this->nouvelleRefNotification = $nouvelleRefNotification;

        return $this;
    }

    public function getNouvelleStatu(): ?string
    {
        return $this->nouvelleStatu;
    }

    public function setNouvelleStatu(?string $nouvelleStatu): static
    {
        $this->nouvelleStatu = $nouvelleStatu;

        return $this;
    }

    public function getNouvelleCodeCorps(): ?string
    {
        return $this->nouvelleCodeCorps;
    }

    public function setNouvelleCodeCorps(?string $nouvelleCodeCorps): static
    {
        $this->nouvelleCodeCorps = $nouvelleCodeCorps;

        return $this;
    }

    public function getNouvelleCategorie(): ?int
    {
        return $this->nouvelleCategorie;
    }

    public function setNouvelleCategorie(?int $nouvelleCategorie): static
    {
        $this->nouvelleCategorie = $nouvelleCategorie;

        return $this;
    }

    public function getNouvelleDateTexte(): ?\DateTimeInterface
    {
        return $this->nouvelleDateTexte;
    }

    public function setNouvelleDateTexte(?\DateTimeInterface $nouvelleDateTexte): static
    {
        $this->nouvelleDateTexte = $nouvelleDateTexte;

        return $this;
    }

    public function getNouvelleEchelle(): ?string
    {
        return $this->nouvelleEchelle;
    }

    public function setNouvelleEchelle(?string $nouvelleEchelle): static
    {
        $this->nouvelleEchelle = $nouvelleEchelle;

        return $this;
    }

    public function getNouvelleEchelon(): ?string
    {
        return $this->nouvelleEchelon;
    }

    public function setNouvelleEchelon(?string $nouvelleEchelon): static
    {
        $this->nouvelleEchelon = $nouvelleEchelon;

        return $this;
    }

    public function getNouvelleIndiceCt(): ?int
    {
        return $this->nouvelleIndiceCt;
    }

    public function setNouvelleIndiceCt(?int $nouvelleIndiceCt): static
    {
        $this->nouvelleIndiceCt = $nouvelleIndiceCt;

        return $this;
    }

    public function getNouvelleMajoration(): ?int
    {
        return $this->nouvelleMajoration;
    }

    public function setNouvelleMajoration(?int $nouvelleMajoration): static
    {
        $this->nouvelleMajoration = $nouvelleMajoration;

        return $this;
    }

    public function getDateHistorisation(): ?\DateTimeImmutable
    {
        return $this->dateHistorisation;
    }

    public function setDateHistorisation(\DateTimeImmutable $dateHistorisation): static
    {
        $this->dateHistorisation = $dateHistorisation;

        return $this;
    }

    public function getTypeOperation(): ?string
    {
        return $this->typeOperation;
    }

    public function setTypeOperation(string $typeOperation): static
    {
        $this->typeOperation = $typeOperation;

        return $this;
    }

}
