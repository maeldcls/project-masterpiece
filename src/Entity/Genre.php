<?php

namespace App\Entity;

use App\Repository\GenreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GenreRepository::class)]
class Genre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\ManyToMany(targetEntity: Game::class, inversedBy: 'genres')]
    private Collection $gameGenre;

    public function __construct()
    {
        $this->gameGenre = new ArrayCollection();
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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection<int, Game>
     */
    public function getGameGenre(): Collection
    {
        return $this->gameGenre;
    }

    public function addGameGenre(Game $gameGenre): static
    {
        if (!$this->gameGenre->contains($gameGenre)) {
            $this->gameGenre->add($gameGenre);
        }

        return $this;
    }

    public function removeGameGenre(Game $gameGenre): static
    {
        $this->gameGenre->removeElement($gameGenre);

        return $this;
    }
}
