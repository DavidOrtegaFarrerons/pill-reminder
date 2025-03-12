<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\PillIntake\TakePillIntakeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class PilIntakeLogController extends AbstractController
{

    public function __construct(
        private readonly TakePillIntakeService $pillIntakeLogTakenService
    )
    {
    }

    #[Route('/api/pill-intake/{id}', methods: ['PUT'])]
    public function takeAction(#[CurrentUser] User $user, Request $request, int $id) : JsonResponse
    {
        return $this->pillIntakeLogTakenService->take($user, $request, $id);
    }
}