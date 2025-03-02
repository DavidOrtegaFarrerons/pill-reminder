<?php

namespace App\Dto\Pill;

use Symfony\Component\Validator\Constraints as Assert;

class PillDto
{
    #[Assert\NotBlank]
    private string $name;
    #[Assert\NotBlank]
    private string $startDate;
    #[Assert\NotBlank]
    private string $startTime;
    #[Assert\NotBlank]
    private string $frequency;
    #[Assert\NotBlank]
    private int $durationDays;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return PillDto
     */
    public function setName(string $name): PillDto
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getStartDate(): string
    {
        return $this->startDate;
    }

    /**
     * @param string $startDate
     * @return PillDto
     */
    public function setStartDate(string $startDate): PillDto
    {
        $this->startDate = $startDate;
        return $this;
    }

    /**
     * @return string
     */
    public function getStartTime(): string
    {
        return $this->startTime;
    }

    /**
     * @param string $startTime
     * @return PillDto
     */
    public function setStartTime(string $startTime): PillDto
    {
        $this->startTime = $startTime;
        return $this;
    }

    /**
     * @return string
     */
    public function getFrequency(): string
    {
        return $this->frequency;
    }

    /**
     * @param string $frequency
     * @return PillDto
     */
    public function setFrequency(string $frequency): PillDto
    {
        $this->frequency = $frequency;
        return $this;
    }

    /**
     * @return int
     */
    public function getDurationDays(): int
    {
        return $this->durationDays;
    }

    /**
     * @param int $durationDays
     * @return PillDto
     */
    public function setDurationDays(int $durationDays): PillDto
    {
        $this->durationDays = $durationDays;
        return $this;
    }
}