<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class UserLoginController extends AbstractController
{
    #[Route('/api/login', 'user_login', methods: ['POST'])]
    public function index(#[CurrentUser] $user) : JsonResponse
    {
        return $this->json([
            'success' => true
        ]);
    }
}