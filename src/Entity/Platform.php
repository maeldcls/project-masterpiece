<?php

namespace App\Entity;

use App\Repository\PlatformRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlatformRepository::class)]
class Platform
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\ManyToMany(targetEntity: Game::class, inversedBy: 'platforms')]
    private Collection $gamePlatform;

    #[ORM\Column]
    private ?int $apiId = null;

    public function __construct()
    {
        $this->gamePlatform = new ArrayCollection();
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
    public function getGamePlatform(): Collection
    {
        return $this->gamePlatform;
    }

    public function addGamePlatform(Game $gamePlatform): static
    {
        if (!$this->gamePlatform->contains($gamePlatform)) {
            $this->gamePlatform->add($gamePlatform);
        }

        return $this;
    }

    public function removeGamePlatform(Game $gamePlatform): static
    {
        $this->gamePlatform->removeElement($gamePlatform);

        return $this;
    }

    public function getApiId(): ?int
    {
        return $this->apiId;
    }

    public function setApiId(int $apiId): static
    {
        $this->apiId = $apiId;

        return $this;
    }
}
