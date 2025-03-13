<?php

namespace App\Service\PillIntake;

use App\Entity\PillIntake;
use App\Enum\PillIntakeStatus;
use Doctrine\ORM\EntityManagerInterface;

class UpdatePillIntakeActualTimeService
{

    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function update(PillIntake $pillIntake, PillIntakeStatus $status) : void {
        if ($status === PillIntakeStatus::TAKEN) {
            $pillIntake->setActualTime($pillIntake->getScheduledTime());
        }

        if ($status === PillIntakeStatus::ADJUSTED) {
            $pillIntake->setActualTime(new \DateTime());
        }

        $this->entityManager->flush();
    }
}