<?php

namespace App\Entity;

use App\Repository\AgentsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AgentsRepository::class)]
class Agents
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 50)]
    #[Assert\NotBlank()]
    private string $prenom;

    #[ORM\Column(type: 'string', length: 50)]
    #[Assert\NotBlank()]
    private string $matricule;

    #[ORM\Column(type: 'string', length: 50)]
    #[Assert\NotBlank()]
    private string $nom;

    #[ORM\Column(type: 'string', length: 1)]
    #[Assert\Choice(['M', 'F', 'Autre'])]
    private string $sexe;

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $dateNaissance = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $lieuNaissance = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $adresse = null;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    private ?string $pere = null;

    #[ORM\Column(type: 'string', length: 3, nullable: true)]
    private ?string $pereDecede = null; // oui/non

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    private ?string $mere = null;

    #[ORM\Column(type: 'string', length: 3, nullable: true)]
    private ?string $mereDecede = null; // oui/non

    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    private ?string $cin = null;

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $dateCin = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $lieuDelivranceCin = null;

    #[ORM\Column(type: 'string', length: 10, nullable: true)]
    private ?string $contact = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $nomPhotos = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $nomPdf = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrenom(): string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;
        return $this;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }
    public function getMatricule(): string
    {
        return $this->matricule;
    }

    public function setMatricule(string $matricule): self
    {
        $this->matricule = $matricule;
        return $this;
    }
    public function getSexe(): string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): self
    {
        $this->sexe = $sexe;
        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(?\DateTimeInterface $dateNaissance): self
    {
        $this->dateNaissance = $dateNaissance;
        return $this;
    }

    public function getLieuNaissance(): ?string
    {
        return $this->lieuNaissance;
    }

    public function setLieuNaissance(?string $lieuNaissance): self
    {
        $this->lieuNaissance = $lieuNaissance;
        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;
        return $this;
    }

    public function getPere(): ?string
    {
        return $this->pere;
    }

    public function setPere(?string $pere): self
    {
        $this->pere = $pere;
        return $this;
    }

    public function getPereDecede(): ?string
    {
        return $this->pereDecede;
    }

    public function setPereDecede(?string $pereDecede): self
    {
        $this->pereDecede = $pereDecede;
        return $this;
    }

    public function getMere(): ?string
    {
        return $this->mere;
    }

    public function setMere(?string $mere): self
    {
        $this->mere = $mere;
        return $this;
    }

    public function getMereDecede(): ?string
    {
        return $this->mereDecede;
    }

    public function setMereDecede(?string $mereDecede): self
    {
        $this->mereDecede = $mereDecede;
        return $this;
    }

    public function getCin(): ?string
    {
        return $this->cin;
    }

    public function setCin(?string $cin): self
    {
        $this->cin = $cin;
        return $this;
    }

    public function getDateCin(): ?\DateTimeInterface
    {
        return $this->dateCin;
    }

    public function setDateCin(?\DateTimeInterface $dateCin): self
    {
        $this->dateCin = $dateCin;
        return $this;
    }

    public function getLieuDelivranceCin(): ?string
    {
        return $this->lieuDelivranceCin;
    }

    public function setLieuDelivranceCin(?string $lieuDelivranceCin): self
    {
        $this->lieuDelivranceCin = $lieuDelivranceCin;
        return $this;
    }

    public function getContact(): ?string
    {
        return $this->contact;
    }

    public function setContact(?string $contact): self
    {
        $this->contact = $contact;
        return $this;
    }

    public function getNomPhotos(): ?string
    {
        return $this->nomPhotos;
    }

    public function setNomPhotos(?string $nomPhotos): self
    {
        $this->nomPhotos = $nomPhotos;
        return $this;
    }

    public function getNomPdf(): ?string
    {
        return $this->nomPdf;
    }

    public function setNomPdf(?string $nomPdf): self
    {
        $this->nomPdf = $nomPdf;
        return $this;
    }
}
