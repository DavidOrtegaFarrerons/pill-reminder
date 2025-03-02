<?php

namespace App\Event;

use App\Entity\Pill;
use Symfony\Contracts\EventDispatcher\Event;

class PillCreatedEvent extends Event
{
    public const NAME = 'pill.created';

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