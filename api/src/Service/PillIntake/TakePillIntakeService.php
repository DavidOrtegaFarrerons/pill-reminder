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
            if (
                DateTime::createFromInterface($pillIntakeLog->getScheduledTime())->modify($pillIntakeLog->getPill()->getFrequency()) >= $pillIntakeLog->getPill()->getEndDate()
            ) {
                $pillIntakeLog->setStatus(PillIntakeStatus::FINISHED);
                $this->entityManager->persist($pillIntakeLog);
                $this->entityManager->flush();
                return new JsonResponse(['success' => true], 201);
            }
        }

        $pillIntakeLog->setStatus($status);

        $this->entityManager->persist($pillIntakeLog);
        $this->entityManager->flush();

        match (true) {
            $status === PillIntakeStatus::TAKEN || $status === PillIntakeStatus::SKIPPED => $this->generateNewPillIntakeLog($pillIntakeLog),
            $status === PillIntakeStatus::ADJUSTED => $this->generateAdjustedPillIntakeLog($pillIntakeLog),
            default => throw new \Exception('Unexpected match value')
        };

        return new JsonResponse(['success' => true], 201);
    }

    private function generateNewPillIntakeLog(?PillIntake $pillIntakeLog) : void
    {
        //I should modify the end date of the Pill itself in case we need to adjust the intakes
        $newPillIntakeLog = (new PillIntake())
            ->setPill($pillIntakeLog->getPill())
            ->setStatus(PillIntakeStatus::PENDING)
            ->setScheduledTime(
                (DateTime::createFromInterface($pillIntakeLog->getScheduledTime()))->modify(
                    $pillIntakeLog->getPill()->getFrequency()
                )
            )
        ;


        $this->entityManager->persist($newPillIntakeLog);
        $this->entityManager->flush();
    }

    private function generateAdjustedPillIntakeLog(?PillIntake $pillIntakeLog): void
    {
        $newPillIntakeLog = (new PillIntake())
            ->setPill($pillIntakeLog->getPill())
            ->setStatus(PillIntakeStatus::PENDING)
            ->setScheduledTime(
                (new \DateTime())->modify(
                    $pillIntakeLog->getPill()->getFrequency()
                )
            )
        ;


        $this->entityManager->persist($newPillIntakeLog);
        $this->entityManager->flush();
    }
}