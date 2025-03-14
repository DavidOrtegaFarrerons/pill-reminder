<?php

namespace App\Service\PillIntake;

use App\Entity\Pill;
use App\Entity\PillIntake;
use App\Enum\PillIntakeStatus;
use App\Repository\PillIntakeRepository;
use App\Repository\PillRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class CreatePillIntakeService
{

    public function __construct(
        private readonly PillIntakeRepository $repository,
    )
    {
    }

    public function createFirstPillIntake(Pill $pill) : PillIntake
    {
        $pillIntake = (new PillIntake())
            ->setPill($pill)
            ->setStatus(PillIntakeStatus::PENDING)
            ->setScheduledTime($pill->getStartDate())
            ->setActualTime(null)
        ;

        $this->repository->save($pillIntake);

        return $pillIntake;
    }

    public function createNewPillIntake(?PillIntake $pillIntake) : void
    {
        $newPillIntake = (new PillIntake())
            ->setPill($pillIntake->getPill())
            ->setStatus(PillIntakeStatus::PENDING)
            ->setScheduledTime(
                (DateTime::createFromInterface($pillIntake->getScheduledTime()))->modify(
                    $pillIntake->getPill()->getFrequency()
                )
            )
        ;

        $this->repository->save($newPillIntake);
    }

    public function createAdjustedPillIntake(?PillIntake $pillIntake): void
    {
        $newPillIntake = (new PillIntake())
            ->setPill($pillIntake->getPill())
            ->setStatus(PillIntakeStatus::PENDING)
            ->setScheduledTime(
                (new \DateTime())->modify(
                    $pillIntake->getPill()->getFrequency()
                )
            )
        ;

        $diffTime = $pillIntake->getScheduledTime()->diff(new DateTime());
        $pillIntake->getPill()->setEndDate(DateTime::createFromInterface($pillIntake->getPill()->getEndDate())->add($diffTime));


        $this->repository->save($newPillIntake);
    }
}