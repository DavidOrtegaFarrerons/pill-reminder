<?php

namespace App\Event\PillIntake;

use App\Entity\PillIntake;
use Symfony\Contracts\EventDispatcher\Event;

class PillIntakeStatusSkippedEvent extends Event
{
    public const NAME = 'pillIntake.status.skipped';

    public function __construct(private readonly PillIntake $pillIntake)
    {
    }

    /**
     * @return PillIntake
     */
    public function getPillIntake(): PillIntake
    {
        return $this->pillIntake;
    }

}