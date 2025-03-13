<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\PillIntake\TakePillIntakeService;
use http\Exception\UnexpectedValueException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class PilIntakeController extends AbstractController
{

    public function __construct(
        private readonly TakePillIntakeService $takePillIntakeService
    )
    {
    }

    #[Route('/api/pill-intake/{id}', methods: ['PUT'])]
    public function takeAction(#[CurrentUser] User $user, Request $request, int $id) : JsonResponse
    {
        $formData = json_decode($request->getContent(), true);

        try {
            $this->takePillIntakeService->take($user, $formData, $id);
        } catch (UnauthorizedHttpException $exception) {
            return $this->json(['message' => $exception->getMessage()], Response::HTTP_FORBIDDEN);
        } catch (UnexpectedValueException $exception) {
            return $this->json(['message' => $exception->getMessage()], Response::HTTP_NOT_ACCEPTABLE);
        }

        return $this->json([], Response::HTTP_ACCEPTED);
    }
}