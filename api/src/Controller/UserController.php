<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\User\UpdateUserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class UserController extends AbstractController
{

    public function __construct(
        private readonly UpdateUserService $updateUserService
    )
    {
    }

    #[Route('/api/user', 'get_user', methods: ['GET'])]
    public function getUserAction(#[CurrentUser] User $user) : JsonResponse
    {
        return $this->json(
            [
                'name' => $user->getName(),
                'email' => $user->getEmail(),
            ]
        );
    }

    #[Route('/api/user', 'update_user', methods: ['PUT'])]
    public function updateUserAction(#[CurrentUser] User $user, Request $request) : JsonResponse
    {
        $formData = json_decode($request->getContent(), true);
        $this->updateUserService->update($user, $formData);

        return $this->json([], Response::HTTP_NO_CONTENT);
    }
}