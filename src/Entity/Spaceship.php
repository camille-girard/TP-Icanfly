<?php

namespace App\Entity;

use App\Enum\SpaceshipUsage;
use App\Repository\SpaceshipRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SpaceshipRepository::class)]
#[ORM\InheritanceType('JOINED')]
#[ORM\DiscriminatorColumn(name: 'type', type: 'string')]
#[ORM\DiscriminatorMap([
    'spaceship' => Spaceship::class,
    'starship' => Starship::class,
    'falcon9' => Falcon9::class,
    'dragon' => Dragon::class,
])]
class Spaceship
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $autonomy = null;

    #[ORM\Column(enumType: SpaceshipUsage::class)]
    private ?SpaceshipUsage $usage = null;

    /**
     * @var Collection<int, Mission>
     */
    #[ORM\ManyToMany(targetEntity: Mission::class, mappedBy: 'spaceships')]
    private Collection $missions;

    /**
     * @var Collection<int, Seat>
     */
    #[ORM\OneToMany(targetEntity: Seat::class, mappedBy: 'Spaceship')]
    private Collection $seats;

    public function __construct()
    {
        $this->missions = new ArrayCollection();
        $this->seats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getAutonomy(): ?int
    {
        return $this->autonomy;
    }

    public function setAutonomy(int $autonomy): static
    {
        $this->autonomy = $autonomy;

        return $this;
    }

    public function getUsage(): ?SpaceshipUsage
    {
        return $this->usage;
    }

    public function setUsage(SpaceshipUsage $usage): static
    {
        $this->usage = $usage;

        return $this;
    }

    /**
     * @return Collection<int, Mission>
     */
    public function getMissions(): Collection
    {
        return $this->missions;
    }

    public function addMission(Mission $mission): static
    {
        if (!$this->missions->contains($mission)) {
            $this->missions->add($mission);
            $mission->addSpaceship($this);
        }

        return $this;
    }

    public function removeMission(Mission $mission): static
    {
        if ($this->missions->removeElement($mission)) {
            $mission->removeSpaceship($this);
        }

        return $this;
    }

    public function getSeats(): Collection
    {
        return $this->seats;
    }

    public function addSeat(Seat $seat): static
    {
        if (!$this->seats->contains($seat)) {
            $this->seats->add($seat);
            $seat->setSpaceship($this);
        }

        return $this;
    }

    public function removeSeat(Seat $seat): static
    {
        if ($this->seats->removeElement($seat)) {
            if ($seat->getSpaceship() === $this) {
                $seat->setSpaceship(null);
            }
        }

        return $this;
    }
}
