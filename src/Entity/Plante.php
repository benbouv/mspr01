<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Repository\PlanteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
        operations: [
            new Get(normalizationContext: ['groups' => 'plante:item']),
            //new GetCollection(normalizationContext: ['groups' => 'plante:list']), //GET ALL PLANTES
            //new Post(normalizationContext: ['groups' => 'plante:item']),
        ],
        order: ['famille' => 'DESC', 'nom' => 'ASC'],
        paginationEnabled: false,
    )]
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

    #[Groups(['plante:list', 'plante:item'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nom = null;

    #[Groups(['plante:list', 'plante:item'])]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $periodeArrosage = null;

    #[Groups(['plante:list', 'plante:item'])]
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

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Image = null;

    #[ORM\OneToMany(mappedBy: 'PlantePossedePhoto', targetEntity: Photo::class, orphanRemoval: true)]
    private Collection $photos;

    public function __construct()
    {
        $this->messagesInformed = new ArrayCollection();
        $this->photos = new ArrayCollection();
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

    public function getImage(): ?string
    {
        return $this->Image;
    }

    public function setImage(?string $Image): self
    {
        $this->Image = $Image;

        return $this;
    }

    /**
     * @return Collection<int, Photo>
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(Photo $photo): self
    {
        if (!$this->photos->contains($photo)) {
            $this->photos->add($photo);
            $photo->setPlantePossedePhoto($this);
        }

        return $this;
    }

    public function removePhoto(Photo $photo): self
    {
        if ($this->photos->removeElement($photo)) {
            // set the owning side to null (unless already changed)
            if ($photo->getPlantePossedePhoto() === $this) {
                $photo->setPlantePossedePhoto(null);
            }
        }

        return $this;
    }
}
