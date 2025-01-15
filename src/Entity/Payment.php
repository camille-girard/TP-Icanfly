<?php

namespace App\Entity;

use App\Enum\PaymentStatus;
use App\Enum\PaymentType;
use App\Repository\PaymentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaymentRepository::class)]
class Payment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $totalPrice = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $paymentDate = null;

    #[ORM\Column(enumType: PaymentStatus::class)]
    private ?PaymentStatus $status = null;

    #[ORM\Column(enumType: PaymentType::class)]
    private ?PaymentType $paymentType = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'payments')]
    private ?self $Mission = null;

    /**
     * @var Collection<int, self>
     */
    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'Mission')]
    private Collection $payments;

    public function __construct()
    {
        $this->paymentDate = new \DateTimeImmutable();
        $this->payments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTotalPrice(): ?int
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(int $totalPrice): static
    {
        $this->totalPrice = $totalPrice;

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

    public function getStatus(): ?PaymentStatus
    {
        return $this->status;
    }

    public function setStatus(PaymentStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getPaymentType(): ?PaymentType
    {
        return $this->paymentType;
    }

    public function setPaymentType(PaymentType $paymentType): static
    {
        $this->paymentType = $paymentType;

        return $this;
    }

    public function getMission(): ?self
    {
        return $this->Mission;
    }

    public function setMission(?self $Mission): static
    {
        $this->Mission = $Mission;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getPayments(): Collection
    {
        return $this->payments;
    }

    public function addPayment(self $payment): static
    {
        if (!$this->payments->contains($payment)) {
            $this->payments->add($payment);
            $payment->setMission($this);
        }

        return $this;
    }

    public function removePayment(self $payment): static
    {
        if ($this->payments->removeElement($payment)) {
            // set the owning side to null (unless already changed)
            if ($payment->getMission() === $this) {
                $payment->setMission(null);
            }
        }

        return $this;
    }
}
