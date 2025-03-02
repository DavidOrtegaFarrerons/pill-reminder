<?php

namespace App\Subscriber\Pill;

use App\Entity\Pill;
use App\Event\PillCreatedEvent;
use App\Service\PillIntake\CreatePillIntakeService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PillCreatedSubscriber implements EventSubscriberInterface
{


    public function __construct(private readonly CreatePillIntakeService $createPillIntakeService)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            PillCreatedEvent::class => 'onPillCreated'
        ];
    }

    public function onPillCreated(PillCreatedEvent $event)
    {
        $pill = $event->getPill();
        $this->createPillIntakeService->createFirstPillIntakeLog($pill);
    }
}