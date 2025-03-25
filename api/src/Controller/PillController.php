<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\PillRepository;
use App\Service\Pill\CreatePillService;
use App\Service\Pill\DeletePillService;
use App\Service\Pill\UpdatePillService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class PillController extends AbstractController
{

    public function __construct(
        private readonly CreatePillService $pillCreationService,
        private readonly UpdatePillService $pillUpdateService,
        private readonly DeletePillService $deletePillService,
        private readonly PillRepository    $pillRepository,
    )
    {
    }

    #[Route('/api/pills', 'create_pill', methods: ['POST'])]
    public function createAction(#[CurrentUser] User $user, Request $request) : JsonResponse
    {
        $formData = json_decode($request->getContent(), true);
        $pill = $this->pillCreationService->create($user, $formData);

        return $this->json(
            [
                'pill' => $pill
            ]
        );
    }

    #[Route('/api/pills/{id}', 'update_pill', methods: ['PUT'])]
    public function updateAction(#[CurrentUser] User $user, Request $request, int $id) : JsonResponse
    {
        $formData = json_decode($request->getContent(), true);
        $this->pillUpdateService->update($user, $formData, $id);

        return $this->json([], Response::HTTP_NO_CONTENT);
    }

    #[Route('api/pills/{id}', 'delete_pill', methods: ['DELETE'])]
    public function deleteAction(#[CurrentUser] User $user, Request $request, int $id) : JsonResponse
    {
        try {
            $this->deletePillService->delete($user, $id);
        } catch (HttpException $exception) {
            return $this->json(
                [
                    'message' => $exception->getMessage()
                ], Response::HTTP_NOT_ACCEPTABLE
            );
        }

        return $this->json([], Response::HTTP_NO_CONTENT);
    }

    #[Route('/api/pills', 'get_all_pills', methods: ['GET'])]
    public function getAllAction(#[CurrentUser] User $user) : JsonResponse
    {
        $pills = $this->pillRepository->getAllByUser($user);

        return $this->json(
            [
                'pills' => $pills
            ],
        );
    }
}