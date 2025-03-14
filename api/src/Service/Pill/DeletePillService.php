<?php

namespace App\Service\Pill;

use App\Entity\User;
use App\Repository\PillRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class DeletePillService
{

    public function __construct(
        private readonly PillRepository $repository
    )
    {
    }

    public function delete(User $user, int $id) : void
    {
        $pill = $this->repository->getById($id);

        if (!$pill) {
            throw new NotFoundHttpException('Pill not found');
        }

        if ($pill->getUser()->getId() !== $user->getId()) {
            throw new UnauthorizedHttpException('Bearer', 'The user does not have access to this resource');
        }

        $this->repository->delete($pill);
    }
}