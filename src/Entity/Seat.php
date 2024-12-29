<?php

namespace App\Entity;

use App\Enum\SeatType;
use App\Repository\SeatRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SeatRepository::class)]
class Seat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $number = null;

    #[ORM\Column]
    private ?bool $isReserved = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column(enumType: SeatType::class)]
    private ?SeatType $class = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): static
    {
        $this->number = $number;

        return $this;
    }

    public function isReserved(): ?bool
    {
        return $this->isReserved;
    }

    public function setReserved(bool $isReserved): static
    {
        $this->isReserved = $isReserved;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getClass(): ?SeatType
    {
        return $this->class;
    }

    public function setClass(SeatType $class): static
    {
        $this->class = $class;

        return $this;
    }
}
