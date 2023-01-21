<?php

namespace App\Entity;

use App\Repository\DemandeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DemandeRepository::class)]
class Demande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    private ?User $UserFaitDemande = null;

    #[ORM\ManyToOne]
    private ?Plante $PlanteContientDemande = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $DateDeCreation = null;

    #[ORM\ManyToOne]
    private ?User $UserRecoiDemande = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserFaitDemande(): ?User
    {
        return $this->UserFaitDemande;
    }

    public function setUserFaitDemande(?User $UserFaitDemande): self
    {
        $this->UserFaitDemande = $UserFaitDemande;

        return $this;
    }

    public function getPlanteContientDemande(): ?Plante
    {
        return $this->PlanteContientDemande;
    }

    public function setPlanteContientDemande(?Plante $PlanteContientDemande): self
    {
        $this->PlanteContientDemande = $PlanteContientDemande;

        return $this;
    }

    public function getDateDeCreation(): ?\DateTimeInterface
    {
        return $this->DateDeCreation;
    }

    public function setDateDeCreation(?\DateTimeInterface $DateDeCreation): self
    {
        $this->DateDeCreation = $DateDeCreation;

        return $this;
    }

    public function getUserRecoiDemande(): ?User
    {
        return $this->UserRecoiDemande;
    }

    public function setUserRecoiDemande(?User $UserRecoiDemande): self
    {
        $this->UserRecoiDemande = $UserRecoiDemande;

        return $this;
    }
}
