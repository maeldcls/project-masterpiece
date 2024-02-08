<?php

namespace App\Entity;

use App\Repository\PublisherRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PublisherRepository::class)]
class Publisher
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: Game::class, inversedBy: 'publishers')]
    private Collection $gamePublisher;

    public function __construct()
    {
        $this->gamePublisher = new ArrayCollection();
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
    public function getGamePublisher(): Collection
    {
        return $this->gamePublisher;
    }

    public function addGamePublisher(Game $gamePublisher): static
    {
        if (!$this->gamePublisher->contains($gamePublisher)) {
            $this->gamePublisher->add($gamePublisher);
        }

        return $this;
    }

    public function removeGamePublisher(Game $gamePublisher): static
    {
        $this->gamePublisher->removeElement($gamePublisher);

        return $this;
    }
}
