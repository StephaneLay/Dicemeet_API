<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameRepository::class)]

class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $minPlayers = null;

    #[ORM\Column]
    private ?int $maxPlayers = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imgUrl = null;

    

    #[ORM\ManyToOne(inversedBy: 'games')]
    private ?Category $category = null;

    /**
     * @var Collection<int, FavoriteGame>
     */
    #[ORM\OneToMany(targetEntity: FavoriteGame::class, mappedBy: 'game')]
    private Collection $favoriteGames;

    
    /**
     * @var Collection<int, Meetup>
     */
    #[ORM\OneToMany(targetEntity: Meetup::class, mappedBy: 'game')]
    private Collection $meetups;

    public function __construct()
    {
        $this->favoriteGames = new ArrayCollection();
        $this->meetups = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMinPlayers(): ?int
    {
        return $this->minPlayers;
    }

    public function setMinPlayers(int $minPlayers): static
    {
        $this->minPlayers = $minPlayers;

        return $this;
    }

    public function getMaxPlayers(): ?int
    {
        return $this->maxPlayers;
    }

    public function setMaxPlayers(int $maxPlayers): static
    {
        $this->maxPlayers = $maxPlayers;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

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


    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

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
            $favoriteGame->setGame($this);
        }

        return $this;
    }

    public function removeFavoriteGame(FavoriteGame $favoriteGame): static
    {
        if ($this->favoriteGames->removeElement($favoriteGame)) {
            // set the owning side to null (unless already changed)
            if ($favoriteGame->getGame() === $this) {
                $favoriteGame->setGame(null);
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
            $meetup->setGame($this);
        }

        return $this;
    }

    public function removeMeetup(Meetup $meetup): static
    {
        if ($this->meetups->removeElement($meetup)) {
            // set the owning side to null (unless already changed)
            if ($meetup->getGame() === $this) {
                $meetup->setGame(null);
            }
        }

        return $this;
    }
}
