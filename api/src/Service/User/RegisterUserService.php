<?php

namespace App\Service\User;

use App\Dto\User\RegisterUserDto;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegisterUserService
{

    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly UserRepository $repository,
        private readonly EntityManagerInterface $entityManager,
    )
    {
    }

    public function registerUser(array $data) : JsonResponse
    {
        $dto = $this->serializer->denormalize($data, RegisterUserDto::class);

        if (!$this->isValid($dto)) {
            return new JsonResponse(['message' => 'error when validating user', 'success' => false], 400);
        }

        try {
            $this->register($dto);
        } catch (\Exception $exception) {
            return new JsonResponse(['message' => 'There has been an internal server error'], 500);
        }


        return new JsonResponse(['message' => 'Registration successful', 'success' => true], 201);
    }

    private function isValid(RegisterUserDto $dto) : bool
    {
        return $this->validator->validate($dto)->count() === 0;
    }

    private function register(RegisterUserDto $dto) : void
    {
        $user = (new User())
            ->setEmail($dto->getEmail())
            ->setName($dto->getName())
        ;

        $user->setPassword($this->passwordHasher->hashPassword($user, $dto->getPassword()));

        $this->repository->save($user);
        $this->entityManager->flush();
    }
}