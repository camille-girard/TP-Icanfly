<?php

namespace App\Entity;

use App\Repository\ScientificMissionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ScientificMissionRepository::class)]
class ScientificMission
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $objectives = null;

    #[ORM\Column(length: 255)]
    private ?string $specialEquipment = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getObjectives(): ?string
    {
        return $this->objectives;
    }

    public function setObjectives(string $objectives): static
    {
        $this->objectives = $objectives;

        return $this;
    }

    public function getSpecialEquipment(): ?string
    {
        return $this->specialEquipment;
    }

    public function setSpecialEquipment(string $specialEquipment): static
    {
        $this->specialEquipment = $specialEquipment;

        return $this;
    }
}
