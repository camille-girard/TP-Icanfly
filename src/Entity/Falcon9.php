<?php

namespace App\Entity;

use App\Repository\Falcon9Repository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: Falcon9Repository::class)]
class Falcon9
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $crewCapacity = null;

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
}
