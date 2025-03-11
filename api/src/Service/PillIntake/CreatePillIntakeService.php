<?php

namespace App\Service\PillIntake;

use App\Entity\Pill;
use App\Entity\PillIntake;
use App\Enum\PillIntakeStatus;
use App\Repository\PillRepository;
use Doctrine\ORM\EntityManagerInterface;

class CreatePillIntakeService
{

    public function __construct(
        private readonly PillRepository $repository,
        private readonly EntityManagerInterface $entityManager
    )
    {
    }

    public function createFirstPillIntakeLog(Pill $pill) : PillIntake
    {
        $pillIntake = (new PillIntake())
            ->setPill($pill)
            ->setStatus(PillIntakeStatus::PENDING)
            ->setScheduledTime($pill->getStartDate())
            ->setActualTime(null)
        ;

        $this->repository->save($pillIntake);
        $this->entityManager->flush();

        return $pillIntake;
    }
}