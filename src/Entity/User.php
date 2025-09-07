<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $bio = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imgUrl = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $creationDate = null;

   

    /**
     * @var Collection<int, PersonalityTrait>
     */
    #[ORM\ManyToMany(targetEntity: PersonalityTrait::class, inversedBy: 'users')]
    private Collection $personalityTraits;

    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?City $city = null;

    /**
     * @var Collection<int, FavoriteGame>
     */
    #[ORM\OneToMany(targetEntity: FavoriteGame::class, mappedBy: 'user')]
    private Collection $favoriteGames;

    /**
     * @var Collection<int, FavoritePlace>
     */
    #[ORM\OneToMany(targetEntity: FavoritePlace::class, mappedBy: 'user')]
    private Collection $favoritePlaces;

    /**
     * @var Collection<int, Meetup>
     */
    #[ORM\ManyToMany(targetEntity: Meetup::class, inversedBy: 'users')]
    private Collection $meetups;

    /**
     * @var Collection<int, Message>
     */
    #[ORM\OneToMany(targetEntity: Message::class, mappedBy: 'user1')]
    private Collection $messages;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    public function __construct()
    {
        $this->availabilities = new ArrayCollection();
        $this->personalityTraits = new ArrayCollection();
        $this->favoriteGames = new ArrayCollection();
        $this->favoritePlaces = new ArrayCollection();
        $this->meetups = new ArrayCollection();
        $this->messages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
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

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Ensure the session doesn't contain actual password hashes by CRC32C-hashing them, as supported since Symfony 7.3.
     */
    public function __serialize(): array
    {
        $data = (array) $this;
        $data["\0".self::class."\0password"] = hash('crc32c', $this->password);

        return $data;
    }

    #[\Deprecated]
    public function eraseCredentials(): void
    {
        // @deprecated, to be removed when upgrading to Symfony 8
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(?string $bio): static
    {
        $this->bio = $bio;

        return $this;
    }

    public function getImgUrl(): ?string
    {
        return $this->imgUrl;
    }

    public function setImgUrl(?string $imgUrl): static
    {
        $this->imgUrl = $imgUrl;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeImmutable
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeImmutable $creationDate): static
    {
        $this->creationDate = $creationDate;

        return $this;
    }





 

    /**
     * @return Collection<int, PersonalityTrait>
     */
    public function getPersonalityTraits(): Collection
    {
        return $this->personalityTraits;
    }

    public function addPersonalityTrait(PersonalityTrait $personalityTrait): static
    {
        if (!$this->personalityTraits->contains($personalityTrait)) {
            $this->personalityTraits->add($personalityTrait);
        }

        return $this;
    }

    public function removePersonalityTrait(PersonalityTrait $personalityTrait): static
    {
        $this->personalityTraits->removeElement($personalityTrait);

        return $this;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): static
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return Collection<int, FavoriteGame>
     */
    public function getFavoriteGames(): Collection
    {
        return $this->favoriteGames;
    }

    public function addFavoriteGame(FavoriteGame $favoriteGame): static
    {
        if (!$this->favoriteGames->contains($favoriteGame)) {
            $this->favoriteGames->add($favoriteGame);
            $favoriteGame->setUser($this);
        }

        return $this;
    }

    public function removeFavoriteGame(FavoriteGame $favoriteGame): static
    {
        if ($this->favoriteGames->removeElement($favoriteGame)) {
            // set the owning side to null (unless already changed)
            if ($favoriteGame->getUser() === $this) {
                $favoriteGame->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, FavoritePlace>
     */
    public function getFavoritePlaces(): Collection
    {
        return $this->favoritePlaces;
    }

    public function addFavoritePlace(FavoritePlace $favoritePlace): static
    {
        if (!$this->favoritePlaces->contains($favoritePlace)) {
            $this->favoritePlaces->add($favoritePlace);
            $favoritePlace->setUser($this);
        }

        return $this;
    }

    public function removeFavoritePlace(FavoritePlace $favoritePlace): static
    {
        if ($this->favoritePlaces->removeElement($favoritePlace)) {
            // set the owning side to null (unless already changed)
            if ($favoritePlace->getUser() === $this) {
                $favoritePlace->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Meetup>
     */
    public function getMeetups(): Collection
    {
        return $this->meetups;
    }

    public function addMeetup(Meetup $meetup): static
    {
        if (!$this->meetups->contains($meetup)) {
            $this->meetups->add($meetup);
        }

        return $this;
    }

    public function removeMeetup(Meetup $meetup): static
    {
        $this->meetups->removeElement($meetup);

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): static
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setUser1($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): static
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getUser1() === $this) {
                $message->setUser1(null);
            }
        }

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }
}
