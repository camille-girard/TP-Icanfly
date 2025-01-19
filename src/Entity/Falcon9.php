<?php

namespace App\Entity;

use App\Repository\Falcon9Repository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: Falcon9Repository::class)]
class Falcon9 extends Spaceship
{
    #[ORM\Column]
    private ?int $crewCapacity = null;

    public function getCrewCapacity(): ?int
    {
        return $this->crewCapacity;
    }

    public function setCrewCapacity(int $crewCapacity): static
    {
        $this->crewCapacity = $crewCapacity;

        return $this;
    }
}
