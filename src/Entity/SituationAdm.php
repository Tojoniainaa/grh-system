<?php

namespace App\Entity;

use App\Repository\SituationAdmRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SituationAdmRepository::class)]
#[ORM\Table(name: 'situation_adm')]
class SituationAdm
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(length: 12, nullable: false)]
    #[Assert\NotBlank]
    private ?string $matricule = null;

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $dateEffet = null;

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $dateEntre = null;

    #[ORM\Column(length: 15, nullable: true)]
    private ?string $statu = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $codeGrade = null;
    #[ORM\Column(name: 'Code_Corps', length: 10, nullable: true)]
    private ?string $codeCorps = null;

    #[ORM\Column(nullable: true)]
    private ?int $categorie = null;

    #[ORM\Column(nullable: true)]
    private ?int $indice = null;

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $dateNotif = null;

    #[ORM\Column(length: 250, nullable: true)]
    private ?string $refNotification = null;

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $dateTexte = null;

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $datePriseServ = null;

    #[ORM\Column(length: 45, nullable: true)]
    private ?string $imputationBudgetaire = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $typeContrat = null;

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $dateDebContrat = null;

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $dateFinContrat = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $modePaie = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $codePaie = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $codeBanque = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $numeroCompte = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $echelle = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $echelon = null;

    #[ORM\Column(nullable: true)]
    private ?int $indiceCt = null;

    #[ORM\Column(nullable: true)]
    private ?int $majoration = null;
    #[ORM\ManyToOne(targetEntity: Services::class)]
    #[ORM\JoinColumn(name: 'service_id', referencedColumnName: 'Id', nullable: true)]
    private ?Services $service = null;


    // ==================== GETTERS & SETTERS ====================

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    public function setMatricule(string $matricule): self
    {
        $this->matricule = $matricule;
        return $this;
    }

    public function getDateEffet(): ?\DateTimeInterface
    {
        return $this->dateEffet;
    }

    public function setDateEffet(?\DateTimeInterface $dateEffet): self
    {
        $this->dateEffet = $dateEffet;

        return $this;
    }

    public function getDateEntre(): ?\DateTimeInterface
    {
        return $this->dateEntre;
    }

    public function setDateEntre(?\DateTimeInterface $dateEntre): self
    {
        $this->dateEntre = $dateEntre;
        return $this;
    }

    public function getStatu(): ?string
    {
        return $this->statu;
    }

    public function setStatu(string $statu): self
    {
        $this->statu = $statu;
        return $this;
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

    public function getCodeCorps(): ?string
    {
        return $this->codeCorps;
    }

    public function setCodeCorps(?string $codeCorps): self
    {
        $this->codeCorps = $codeCorps;
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

    public function getIndice(): ?int
    {
        return $this->indice;
    }

    public function setIndice(?int $indice): self
    {
        $this->indice = $indice;
        return $this;
    }

    // ... (les autres getters/setters suivent le même modèle)

    public function getDateNotif(): ?\DateTimeInterface
    {
        return $this->dateNotif;
    }

    public function setDateNotif(?\DateTimeInterface $dateNotif): self
    {
        $this->dateNotif = $dateNotif;
        return $this;
    }

    public function getRefNotification(): ?string
    {
        return $this->refNotification;
    }

    public function setRefNotification(?string $refNotification): self
    {
        $this->refNotification = $refNotification;
        return $this;
    }
    public function getDateTexte(): ?\DateTimeInterface
    {
        return $this->dateTexte;
    }

    public function setDateTexte(?\DateTimeInterface $dateTexte): self
    {
        $this->dateTexte = $dateTexte;
        return $this;
    }

    public function getDatePriseServ(): ?\DateTimeInterface
    {
        return $this->datePriseServ;
    }

    public function setDatePriseServ(?\DateTimeInterface $datePriseServ): self
    {
        $this->datePriseServ = $datePriseServ;
        return $this;
    }

    public function getImputationBudgetaire(): ?string
    {
        return $this->imputationBudgetaire;
    }

    public function setImputationBudgetaire(?string $imputationBudgetaire): self
    {
        $this->imputationBudgetaire = $imputationBudgetaire;
        return $this;
    }

    public function getTypeContrat(): ?string
    {
        return $this->typeContrat;
    }

    public function setTypeContrat(?string $typeContrat): self
    {
        $this->typeContrat = $typeContrat;
        return $this;
    }

    public function getDateDebContrat(): ?\DateTimeInterface
    {
        return $this->dateDebContrat;
    }

    public function setDateDebContrat(?\DateTimeInterface $dateDebContrat): self
    {
        $this->dateDebContrat = $dateDebContrat;
        return $this;
    }

    public function getDateFinContrat(): ?\DateTimeInterface
    {
        return $this->dateFinContrat;
    }

    public function setDateFinContrat(?\DateTimeInterface $dateFinContrat): self
    {
        $this->dateFinContrat = $dateFinContrat;
        return $this;
    }

    public function getModePaie(): ?string
    {
        return $this->modePaie;
    }

    public function setModePaie(?string $modePaie): self
    {
        $this->modePaie = $modePaie;
        return $this;
    }

    public function getCodePaie(): ?string
    {
        return $this->codePaie;
    }

    public function setCodePaie(?string $codePaie): self
    {
        $this->codePaie = $codePaie;
        return $this;
    }

    public function getCodeBanque(): ?string
    {
        return $this->codeBanque;
    }

    public function setCodeBanque(?string $codeBanque): self
    {
        $this->codeBanque = $codeBanque;
        return $this;
    }

    public function getNumeroCompte(): ?string
    {
        return $this->numeroCompte;
    }

    public function setNumeroCompte(?string $numeroCompte): self
    {
        $this->numeroCompte = $numeroCompte;
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

    public function getEchelon(): ?string
    {
        return $this->echelon;
    }

    public function setEchelon(?string $echelon): self
    {
        $this->echelon = $echelon;
        return $this;
    }

    public function getIndiceCt(): ?int
    {
        return $this->indiceCt;
    }

    public function setIndiceCt(?int $indiceCt): self
    {
        $this->indiceCt = $indiceCt;
        return $this;
    }

    public function getMajoration(): ?int
    {
        return $this->majoration;
    }

    public function setMajoration(?int $majoration): self
    {
        $this->majoration = $majoration;
        return $this;
    }
    public function getService(): ?Services
    {
        return $this->service;
    }

    public function setService(?Services $service): static
    {
        $this->service = $service;

        return $this;
    }
}
