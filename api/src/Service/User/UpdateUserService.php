<?php

namespace App\Service\User;

use App\Entity\User;
use App\Factory\Pill\UpdateUserDtoFactory;
use App\Mapper\User\UserMapper;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UpdateUserService
{

    public function __construct(
        private readonly UpdateUserDtoFactory $updateUserDtoFactory,
        private readonly ValidatorInterface $validator,
        private readonly UserMapper $userMapper,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly UserRepository $userRepository,
    )
    {
    }

    public function update(User $user, array $formData) : void
    {
        $dto = $this->updateUserDtoFactory->createFromArray($formData);

        $errors = $this->validator->validate($dto);
        if ($errors->count() > 0) {
            throw new ValidationFailedException("User form data is not correct", $errors);
        }

        $user = $this->userMapper->mapDtoToEntity($dto, $user);

        if ($dto->getPassword() !== null) {
            $user->setPassword($this->passwordHasher->hashPassword($user, $dto->getPassword()));
        }

        $this->userRepository->update();
    }
}