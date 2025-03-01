<?php

namespace App\Controller;

use App\Service\PillIntakeTakenService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class PilIntakeLogController extends AbstractController
{

    public function __construct(
        private readonly PillIntakeTakenService $pillIntakeLogTakenService
    )
    {
    }

    #[Route('/api/pill-intake/{id}', methods: ['PUT'])]
    public function takeAction(#[CurrentUser] $user, Request $request, int $id) : JsonResponse
    {
        return $this->pillIntakeLogTakenService->take($user, $request, $id);
    }
}