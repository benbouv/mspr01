<?php

namespace App\Entity;

use App\Repository\PlanteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlanteRepository::class)]
class Plante
{

    const FAMILLELIST = [
        0 => 'Je ne connais pas',
        1 => 'Plante à fleur',
        2 => 'Cactus',
        3 => 'Herbacées',
        4 => 'Arbres',
        5 => 'Algues',
        6 => 'Autre?'
    ];


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $periodeArrosage = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $famille = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $positionGPS = null;

    #[ORM\ManyToOne(inversedBy: 'plantesOwned')]
    private ?User $userOwningPlant = null;

    #[ORM\ManyToOne(inversedBy: 'plantesKept')]
    private ?User $userKeepingPlant = null;

    #[ORM\OneToMany(mappedBy: 'plantInformedByMessage', targetEntity: Message::class)]
    private Collection $messagesInformed;

    public function __construct()
    {
        $this->messagesInformed = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPeriodeArrosage(): ?string
    {
        return $this->periodeArrosage;
    }

    public function setPeriodeArrosage(?string $periodeArrosage): self
    {
        $this->periodeArrosage = $periodeArrosage;

        return $this;
    }

    public function getFamille(): ?string
    {
        return $this->famille;
    }

    public function setFamille(?string $famille): self
    {
        $this->famille = $famille;

        return $this;
    }

    public function getPositionGPS(): ?string
    {
        return $this->positionGPS;
    }

    public function setPositionGPS(?string $positionGPS): self
    {
        $this->positionGPS = $positionGPS;

        return $this;
    }

    public function getUserOwningPlant(): ?User
    {
        return $this->userOwningPlant;
    }

    public function setUserOwningPlant(?User $userOwningPlant): self
    {
        $this->userOwningPlant = $userOwningPlant;

        return $this;
    }

    public function getUserKeepingPlant(): ?User
    {
        return $this->userKeepingPlant;
    }

    public function setUserKeepingPlant(?User $userKeepingPlant): self
    {
        $this->userKeepingPlant = $userKeepingPlant;

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessagesInformed(): Collection
    {
        return $this->messagesInformed;
    }

    public function addMessagesInformed(Message $messagesInformed): self
    {
        if (!$this->messagesInformed->contains($messagesInformed)) {
            $this->messagesInformed->add($messagesInformed);
            $messagesInformed->setPlantInformedByMessage($this);
        }

        return $this;
    }

    public function removeMessagesInformed(Message $messagesInformed): self
    {
        if ($this->messagesInformed->removeElement($messagesInformed)) {
            // set the owning side to null (unless already changed)
            if ($messagesInformed->getPlantInformedByMessage() === $this) {
                $messagesInformed->setPlantInformedByMessage(null);
            }
        }

        return $this;
    }
}
