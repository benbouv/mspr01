<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\OneToMany(mappedBy: 'userGivingNote', targetEntity: Note::class)]
    private Collection $notesGiven;

    #[ORM\OneToMany(mappedBy: 'userReceivingNote', targetEntity: Note::class)]
    private Collection $notesReceived;

    #[ORM\OneToMany(mappedBy: 'userOwningPlant', targetEntity: Plante::class)]
    private Collection $plantesOwned;

    #[ORM\OneToMany(mappedBy: 'userKeepingPlant', targetEntity: Plante::class)]
    private Collection $plantesKept;

    #[ORM\OneToMany(mappedBy: 'userWritingMessage', targetEntity: Message::class)]
    private Collection $messagesWritten;

    #[ORM\OneToMany(mappedBy: 'userReceivingMessage', targetEntity: Message::class)]
    private Collection $messagesReceived;

    #[ORM\OneToMany(mappedBy: 'userAdviseMessage', targetEntity: Message::class)]
    private Collection $messagesAdvised;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Pseudo = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $telephone = null;

    public function __construct()
    {
        $this->notesGiven = new ArrayCollection();
        $this->notesReceived = new ArrayCollection();
        $this->plantesOwned = new ArrayCollection();
        $this->plantesKept = new ArrayCollection();
        $this->messagesWritten = new ArrayCollection();
        $this->messagesReceived = new ArrayCollection();
        $this->messagesAdvised = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Note>
     */
    public function getNotesGiven(): Collection
    {
        return $this->notesGiven;
    }

    public function addNotesGiven(Note $notesGiven): self
    {
        if (!$this->notesGiven->contains($notesGiven)) {
            $this->notesGiven->add($notesGiven);
            $notesGiven->setUserGivingNote($this);
        }

        return $this;
    }

    public function removeNotesGiven(Note $notesGiven): self
    {
        if ($this->notesGiven->removeElement($notesGiven)) {
            // set the owning side to null (unless already changed)
            if ($notesGiven->getUserGivingNote() === $this) {
                $notesGiven->setUserGivingNote(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Note>
     */
    public function getNotesReceived(): Collection
    {
        return $this->notesReceived;
    }

    public function addNotesReceived(Note $notesReceived): self
    {
        if (!$this->notesReceived->contains($notesReceived)) {
            $this->notesReceived->add($notesReceived);
            $notesReceived->setUserReceivingNote($this);
        }

        return $this;
    }

    public function removeNotesReceived(Note $notesReceived): self
    {
        if ($this->notesReceived->removeElement($notesReceived)) {
            // set the owning side to null (unless already changed)
            if ($notesReceived->getUserReceivingNote() === $this) {
                $notesReceived->setUserReceivingNote(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Plante>
     */
    public function getPlantesOwned(): Collection
    {
        return $this->plantesOwned;
    }

    public function addPlantesOwned(Plante $plantesOwned): self
    {
        if (!$this->plantesOwned->contains($plantesOwned)) {
            $this->plantesOwned->add($plantesOwned);
            $plantesOwned->setUserOwningPlant($this);
        }

        return $this;
    }

    public function removePlantesOwned(Plante $plantesOwned): self
    {
        if ($this->plantesOwned->removeElement($plantesOwned)) {
            // set the owning side to null (unless already changed)
            if ($plantesOwned->getUserOwningPlant() === $this) {
                $plantesOwned->setUserOwningPlant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Plante>
     */
    public function getPlantesKept(): Collection
    {
        return $this->plantesKept;
    }

    public function addPlantesKept(Plante $plantesKept): self
    {
        if (!$this->plantesKept->contains($plantesKept)) {
            $this->plantesKept->add($plantesKept);
            $plantesKept->setUserKeepingPlant($this);
        }

        return $this;
    }

    public function removePlantesKept(Plante $plantesKept): self
    {
        if ($this->plantesKept->removeElement($plantesKept)) {
            // set the owning side to null (unless already changed)
            if ($plantesKept->getUserKeepingPlant() === $this) {
                $plantesKept->setUserKeepingPlant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessagesWritten(): Collection
    {
        return $this->messagesWritten;
    }

    public function addMessagesWritten(Message $messagesWritten): self
    {
        if (!$this->messagesWritten->contains($messagesWritten)) {
            $this->messagesWritten->add($messagesWritten);
            $messagesWritten->setUserWritingMessage($this);
        }

        return $this;
    }

    public function removeMessagesWritten(Message $messagesWritten): self
    {
        if ($this->messagesWritten->removeElement($messagesWritten)) {
            // set the owning side to null (unless already changed)
            if ($messagesWritten->getUserWritingMessage() === $this) {
                $messagesWritten->setUserWritingMessage(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessagesReceived(): Collection
    {
        return $this->messagesReceived;
    }

    public function addMessagesReceived(Message $messagesReceived): self
    {
        if (!$this->messagesReceived->contains($messagesReceived)) {
            $this->messagesReceived->add($messagesReceived);
            $messagesReceived->setUserReceivingMessage($this);
        }

        return $this;
    }

    public function removeMessagesReceived(Message $messagesReceived): self
    {
        if ($this->messagesReceived->removeElement($messagesReceived)) {
            // set the owning side to null (unless already changed)
            if ($messagesReceived->getUserReceivingMessage() === $this) {
                $messagesReceived->setUserReceivingMessage(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessagesAdvised(): Collection
    {
        return $this->messagesAdvised;
    }

    public function addMessagesAdvised(Message $messagesAdvised): self
    {
        if (!$this->messagesAdvised->contains($messagesAdvised)) {
            $this->messagesAdvised->add($messagesAdvised);
            $messagesAdvised->setUserAdviseMessage($this);
        }

        return $this;
    }

    public function removeMessagesAdvised(Message $messagesAdvised): self
    {
        if ($this->messagesAdvised->removeElement($messagesAdvised)) {
            // set the owning side to null (unless already changed)
            if ($messagesAdvised->getUserAdviseMessage() === $this) {
                $messagesAdvised->setUserAdviseMessage(null);
            }
        }

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->Pseudo;
    }

    public function setPseudo(?string $Pseudo): self
    {
        $this->Pseudo = $Pseudo;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(?string $Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }
}
