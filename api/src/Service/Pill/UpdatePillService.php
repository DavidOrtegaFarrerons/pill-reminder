<?php

namespace App\Service\Pill;

use App\Entity\Pill;
use App\Entity\User;
use App\Factory\Pill\PillDtoFactory;
use App\Mapper\Pill\PillMapper;
use App\Repository\PillRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UpdatePillService
{


    public function __construct(
        private readonly PillRepository         $repository,
        private readonly PillDtoFactory         $factory,
        private readonly ValidatorInterface     $validator,
        private readonly PillMapper             $mapper,
    )
    {
    }

    public function update(User $user, array $formData, int $id) : ?Pill {
        $pill = $this->repository->getById($id);

        if (!$pill) {
            throw new NotFoundHttpException('Pill not found');
        }

        if ($pill->getUser() !== $user) {
            throw new UnauthorizedHttpException('Bearer', 'You do not have access to this pill');
        }

        $dto = $this->factory->createFromArray($formData);
        $errors = $this->validator->validate($dto);

        if ($errors->count() > 0) {
            throw new ValidationFailedException("Pill form data is not correct", $errors);
        }

        $pill = $this->mapper->mapDtoToEntity($dto, $pill);

        $this->entityManager->flush();

        return $pill;
    }
}