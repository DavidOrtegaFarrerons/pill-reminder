<?php

namespace App\Service\PillIntake;

use App\Entity\PillIntake;
use App\Enum\PillIntakeStatus;
use App\Repository\PillIntakeRepository;
use Doctrine\ORM\EntityManagerInterface;

class UpdatePillIntakeActualTimeService
{

    public function __construct(private readonly PillIntakeRepository $repository)
    {
    }

    public function update(PillIntake $pillIntake, PillIntakeStatus $status) : void {
        if ($status === PillIntakeStatus::TAKEN) {
            $pillIntake->setActualTime($pillIntake->getScheduledTime());
        }

        if ($status === PillIntakeStatus::ADJUSTED) {
            $pillIntake->setActualTime(new \DateTime());
        }

        $this->repository->update();
    }
}