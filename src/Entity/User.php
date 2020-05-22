<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\EquatableInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="users")
 * @UniqueEntity(fields={"username"}, message="There is already an account with this username")
 */
class User implements UserInterface, \Serializable, EquatableInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="json")
     */
    private $roles;
    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $fullName;

    /**
     * @ORM\Column(type="string", unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(min=2, max=50)
     */
    private $username;

    /**
     * @ORM\Column(type="string", unique=true)
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="string")
     */
    private $password;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $locale;


    /**
     * @ORM\Column(type="string", length=14, nullable=true)
     */
    private $telephone;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $professionalNumber;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $experienceYears;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Event", mappedBy="planner")
     */
    private $myEvents;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Participation", mappedBy="participant", orphanRemoval=true)
     */
    private $participation;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Image", cascade={"persist","remove"})
     */
    private $ProfilImage;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Country")
     * @ORM\JoinColumn(nullable=false)
     */
    private $nationality;

    public function __construct()
    {
        $this->events = new ArrayCollection();
        $this->participantEvents = new ArrayCollection();
        $this->myEvents = new ArrayCollection();
        $this->participation = new ArrayCollection();
    }    
    public function getId(): int
    {
        return $this->id;
    }

    public function setFullName(string $fullName): void
    {
        $this->fullName = $fullName;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        return array_unique($roles);
    }

    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }
    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function setLocale(?string $locale): self
    {
        $this->locale = $locale;

        return $this;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials(): void
    {
    }
    public function serialize()
    {
        return serialize([
            $this->id, 
            $this->username,
            $this->email,
            $this->password,
            $this->locale,
            $this->roles
        ]);
    } 

    public function unserialize($string)
    {
        list (
            $this->id, 
            $this->username,
            $this->email,
            $this->password,
            $this->locale,
            $this->roles
        ) = unserialize($string, ['allowed_classes' => false]);
    }
    public function isEqualTo(UserInterface $user)
    {
        if ($user instanceof self)
        {
            if ($user->getLocale() != $this->locale) {
                return false;
            }
        }
        return true;
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

    public function getProfessionalNumber(): ?int
    {
        return $this->professionalNumber;
    }

    public function setProfessionalNumber(?int $professionalNumber): self
    {
        $this->professionalNumber = $professionalNumber;

        return $this;
    }

    public function getExperienceYears(): ?int
    {
        return $this->experienceYears;
    }

    public function setExperienceYears(?int $experienceYears): self
    {
        $this->experienceYears = $experienceYears;

        return $this;
    }

    /**
     * @return Collection|Event[]
     */
    public function getMyEvents(): Collection
    {
        return $this->myEvents;
    }

    public function addMyEvent(Event $myEvent): self
    {
        if (!$this->myEvents->contains($myEvent)) {
            $this->myEvents[] = $myEvent;
            $myEvent->setPlanner($this);
        }

        return $this;
    }

    public function removeMyEvent(Event $myEvent): self
    {
        if ($this->myEvents->contains($myEvent)) {
            $this->myEvents->removeElement($myEvent);
            // set the owning side to null (unless already changed)
            if ($myEvent->getPlanner() === $this) {
                $myEvent->setPlanner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Participation[]
     */
    public function getParticipation(): Collection
    {
        return $this->participation;
    }

    public function addParticipation(Participation $participation): self
    {
        if (!$this->participation->contains($participation)) {
            $this->participation[] = $participation;
            $participation->setParticipant($this);
        }

        return $this;
    }

    public function removeParticipation(Participation $participation): self
    {
        if ($this->participation->contains($participation)) {
            $this->participation->removeElement($participation);
            // set the owning side to null (unless already changed)
            if ($participation->getParticipant() === $this) {
                $participation->setParticipant(null);
            }
        }

        return $this;
    }

    public function getProfilImage(): ?Image
    {
        return $this->ProfilImage;
    }

    public function setProfilImage(?Image $ProfilImage): self
    {
        $this->ProfilImage = $ProfilImage;

        return $this;
    }

    public function getNationality(): ?Country
    {
        return $this->nationality;
    }

    public function setNationality(?Country $nationality): self
    {
        $this->nationality = $nationality;

        return $this;
    }
}
