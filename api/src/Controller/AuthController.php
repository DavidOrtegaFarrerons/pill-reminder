<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class AuthController extends AbstractController
{
    #[Route('/api/check-auth', 'check_auth', methods: ['GET'])]
    public function index(#[CurrentUser] ?User $user) : JsonResponse
    {

        if ($user !== null) {
            return $this->json(['isAuthenticated' => true]);
        }

        return $this->json(['isAuthenticated' => false]);
    }
}