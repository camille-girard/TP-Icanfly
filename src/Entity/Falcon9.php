<?php

namespace App\Entity;

use App\Repository\Falcon9Repository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: Falcon9Repository::class)]
class Falcon9 extends SpaceShip
{
    #[ORM\Column]
    private ?int $stageCount = null;

    #[ORM\Column]
    private ?bool $reusable = null;

    public function getStageCount(): ?int
    {
        return $this->stageCount;
    }

    public function setStageCount(int $stageCount): static
    {
        $this->stageCount = $stageCount;

        return $this;
    }

    public function isReusable(): ?bool
    {
        return $this->reusable;
    }

    public function setReusable(bool $reusable): static
    {
        $this->reusable = $reusable;

        return $this;
    }
}
