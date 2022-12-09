<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $contenu = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(inversedBy: 'messagesWritten')]
    private ?User $userWritingMessage = null;

    #[ORM\ManyToOne(inversedBy: 'messagesReceived')]
    private ?User $userReceivingMessage = null;

    #[ORM\ManyToOne(inversedBy: 'messagesAdvised')]
    private ?User $userAdviseMessage = null;

    #[ORM\ManyToOne(inversedBy: 'messagesInformed')]
    private ?Plante $plantInformedByMessage = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(?string $contenu): self
    {
        $this->contenu = $contenu;

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

    public function getUserWritingMessage(): ?User
    {
        return $this->userWritingMessage;
    }

    public function setUserWritingMessage(?User $userWritingMessage): self
    {
        $this->userWritingMessage = $userWritingMessage;

        return $this;
    }

    public function getUserReceivingMessage(): ?User
    {
        return $this->userReceivingMessage;
    }

    public function setUserReceivingMessage(?User $userReceivingMessage): self
    {
        $this->userReceivingMessage = $userReceivingMessage;

        return $this;
    }

    public function getUserAdviseMessage(): ?User
    {
        return $this->userAdviseMessage;
    }

    public function setUserAdviseMessage(?User $userAdviseMessage): self
    {
        $this->userAdviseMessage = $userAdviseMessage;

        return $this;
    }

    public function getPlantInformedByMessage(): ?Plante
    {
        return $this->plantInformedByMessage;
    }

    public function setPlantInformedByMessage(?Plante $plantInformedByMessage): self
    {
        $this->plantInformedByMessage = $plantInformedByMessage;

        return $this;
    }
}
