<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\PlaceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlaceRepository::class)]
class Place
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $adressStreet = null;

    #[ORM\Column]
    private ?int $adressNumber = null;

    #[ORM\Column(nullable: true)]
    private ?int $capacity = null;

    #[ORM\ManyToOne(inversedBy: 'places')]
    private ?City $city = null;

    /**
     * @var Collection<int, FavoritePlace>
     */
    #[ORM\OneToMany(targetEntity: FavoritePlace::class, mappedBy: 'place')]
    private Collection $favoritePlaces;

    /**
     * @var Collection<int, Game>
     */
    #[ORM\ManyToMany(targetEntity: Game::class, inversedBy: 'places')]
    private Collection $games;

    /**
     * @var Collection<int, Meetup>
     */
    #[ORM\OneToMany(targetEntity: Meetup::class, mappedBy: 'place')]
    private Collection $meetups;

    public function __construct()
    {
        $this->favoritePlaces = new ArrayCollection();
        $this->games = new ArrayCollection();
        $this->meetups = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdressStreet(): ?string
    {
        return $this->adressStreet;
    }

    public function setAdressStreet(string $adressStreet): static
    {
        $this->adressStreet = $adressStreet;

        return $this;
    }

    public function getAdressNumber(): ?int
    {
        return $this->adressNumber;
    }

    public function setAdressNumber(int $adressNumber): static
    {
        $this->adressNumber = $adressNumber;

        return $this;
    }

    public function getCapacity(): ?int
    {
        return $this->capacity;
    }

    public function setCapacity(?int $capacity): static
    {
        $this->capacity = $capacity;

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
            $favoritePlace->setPlace($this);
        }

        return $this;
    }

    public function removeFavoritePlace(FavoritePlace $favoritePlace): static
    {
        if ($this->favoritePlaces->removeElement($favoritePlace)) {
            // set the owning side to null (unless already changed)
            if ($favoritePlace->getPlace() === $this) {
                $favoritePlace->setPlace(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Game>
     */
    public function getGames(): Collection
    {
        return $this->games;
    }

    public function addGame(Game $game): static
    {
        if (!$this->games->contains($game)) {
            $this->games->add($game);
        }

        return $this;
    }

    public function removeGame(Game $game): static
    {
        $this->games->removeElement($game);

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
            $meetup->setPlace($this);
        }

        return $this;
    }

    public function removeMeetup(Meetup $meetup): static
    {
        if ($this->meetups->removeElement($meetup)) {
            // set the owning side to null (unless already changed)
            if ($meetup->getPlace() === $this) {
                $meetup->setPlace(null);
            }
        }

        return $this;
    }
}
