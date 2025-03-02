<?php

namespace App\Controller;

use App\Service\RegisterUserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class RegisterUserController extends AbstractController
{

    public function __construct(private readonly RegisterUserService $userRegistrationService)
    {
    }

    #[Route('/api/register', 'user_registration', methods: ['POST'])]
    public function register(
        Request $request,

    ) : JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        return $this->userRegistrationService->registerUser($data);
    }
}