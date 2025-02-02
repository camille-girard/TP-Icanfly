<?php

namespace App\Entity;

use App\Repository\MissionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MissionRepository::class)]
#[ORM\InheritanceType('JOINED')]
#[ORM\DiscriminatorColumn(name: 'type', type: 'string')]
#[ORM\DiscriminatorMap(['mission' => Mission::class, 'scientific_mission' => ScientificMission::class, 'tourist_mission' => TouristMission::class])]
class Mission
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $destination = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column]
    private ?int $seatPrice = null;

    #[ORM\Column(length: 2500)]
    private ?string $image = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $duration = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\OneToMany(targetEntity: Booking::class, mappedBy: 'mission')]
    private Collection $bookings;

    #[ORM\ManyToMany(targetEntity: Spaceship::class, inversedBy: 'missions')]
    #[ORM\JoinTable(name: 'mission_spaceship')]
    private Collection $spaceships;

    #[ORM\OneToMany(targetEntity: VideoStreaming::class, mappedBy: 'mission')]
    private Collection $videoStreamings;

    #[ORM\OneToMany(targetEntity: Payment::class, mappedBy: 'mission')]
    private Collection $payments;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?User $operator = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->bookings = new ArrayCollection();
        $this->spaceships = new ArrayCollection();
        $this->videoStreamings = new ArrayCollection();
        $this->payments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getSeatPrice(): ?int
    {
        return $this->seatPrice;
    }

    public function setSeatPrice(int $seatPrice): static
    {
        $this->seatPrice = $seatPrice;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getDuration(): ?\DateTimeInterface
    {
        return $this->duration;
    }

    public function setDuration(\DateTimeInterface $duration): static
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
            if ($booking->getMission() === $this) {
                $booking->setMission(null);
            }
        }

        return $this;
    }

    public function getSpaceships(): Collection
    {
        return $this->spaceships;
    }

    public function addSpaceship(Spaceship $spaceship): static
    {
        if (!$this->spaceships->contains($spaceship)) {
            $this->spaceships->add($spaceship);
        }

        return $this;
    }

    public function removeSpaceship(Spaceship $spaceship): static
    {
        $this->spaceships->removeElement($spaceship);

        return $this;
    }

    public function getVideoStreamings(): Collection
    {
        return $this->videoStreamings;
    }

    public function addVideoStreaming(VideoStreaming $videoStreaming): static
    {
        if (!$this->videoStreamings->contains($videoStreaming)) {
            $this->videoStreamings->add($videoStreaming);
            $videoStreaming->setMission($this);
        }

        return $this;
    }

    public function removeVideoStreaming(VideoStreaming $videoStreaming): static
    {
        if ($this->videoStreamings->removeElement($videoStreaming)) {
            if ($videoStreaming->getMission() === $this) {
                $videoStreaming->setMission(null);
            }
        }

        return $this;
    }

    public function getPayments(): Collection
    {
        return $this->payments;
    }

    public function addPayment(Payment $payment): static
    {
        if (!$this->payments->contains($payment)) {
            $this->payments->add($payment);
            $payment->setMission($this);
        }

        return $this;
    }

    public function removePayment(Payment $payment): static
    {
        if ($this->payments->removeElement($payment)) {
            if ($payment->getMission() === $this) {
                $payment->setMission(null);
            }
        }

        return $this;
    }

    public function getType(): string
    {
        return $this instanceof ScientificMission ? 'scientific' : ($this instanceof TouristMission ? 'travel' : 'other');
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'destination' => $this->destination,
            'description' => $this->description,
            'date' => $this->date->format('Y-m-d H:i:s'),
            'seatPrice' => $this->seatPrice,
            'image' => $this->image,
            'duration' => $this->duration->format('H:i:s'),
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
            'type' => $this->getType(),
        ];
    }

    public function getOperator(): ?User
    {
        return $this->operator;
    }

    public function setOperator(?User $operator): self
    {
        $this->operator = $operator;
        return $this;
    }
}
