<?php

namespace App\Entity;

use App\Repository\ServicesRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
#[ORM\Entity(repositoryClass: ServicesRepository::class)]
#[ORM\Table(name: 'services')]
class Services
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\OneToMany(
        mappedBy: 'service',
        targetEntity: SituationAdm::class
    )]
    #[ORM\Column(name: 'Id', type: 'integer')]
    private ?int $id = null;
    private Collection $situationsAdm;
    public function __construct()
    {
        $this->situationsAdm = new ArrayCollection();
    }
    #[ORM\Column(name: 'Direction_Generale', type: 'string', length: 26, nullable: true)]
    private ?string $directionGenerale = null;

    #[ORM\Column(name: 'Code_Soa', type: 'string', length: 17, nullable: true)]
    private ?string $codeSoa = null;

    #[ORM\Column(name: 'Services', type: 'string', length: 35, nullable: true)]
    private ?string $services = null;

    #[ORM\Column(name: 'Region', type: 'string', length: 19, nullable: true)]
    private ?string $region = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDirectionGenerale(): ?string
    {
        return $this->directionGenerale;
    }

    public function setDirectionGenerale(?string $directionGenerale): static
    {
        $this->directionGenerale = $directionGenerale;

        return $this;
    }

    public function getCodeSoa(): ?string
    {
        return $this->codeSoa;
    }

    public function setCodeSoa(?string $codeSoa): static
    {
        $this->codeSoa = $codeSoa;

        return $this;
    }

    public function getServices(): ?string
    {
        return $this->services;
    }

    public function setServices(?string $services): static
    {
        $this->services = $services;

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(?string $region): static
    {
        $this->region = $region;

        return $this;
    }
    public function getSituationsAdm(): Collection
    {
        return $this->situationsAdm;
    }
}
