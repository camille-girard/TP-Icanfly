<?php

namespace App\Entity;

use App\Repository\DragonRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DragonRepository::class)]
class Dragon extends Spaceship
{

    #[ORM\Column]
    private ?int $seatCapacity = null;

    public function getSeatCapacity(): ?int
    {
        return $this->seatCapacity;
    }

    public function setSeatCapacity(int $seatCapacity): static
    {
        $this->seatCapacity = $seatCapacity;

        return $this;
    }
}
