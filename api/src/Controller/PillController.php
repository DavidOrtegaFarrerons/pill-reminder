<?php

namespace App\Controller;

use App\Entity\Pill;
use App\Entity\User;
use App\Repository\PillRepository;
use App\Service\PillCreationService;
use App\Service\PillIntakeCreationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Serializer\SerializerInterface;

class PillController extends AbstractController
{

    public function __construct(
        private readonly PillCreationService $pillCreationService,
        private readonly PillRepository $pillRepository,
    )
    {
    }

    #[Route('/api/pills', 'create_pill', methods: ['POST'])]
    public function createAction(#[CurrentUser] User $user, Request $request) : JsonResponse
    {
        $pill = $this->pillCreationService->create($user, $request);

        return $this->json(
            [
                'pill' => true
            ]
        );
    }

    #[Route('/api/pills', 'get_all_pills', methods: ['GET'])]
    public function getAllAction(#[CurrentUser] User $user, Request $request, EntityManagerInterface $entityManager) : JsonResponse
    {
        return $this->json(
            [
                'pills' => $this->pillRepository->getAllByUser($user)
            ],
        );
    }
}