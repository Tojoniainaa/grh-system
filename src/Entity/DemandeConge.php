<?php

namespace App\Entity;

use App\Repository\DemandeCongeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DemandeCongeRepository::class)]
#[ORM\Table(name: 'demande_conge')]
class DemandeConge
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'Num_EnregistrConge')]
    private ?int $id = null;

    #[ORM\Column(name: 'Matricule', type: 'integer')]
    #[Assert\NotBlank]
    private ?int $matricule = null;

    #[ORM\Column(name: 'Date_Demande_Con', type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank]
    private ?\DateTimeInterface $dateDemande = null;

    #[ORM\Column(name: 'Dure', type: 'integer')]
    #[Assert\NotBlank]
    #[Assert\Positive]
    private ?int $duree = null;

    #[ORM\Column(name: 'Date_DepartCon', type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank]
    private ?\DateTimeInterface $dateDepart = null;

    #[ORM\Column(name: 'Date_RetourCon', type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank]
    private ?\DateTimeInterface $dateRetour = null;

    #[ORM\Column(name: 'Lieu_jouissance', length: 250)]
    #[Assert\NotBlank]
    private ?string $lieuJouissance = null;

    #[ORM\Column(name: 'Motifs', length: 250)]
    #[Assert\NotBlank]
    private ?string $motifs = null;

    #[ORM\Column(name: 'NbjoursReste', type: 'integer')]
    private ?int $nbJoursReste = null;

    // ==================== GETTERS / SETTERS ====================

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMatricule(): ?int
    {
        return $this->matricule;
    }

    public function setMatricule(int $matricule): self
    {
        $this->matricule = $matricule;
        return $this;
    }

    public function getDateDemande(): ?\DateTimeInterface
    {
        return $this->dateDemande;
    }

    public function setDateDemande(\DateTimeInterface $dateDemande): self
    {
        $this->dateDemande = $dateDemande;
        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(int $duree): self
    {
        $this->duree = $duree;
        return $this;
    }

    public function getDateDepart(): ?\DateTimeInterface
    {
        return $this->dateDepart;
    }

    public function setDateDepart(\DateTimeInterface $dateDepart): self
    {
        $this->dateDepart = $dateDepart;
        return $this;
    }

    public function getDateRetour(): ?\DateTimeInterface
    {
        return $this->dateRetour;
    }

    public function setDateRetour(\DateTimeInterface $dateRetour): self
    {
        $this->dateRetour = $dateRetour;
        return $this;
    }

    public function getLieuJouissance(): ?string
    {
        return $this->lieuJouissance;
    }

    public function setLieuJouissance(string $lieuJouissance): self
    {
        $this->lieuJouissance = $lieuJouissance;
        return $this;
    }

    public function getMotifs(): ?string
    {
        return $this->motifs;
    }

    public function setMotifs(string $motifs): self
    {
        $this->motifs = $motifs;
        return $this;
    }

    public function getNbJoursReste(): ?int
    {
        return $this->nbJoursReste;
    }

    public function setNbJoursReste(int $nbJoursReste): self
    {
        $this->nbJoursReste = $nbJoursReste;
        return $this;
    }
}
