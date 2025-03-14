<?php

namespace App\Controller;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class LoginController extends AbstractController
{

    const ONE_HOUR = 3600;

    public function __construct(
        private readonly JWTTokenManagerInterface $jwtManager
    )
    {
    }

    #[Route('/api/login', 'user_login', methods: ['POST'])]
    public function index(#[CurrentUser] ?User $user) : JsonResponse
    {

        if ($user === null) {
            return $this->json(['message' => 'Unauthorized'], 401);
        }
        $token = $this->jwtManager->create($user);

        $response = $this->json([
            'success' => true
        ]);

        $response->headers->setCookie(
            new Cookie(
                'jwt',
                $token,
                time() + self::ONE_HOUR,
                '/',
                '',
                false,
                true,
                false,
                Cookie::SAMESITE_LAX
            )
        );

        return $response;
    }
}