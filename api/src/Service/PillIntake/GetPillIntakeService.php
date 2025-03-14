<?php

namespace App\Service\PillIntake;

use App\Entity\User;
use App\Repository\PillIntakeRepository;

class GetPillIntakeService
{

    public function __construct(private readonly PillIntakeRepository $repository)
    {
    }

    public function get(User $user) : array
    {
        return $this->repository->getHistory($user);
    }
}