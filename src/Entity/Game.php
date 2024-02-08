<?php

namespace App\Entity;

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
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $summary = null;


    #[ORM\Column(length: 255, nullable: true)]
    private ?string $website = null;



    #[ORM\OneToMany(mappedBy: 'game', targetEntity: GameUser::class)]
    private Collection $gameUsers;

    #[ORM\ManyToMany(targetEntity: Tag::class, mappedBy: 'GameTag')]
    private Collection $tags;

    #[ORM\ManyToMany(targetEntity: Platform::class, mappedBy: 'gamePlatform')]
    private Collection $platforms;

    #[ORM\ManyToMany(targetEntity: Genre::class, mappedBy: 'gameGenre')]
    private Collection $genres;

    #[ORM\ManyToMany(targetEntity: Publisher::class, mappedBy: 'gamePublisher')]
    private Collection $publishers;

    #[ORM\ManyToMany(targetEntity: Developer::class, mappedBy: 'gameDeveloper')]
    private Collection $developers;

    #[ORM\Column]
    private ?int $metacritics = null;

    #[ORM\Column(length: 255)]
    private ?string $backgroundImage = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private ?array $parentPlatform = null;

    #[ORM\Column]
    private ?int $gameId = null;

    #[ORM\Column(nullable: true)]
    private ?array $screenshots = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $releaseDate = null;


    public function __construct()
    {
        $this->gameUsers = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->platforms = new ArrayCollection();
        $this->genres = new ArrayCollection();
        $this->publishers = new ArrayCollection();
        $this->developers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(string $summary): static
    {
        $this->summary = $summary;

        return $this;
    }

  

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): static
    {
        $this->website = $website;

        return $this;
    }



    /**
     * @return Collection<int, GameUser>
     */
    public function getGameUsers(): Collection
    {
        return $this->gameUsers;
    }

    public function addGameUser(GameUser $gameUser): static
    {
        if (!$this->gameUsers->contains($gameUser)) {
            $this->gameUsers->add($gameUser);
            $gameUser->setGame($this);
        }

        return $this;
    }

    public function removeGameUser(GameUser $gameUser): static
    {
        if ($this->gameUsers->removeElement($gameUser)) {
            // set the owning side to null (unless already changed)
            if ($gameUser->getGame() === $this) {
                $gameUser->setGame(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): static
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
            $tag->addGameTag($this);
        }

        return $this;
    }

    public function removeTag(Tag $tag): static
    {
        if ($this->tags->removeElement($tag)) {
            $tag->removeGameTag($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Platform>
     */
    public function getPlatforms(): Collection
    {
        return $this->platforms;
    }

    public function addPlatform(Platform $platform): static
    {
        if (!$this->platforms->contains($platform)) {
            $this->platforms->add($platform);
            $platform->addGamePlatform($this);
        }

        return $this;
    }

    public function removePlatform(Platform $platform): static
    {
        if ($this->platforms->removeElement($platform)) {
            $platform->removeGamePlatform($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Genre>
     */
    public function getGenres(): Collection
    {
        return $this->genres;
    }

    public function addGenre(Genre $genre): static
    {
        if (!$this->genres->contains($genre)) {
            $this->genres->add($genre);
            $genre->addGameGenre($this);
        }

        return $this;
    }

    public function removeGenre(Genre $genre): static
    {
        if ($this->genres->removeElement($genre)) {
            $genre->removeGameGenre($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Publisher>
     */
    public function getPublishers(): Collection
    {
        return $this->publishers;
    }

    public function addPublisher(Publisher $publisher): static
    {
        if (!$this->publishers->contains($publisher)) {
            $this->publishers->add($publisher);
            $publisher->addGamePublisher($this);
        }

        return $this;
    }

    public function removePublisher(Publisher $publisher): static
    {
        if ($this->publishers->removeElement($publisher)) {
            $publisher->removeGamePublisher($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Developer>
     */
    public function getDevelopers(): Collection
    {
        return $this->developers;
    }

    public function addDeveloper(Developer $developer): static
    {
        if (!$this->developers->contains($developer)) {
            $this->developers->add($developer);
            $developer->addGameDeveloper($this);
        }

        return $this;
    }

    public function removeDeveloper(Developer $developer): static
    {
        if ($this->developers->removeElement($developer)) {
            $developer->removeGameDeveloper($this);
        }

        return $this;
    }

    public function getMetacritics(): ?int
    {
        return $this->metacritics;
    }

    public function setMetacritics(int $metacritics): static
    {
        $this->metacritics = $metacritics;

        return $this;
    }

    public function getBackgroundImage(): ?string
    {
        return $this->backgroundImage;
    }

    public function setBackgroundImage(string $backgroundImage): static
    {
        $this->backgroundImage = $backgroundImage;

        return $this;
    }

    public function getParentPlatform(): ?array
    {
        return $this->parentPlatform;
    }

    public function setParentPlatform(?array $parentPlatform): static
    {
        $this->parentPlatform = $parentPlatform;

        return $this;
    }

    public function getGameId(): ?int
    {
        return $this->gameId;
    }

    public function setGameId(int $gameId): static
    {
        $this->gameId = $gameId;

        return $this;
    }

    public function getScreenshots(): ?array
    {
        return $this->screenshots;
    }

    public function setScreenshots(?array $screenshots): static
    {
        $this->screenshots = $screenshots;

        return $this;
    }

    public function getReleaseDate(): ?\DateTimeImmutable
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(?\DateTimeImmutable $releaseDate): static
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }


}
