<?php

namespace App\Service\PillIntake;

use App\Entity\Pill;
use App\Repository\PillIntakeRepository;

class InvalidPillIntakeRemoverService
{

    public function __construct(private readonly PillIntakeRepository $repository)
    {
    }

    public function removeInvalidIntakes(Pill $pill) : void {
        $this->repository->removeInvalidIntakes($pill);
    }
}