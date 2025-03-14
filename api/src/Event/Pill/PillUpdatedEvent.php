<?php

namespace App\Event\Pill;

use App\Entity\Pill;
use Symfony\Contracts\EventDispatcher\Event;

class PillUpdatedEvent extends Event
{
    public const NAME = 'pill.updated';

    public function __construct(private readonly Pill $pill)
    {
    }

    /**
     * @return Pill
     */
    public function getPill(): Pill
    {
        return $this->pill;
    }
}