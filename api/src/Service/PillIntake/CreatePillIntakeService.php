<?php

namespace App\Service\PillIntake;

use App\Entity\Pill;
use App\Entity\PillIntake;
use App\Enum\PillIntakeStatus;
use Doctrine\ORM\EntityManagerInterface;

class CreatePillIntakeService
{

    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function createFirstPillIntakeLog(Pill $pill) : PillIntake
    {
        $pillIntakeLog = (new PillIntake())
            ->setPill($pill)
            ->setStatus(PillIntakeStatus::PENDING)
            ->setScheduledTime($pill->getStartDate())
            ->setActualTime(null)
        ;

        $this->entityManager->persist($pillIntakeLog);
        $this->entityManager->flush();

        return $pillIntakeLog;
    }
}