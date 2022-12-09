<?php

namespace App\Entity;

use App\Repository\NoteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NoteRepository::class)]
class Note
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $valeur = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $commentaire = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(inversedBy: 'notesGiven')]
    private ?User $userGivingNote = null;

    #[ORM\ManyToOne(inversedBy: 'notesReceived')]
    private ?User $userReceivingNote = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValeur(): ?int
    {
        return $this->valeur;
    }

    public function setValeur(?int $valeur): self
    {
        $this->valeur = $valeur;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): self
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getUserGivingNote(): ?User
    {
        return $this->userGivingNote;
    }

    public function setUserGivingNote(?User $userGivingNote): self
    {
        $this->userGivingNote = $userGivingNote;

        return $this;
    }

    public function getUserReceivingNote(): ?User
    {
        return $this->userReceivingNote;
    }

    public function setUserReceivingNote(?User $userReceivingNote): self
    {
        $this->userReceivingNote = $userReceivingNote;

        return $this;
    }
}
