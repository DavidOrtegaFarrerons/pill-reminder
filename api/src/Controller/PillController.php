<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\PillRepository;
use App\Service\Pill\CreatePillService;
use App\Service\Pill\UpdatePillService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class PillController extends AbstractController
{

    public function __construct(
        private readonly CreatePillService $pillCreationService,
        private readonly UpdatePillService $pillUpdateService,
        private readonly PillRepository    $pillRepository,
    )
    {
    }

    #[Route('/api/pills', 'create_pill', methods: ['POST'])]
    public function createAction(#[CurrentUser] User $user, Request $request) : JsonResponse
    {
        $formData = json_decode($request->getContent(), true);
        $this->pillCreationService->create($user, $formData);

        return $this->json(
            [
                'pill' => true
            ]
        );
    }

    #[Route('/api/pills/{id}', 'update_pill', methods: ['PUT'])]
    public function updateAction(#[CurrentUser] $user, Request $request, $id) : JsonResponse
    {
        $formData = json_decode($request->getContent(), true);
        $this->pillUpdateService->update($user, $formData, $id);

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