<?php

namespace App\Entity;

use App\Repository\PillRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PillRepository::class)]
class Pill
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(length: 255)]
    private ?string $frequency = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\ManyToOne(inversedBy: 'pills')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    /**
     * @var Collection<int, PillIntake>
     */
    #[ORM\OneToMany(targetEntity: PillIntake::class, mappedBy: 'pill', orphanRemoval: true)]
    private Collection $pillIntakeLogs;

    public function __construct()
    {
        $this->pillIntakeLogs = new ArrayCollection();
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

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getFrequency(): ?string
    {
        return $this->frequency;
    }

    public function setFrequency(string $frequency): static
    {
        $this->frequency = $frequency;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, PillIntake>
     */
    public function getPillIntakeLogs(): Collection
    {
        return $this->pillIntakeLogs;
    }

    public function addPillIntakeLog(PillIntake $pillIntakeLog): static
    {
        if (!$this->pillIntakeLogs->contains($pillIntakeLog)) {
            $this->pillIntakeLogs->add($pillIntakeLog);
            $pillIntakeLog->setPill($this);
        }

        return $this;
    }

    public function removePillIntakeLog(PillIntake $pillIntakeLog): static
    {
        if ($this->pillIntakeLogs->removeElement($pillIntakeLog)) {
            // set the owning side to null (unless already changed)
            if ($pillIntakeLog->getPill() === $this) {
                $pillIntakeLog->setPill(null);
            }
        }

        return $this;
    }
}
