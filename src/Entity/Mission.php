<?php

namespace App\Entity;

use App\Enum\MissionStatusType;
use App\Repository\MissionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\InheritanceType;

#[ORM\Entity(repositoryClass: MissionRepository::class)]
#[InheritanceType('JOINED')]
#[DiscriminatorColumn(name: 'mission_type', type: 'string')]
#[DiscriminatorMap([
    'tourist' => TouristMission::class,
    'scientific' => ScientificMission::class,
])]
class Mission
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $destination = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $launchDate = null;

    #[ORM\Column]
    private ?float $seatPrice = null;

    #[ORM\Column]
    private ?int $duration = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(enumType: MissionStatusType::class)]
    private ?MissionStatusType $status = null;

    /**
     * @var Collection<int, Seat>
     */
    #[ORM\OneToMany(targetEntity: Seat::class, mappedBy: 'mission')]
    private Collection $seats;

    /**
     * @var Collection<int, Booking>
     */
    #[ORM\OneToMany(targetEntity: Booking::class, mappedBy: 'mission')]
    private Collection $bookings;

    /**
     * @var Collection<int, Notification>
     */
    #[ORM\OneToMany(targetEntity: Notification::class, mappedBy: 'mission')]
    private Collection $notifications;

    /**
     * @var Collection<int, Statistic>
     */
    #[ORM\OneToMany(targetEntity: Statistic::class, mappedBy: 'mission')]
    private Collection $statistics;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'missions')]
    private Collection $participants;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?VideoStreaming $videoStreaming = null;

    #[ORM\ManyToOne(inversedBy: 'missions')]
    private ?SpaceShip $spaceship = null;

    public function __construct()
    {
        $this->seats = new ArrayCollection();
        $this->bookings = new ArrayCollection();
        $this->notifications = new ArrayCollection();
        $this->statistics = new ArrayCollection();
        $this->participants = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDestination(): ?string
    {
        return $this->destination;
    }

    public function setDestination(string $destination): static
    {
        $this->destination = $destination;

        return $this;
    }

    public function getLaunchDate(): ?\DateTimeImmutable
    {
        return $this->launchDate;
    }

    public function setLaunchDate(\DateTimeImmutable $launchDate): static
    {
        $this->launchDate = $launchDate;

        return $this;
    }

    public function getSeatPrice(): ?float
    {
        return $this->seatPrice;
    }

    public function setSeatPrice(float $seatPrice): static
    {
        $this->seatPrice = $seatPrice;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getStatus(): ?MissionStatusType
    {
        return $this->status;
    }

    public function setStatus(MissionStatusType $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, Seat>
     */
    public function getSeats(): Collection
    {
        return $this->seats;
    }

    public function addSeat(Seat $seat): static
    {
        if (!$this->seats->contains($seat)) {
            $this->seats->add($seat);
            $seat->setMission($this);
        }

        return $this;
    }

    public function removeSeat(Seat $seat): static
    {
        if ($this->seats->removeElement($seat)) {
            // set the owning side to null (unless already changed)
            if ($seat->getMission() === $this) {
                $seat->setMission(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Booking>
     */
    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    public function addBooking(Booking $booking): static
    {
        if (!$this->bookings->contains($booking)) {
            $this->bookings->add($booking);
            $booking->setMission($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): static
    {
        if ($this->bookings->removeElement($booking)) {
            // set the owning side to null (unless already changed)
            if ($booking->getMission() === $this) {
                $booking->setMission(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Notification>
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): static
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications->add($notification);
            $notification->setMission($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): static
    {
        if ($this->notifications->removeElement($notification)) {
            // set the owning side to null (unless already changed)
            if ($notification->getMission() === $this) {
                $notification->setMission(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Statistic>
     */
    public function getStatistics(): Collection
    {
        return $this->statistics;
    }

    public function addStatistic(Statistic $statistic): static
    {
        if (!$this->statistics->contains($statistic)) {
            $this->statistics->add($statistic);
            $statistic->setMission($this);
        }

        return $this;
    }

    public function removeStatistic(Statistic $statistic): static
    {
        if ($this->statistics->removeElement($statistic)) {
            // set the owning side to null (unless already changed)
            if ($statistic->getMission() === $this) {
                $statistic->setMission(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(User $participant): static
    {
        if (!$this->participants->contains($participant)) {
            $this->participants->add($participant);
            $participant->addMission($this);
        }

        return $this;
    }

    public function removeParticipant(User $participant): static
    {
        if ($this->participants->removeElement($participant)) {
            $participant->removeMission($this);
        }

        return $this;
    }

    public function getVideoStreaming(): ?VideoStreaming
    {
        return $this->videoStreaming;
    }

    public function setVideoStreaming(?VideoStreaming $videoStreaming): static
    {
        $this->videoStreaming = $videoStreaming;

        return $this;
    }

    public function getSpaceship(): ?SpaceShip
    {
        return $this->spaceship;
    }

    public function setSpaceship(?SpaceShip $spaceship): static
    {
        $this->spaceship = $spaceship;

        return $this;
    }
}
