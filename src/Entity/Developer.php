<?php

namespace App\Entity;

use App\Repository\DeveloperRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DeveloperRepository::class)]
class Developer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: Game::class, inversedBy: 'developers')]
    private Collection $gameDeveloper;

    public function __construct()
    {
        $this->gameDeveloper = new ArrayCollection();
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

    /**
     * @return Collection<int, Game>
     */
    public function getGameDeveloper(): Collection
    {
        return $this->gameDeveloper;
    }

    public function addGameDeveloper(Game $gameDeveloper): static
    {
        if (!$this->gameDeveloper->contains($gameDeveloper)) {
            $this->gameDeveloper->add($gameDeveloper);
        }

        return $this;
    }

    public function removeGameDeveloper(Game $gameDeveloper): static
    {
        $this->gameDeveloper->removeElement($gameDeveloper);

        return $this;
    }
}
