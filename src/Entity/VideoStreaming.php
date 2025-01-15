<?php

namespace App\Entity;

use App\Repository\VideoStreamingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VideoStreamingRepository::class)]
class VideoStreaming
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 2500)]
    private ?string $url = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'videoStreamings')]
    private ?self $Mission = null;

    /**
     * @var Collection<int, self>
     */
    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'Mission')]
    private Collection $videoStreamings;

    public function __construct()
    {
        $this->videoStreamings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): static
    {
        $this->endDate = $endDate;

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
    public function getVideoStreamings(): Collection
    {
        return $this->videoStreamings;
    }

    public function addVideoStreaming(self $videoStreaming): static
    {
        if (!$this->videoStreamings->contains($videoStreaming)) {
            $this->videoStreamings->add($videoStreaming);
            $videoStreaming->setMission($this);
        }

        return $this;
    }

    public function removeVideoStreaming(self $videoStreaming): static
    {
        if ($this->videoStreamings->removeElement($videoStreaming)) {
            // set the owning side to null (unless already changed)
            if ($videoStreaming->getMission() === $this) {
                $videoStreaming->setMission(null);
            }
        }

        return $this;
    }
}
