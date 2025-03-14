<?php

namespace App\Service\PillIntake;

use App\Entity\PillIntake;
use App\Entity\User;
use App\Enum\PillIntakeStatus;
use App\Event\PillIntake\PillIntakeStatusAdjustedEvent;
use App\Event\PillIntake\PillIntakeStatusSkippedEvent;
use App\Event\PillIntake\PillIntakeStatusTakenEvent;
use App\Repository\PillIntakeRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use http\Exception\InvalidArgumentException;
use http\Exception\RuntimeException;
use http\Exception\UnexpectedValueException;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class TakePillIntakeService
{
    public function __construct(
        private readonly PillIntakeRepository   $repository,
        private readonly UpdatePillIntakeActualTimeService $updatePillIntakeActualTimeService,
        private readonly EventDispatcherInterface $eventDispatcher
    ) {}

    /**
     * @throws \Exception
     */
    public function take(User $user, array $formData, int $id) : void
    {
        $pillIntake = $this->repository->getById($id);

        if ($pillIntake->getPill()->getUser() !== $user) {
            throw new UnauthorizedHttpException('Bearer', 'The given pill is not from the given user');
        }

        if (!isset($formData['status'])) {
            throw new UnexpectedValueException("Status must be present in the data");
        }

        $status = PillIntakeStatus::tryFrom($formData['status']);

        if (!$status) {
            throw new InvalidArgumentException("Status is invalid");
        }

        $this->updatePillIntakeActualTimeService->update($pillIntake, $status);

        if ($pillIntake->isLastPillIntake()) {
            $pillIntake->setStatus(PillIntakeStatus::FINISHED);
            $this->repository->update();
            return;
        }

        $pillIntake->setStatus($status);
        $this->repository->update();

        match ($status) {
            PillIntakeStatus::TAKEN => $this->eventDispatcher->dispatch(new PillIntakeStatusTakenEvent($pillIntake)),
            PillIntakeStatus::ADJUSTED => $this->eventDispatcher->dispatch(new PillIntakeStatusAdjustedEvent($pillIntake)),
            PillIntakeStatus::SKIPPED => $this->eventDispatcher->dispatch(new PillIntakeStatusSkippedEvent($pillIntake)),
            default => throw new \Exception('Unexpected match value')
        };
    }
}