<?php

namespace App\Controller;

use App\Service\UserRegistrationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class UserRegistrationController extends AbstractController
{

    public function __construct(private readonly UserRegistrationService $userRegistrationService)
    {
    }

    #[Route('/register', 'user_registration', methods: ['POST'])]
    public function register(
        Request $request,

    ) : JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        return $this->userRegistrationService->registerUser($data);
    }
}