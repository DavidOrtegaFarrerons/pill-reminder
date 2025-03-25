<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\PillIntake\GetPillIntakeService;
use App\Service\PillIntake\TakePillIntakeService;
use http\Exception\InvalidArgumentException;
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
        private readonly TakePillIntakeService $takePillIntakeService,
        private readonly GetPillIntakeService $getPillIntakeService,
    )
    {
    }

    #[Route('/api/pill-intakes/{id}', 'update_pill_intake', methods: ['PUT'])]
    public function takeAction(#[CurrentUser] User $user, Request $request, int $id) : JsonResponse
    {
        $formData = json_decode($request->getContent(), true);

        try {
            $this->takePillIntakeService->take($user, $formData, $id);
        } catch (UnauthorizedHttpException $exception) {
            return $this->json(['message' => $exception->getMessage()], Response::HTTP_FORBIDDEN);
        } catch (UnexpectedValueException|InvalidArgumentException $exception) {
            return $this->json(['message' => $exception->getMessage()], Response::HTTP_NOT_ACCEPTABLE);
        }

        return $this->json([], Response::HTTP_NO_CONTENT);
    }

    #[Route('api/pill-intake', 'get_pill_intakes')]
    public function getAction(#[CurrentUser] User $user) : JsonResponse
    {
        $pillIntakes = $this->getPillIntakeService->get($user);
        return $this->json($pillIntakes, 200, [], ['groups' => 'pill_intake:list']);
    }
}