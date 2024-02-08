<?php

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TagRepository::class)]
class Tag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: Game::class, inversedBy: 'tags')]
    private Collection $GameTag;

    public function __construct()
    {
        $this->GameTag = new ArrayCollection();
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
    public function getGameTag(): Collection
    {
        return $this->GameTag;
    }

    public function addGameTag(Game $gameTag): static
    {
        if (!$this->GameTag->contains($gameTag)) {
            $this->GameTag->add($gameTag);
        }

        return $this;
    }

    public function removeGameTag(Game $gameTag): static
    {
        $this->GameTag->removeElement($gameTag);

        return $this;
    }
}
