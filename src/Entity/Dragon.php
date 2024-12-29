<?php

namespace App\Entity;

use App\Repository\DragonRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DragonRepository::class)]
class Dragon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $crewCapacity = null;

    #[ORM\Column]
    private ?bool $lifeSupport = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCrewCapacity(): ?int
    {
        return $this->crewCapacity;
    }

    public function setCrewCapacity(int $crewCapacity): static
    {
        $this->crewCapacity = $crewCapacity;

        return $this;
    }

    public function isLifeSupport(): ?bool
    {
        return $this->lifeSupport;
    }

    public function setLifeSupport(bool $lifeSupport): static
    {
        $this->lifeSupport = $lifeSupport;

        return $this;
    }
}
