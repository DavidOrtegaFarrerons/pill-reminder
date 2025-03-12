<?php

namespace App\Factory\Pill;

use App\Dto\User\UpdateUserDto;

class UpdateUserDtoFactory
{
    /**
     * @param array<string, mixed> $data
     */
    public function createFromArray(array $data) : UpdateUserDto
    {
        return (new UpdateUserDto())
            ->setName($data['name'] ?? null)
            ->setPassword($data['password'] ?? null)
        ;
    }
}