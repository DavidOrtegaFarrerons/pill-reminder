<?php

namespace App\Factory\Pill;

use App\Dto\Pill\PillDto;

class PillDtoFactory
{
    /**
     * @param array<string, mixed> $data
     */
    public function createFromArray(array $data): PillDto
    {
        return (new PillDto())
            ->setName($data['name'])
            ->setStartDate($data['startDate'])
            ->setStartTime($data['startTime'])
            ->setFrequency($data['frequency'])
            ->setDurationDays($data['durationDays'])
        ;
    }
}