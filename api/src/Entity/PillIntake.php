<?php

namespace App\Entity;

use App\Enum\PillIntakeStatus;
use App\Repository\PillIntakeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PillIntakeRepository::class)]
#[ORM\HasLifecycleCallbacks]
class PillIntake
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'pillIntakeLogs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Pill $pill = null;

    #[ORM\Column(enumType: PillIntakeStatus::class)]
    private ?PillIntakeStatus $status = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $scheduledTime = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $actualTime = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    public function isLastPillIntake() : bool {
        $nextPillIntakeTime = $this->getScheduledTime()->modify($this->getPill()->getFrequency());
        return $nextPillIntakeTime >= $this->getPill()->getEndDate();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPill(): ?Pill
    {
        return $this->pill;
    }

    public function setPill(?Pill $pill): static
    {
        $this->pill = $pill;

        return $this;
    }

    public function getStatus(): ?PillIntakeStatus
    {
        return $this->status;
    }

    public function setStatus(?PillIntakeStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getScheduledTime(): ?\DateTimeInterface
    {
        return $this->scheduledTime;
    }

    public function setScheduledTime(\DateTimeInterface $scheduledTime): static
    {
        $this->scheduledTime = $scheduledTime;

        return $this;
    }

    public function getActualTime(): ?\DateTimeInterface
    {
        return $this->actualTime;
    }

    public function setActualTime(?\DateTimeInterface $actualTime): static
    {
        $this->actualTime = $actualTime;

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

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }
}
