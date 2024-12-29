<?php

namespace App\Entity;

use App\Enum\PaymentMethodType;
use App\Enum\PaymentStatusType;
use App\Repository\PaymentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaymentRepository::class)]
class Payment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $amount = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $paymentDate = null;

    #[ORM\Column(enumType: PaymentStatusType::class)]
    private ?PaymentStatusType $status = null;

    #[ORM\Column(enumType: PaymentMethodType::class)]
    private ?PaymentMethodType $paymentMethod = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getPaymentDate(): ?\DateTimeImmutable
    {
        return $this->paymentDate;
    }

    public function setPaymentDate(\DateTimeImmutable $paymentDate): static
    {
        $this->paymentDate = $paymentDate;

        return $this;
    }

    public function getStatus(): ?PaymentStatusType
    {
        return $this->status;
    }

    public function setStatus(PaymentStatusType $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getPaymentMethod(): ?PaymentMethodType
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod(PaymentMethodType $paymentMethod): static
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }
}
