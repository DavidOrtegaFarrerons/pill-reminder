<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class AuthCheck
{
    #[Route('/api/healthcheck', 'healthcheck', methods: ['GET'])]
    public function index(#[CurrentUser] $user) : JsonResponse
    {
        dd($user);
    }
}