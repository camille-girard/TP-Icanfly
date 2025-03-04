<?php

namespace App\Entity;

use App\Repository\ScientificMissionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ScientificMissionRepository::class)]
class ScientificMission extends Mission
{
    #[ORM\Column(length: 255)]
    private ?string $specialEquipement = null;

    #[ORM\Column(length: 255)]
    private ?string $objective = null;

    public function getSpecialEquipement(): ?string
    {
        return $this->specialEquipement;
    }

    public function setSpecialEquipement(string $specialEquipement): static
    {
        $this->specialEquipement = $specialEquipement;

        return $this;
    }

    public function getObjective(): ?string
    {
        return $this->objective;
    }

    public function setObjective(string $objective): static
    {
        $this->objective = $objective;

        return $this;
    }
}
