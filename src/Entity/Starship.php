<?php

namespace App\Entity;

use App\Repository\StarshipRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StarshipRepository::class)]
class Starship extends SpaceShip
{
    #[ORM\Column]
    private ?int $autonomy = null;

    #[ORM\Column]
    private ?float $cargoCapacity = null;

    public function getAutonomy(): ?int
    {
        return $this->autonomy;
    }

    public function setAutonomy(int $autonomy): static
    {
        $this->autonomy = $autonomy;

        return $this;
    }

    public function getCargoCapacity(): ?float
    {
        return $this->cargoCapacity;
    }

    public function setCargoCapacity(float $cargoCapacity): static
    {
        $this->cargoCapacity = $cargoCapacity;

        return $this;
    }
}
