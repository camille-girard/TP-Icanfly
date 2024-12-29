<?php

namespace App\Entity;

use App\Repository\TouristMissionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TouristMissionRepository::class)]
class TouristMission extends Mission
{
    #[ORM\Column(length: 255)]
    private ?string $activities = null;

    #[ORM\Column]
    private ?bool $hasGuide = null;

    public function getActivities(): ?string
    {
        return $this->activities;
    }

    public function setActivities(string $activities): static
    {
        $this->activities = $activities;

        return $this;
    }

    public function hasGuide(): ?bool
    {
        return $this->hasGuide;
    }

    public function setHasGuide(bool $hasGuide): static
    {
        $this->hasGuide = $hasGuide;

        return $this;
    }
}
