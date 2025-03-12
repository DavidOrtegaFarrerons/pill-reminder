<?php

namespace App\Mapper\User;

use App\Dto\User\UpdateUserDto;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

class UserMapper
{
    public function mapDtoToEntity(UpdateUserDto $dto, User $user) : User
    {
        if (!empty($dto->getName())) {
            $user->setName($dto->getName());
        }

        if (!empty($dto->getPassword())) {
            $user->setPassword($dto->getPassword());
        }

        return $user;
    }
}