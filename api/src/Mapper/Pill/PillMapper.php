<?php

namespace App\Mapper\Pill;

use App\Dto\Pill\PillDto;
use App\Entity\Pill;
use App\Helper\DateTimeHelper;

class PillMapper
{
    private const DAY_MODIFIER = 'days';

    public function mapDtoToEntity(PillDto $dto, Pill $pill): Pill
    {
        return $pill
            ->setName($dto->getName())
            ->setStartDate(
                DateTimeHelper::dateTimeFromStrings(
                    $dto->getStartDate(), $dto->getStartTime()
                )
            )
            ->setFrequency($dto->getFrequency())
            ->setEndDate(\DateTime::createFromInterface($pill->getStartDate())->modify($dto->getDurationDays() . self::DAY_MODIFIER))
        ;
    }
}