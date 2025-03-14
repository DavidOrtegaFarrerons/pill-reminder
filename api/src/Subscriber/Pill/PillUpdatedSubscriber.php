<?php

namespace App\Subscriber\Pill;

use App\Event\Pill\PillUpdatedEvent;
use App\Service\PillIntake\CreatePillIntakeService;
use App\Service\PillIntake\InvalidPillIntakeRemoverService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PillUpdatedSubscriber implements EventSubscriberInterface
{


    public function __construct(private readonly InvalidPillIntakeRemoverService $invalidPillIntakeRemoverService)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            PillUpdatedEvent::class => 'onPillUpdated'
        ];
    }

    public function onPillUpdated(PillUpdatedEvent $event)
    {
        $pill = $event->getPill();
        $this->invalidPillIntakeRemoverService->removeInvalidIntakes($pill);
    }
}