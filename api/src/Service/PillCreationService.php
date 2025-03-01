<?php

namespace App\Service;

use App\Entity\Pill;
use App\Entity\User;
use App\Repository\PillRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

class PillCreationService
{
    public function __construct(
        private readonly SerializerInterface       $serializer,
        private readonly PillIntakeCreationService $intakeLogCreationService,
        private readonly EntityManagerInterface    $entityManager
    )
    {
    }

    public function create(User $user, Request $request) : ?Pill
    {

        $formData = json_decode($request->getContent(), true);

        /** @var Pill $pill */
        $pill = $this->serializer->denormalize($formData, Pill::class);
        $pill->setUser($user);

        try {
            $this->entityManager->beginTransaction();
            $this->entityManager->persist($pill);
            $this->entityManager->flush();
            $this->intakeLogCreationService->createFirstPillIntakeLog($pill);
            $this->entityManager->commit();
        } catch (\Exception $exception) {
            return null;
        }

        return $pill;
    }
}