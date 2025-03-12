<?php

namespace App\Service\PillIntake;

use App\Entity\PillIntake;
use App\Entity\User;
use App\Enum\PillIntakeStatus;
use App\Repository\PillIntakeRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TakePillIntakeService
{

    public function __construct(
        private readonly PillIntakeRepository   $repository,
        private readonly EntityManagerInterface $entityManager
    )
    {
    }

    public function take(User $user, Request $request, int $id) : JsonResponse
    {
        $pillIntakeLog = $this->repository->getById($id);

        if ($pillIntakeLog->getPill()->getUser() !== $user) {
            return new JsonResponse([], 403);
        }

        $formData = json_decode($request->getContent(), true);

        $status = PillIntakeStatus::tryFrom($formData['status']);

        if ($status === PillIntakeStatus::TAKEN) {
            $nextPillIntakeTime = DateTime::createFromInterface($pillIntakeLog->getScheduledTime())->modify($pillIntakeLog->getPill()->getFrequency());
            if (
                $nextPillIntakeTime >= $pillIntakeLog->getPill()->getEndDate()
            ) {
                $pillIntakeLog->setStatus(PillIntakeStatus::FINISHED);
                $this->entityManager->flush();
                return new JsonResponse(['success' => true], 201);
            }
        }

        $pillIntakeLog->setStatus($status);
        $this->entityManager->flush();

        match (true) {
            $status === PillIntakeStatus::TAKEN || $status === PillIntakeStatus::SKIPPED => $this->generateNewPillIntake($pillIntakeLog),
            $status === PillIntakeStatus::ADJUSTED => $this->generateAdjustedPillIntake($pillIntakeLog),
            default => throw new \Exception('Unexpected match value')
        };

        return new JsonResponse(['success' => true], 201);
    }

    private function generateNewPillIntake(?PillIntake $pillIntake) : void
    {
        $newPillIntakeLog = (new PillIntake())
            ->setPill($pillIntake->getPill())
            ->setStatus(PillIntakeStatus::PENDING)
            ->setScheduledTime(
                (DateTime::createFromInterface($pillIntake->getScheduledTime()))->modify(
                    $pillIntake->getPill()->getFrequency()
                )
            )
        ;

        $this->repository->save($newPillIntakeLog);
        $this->entityManager->flush();
    }

    private function generateAdjustedPillIntake(?PillIntake $pillIntake): void
    {
        $newPillIntakeLog = (new PillIntake())
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


        $this->repository->save($newPillIntakeLog);
        $this->entityManager->flush();
    }
}