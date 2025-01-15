<?php

namespace App\Entity;

use App\Repository\StarshipRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StarshipRepository::class)]
class Starship
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $cargoCapacity = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCargoCapacity(): ?int
    {
        return $this->cargoCapacity;
    }

    public function setCargoCapacity(int $cargoCapacity): static
    {
        $this->cargoCapacity = $cargoCapacity;

        return $this;
    }
}
