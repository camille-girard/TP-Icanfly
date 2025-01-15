<?php

namespace App\Entity;

use App\Repository\NotificationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NotificationRepository::class)]
class Notification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $content = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $sentDate = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'notifications')]
    private ?self $Customer = null;

    /**
     * @var Collection<int, self>
     */
    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'Customer')]
    private Collection $notifications;

    public function __construct()
    {
        $this->notifications = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getSentDate(): ?\DateTimeInterface
    {
        return $this->sentDate;
    }

    public function setSentDate(\DateTimeInterface $sentDate): static
    {
        $this->sentDate = $sentDate;

        return $this;
    }

    public function getCustomer(): ?self
    {
        return $this->Customer;
    }

    public function setCustomer(?self $Customer): static
    {
        $this->Customer = $Customer;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(self $notification): static
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications->add($notification);
            $notification->setCustomer($this);
        }

        return $this;
    }

    public function removeNotification(self $notification): static
    {
        if ($this->notifications->removeElement($notification)) {
            // set the owning side to null (unless already changed)
            if ($notification->getCustomer() === $this) {
                $notification->setCustomer(null);
            }
        }

        return $this;
    }
}
