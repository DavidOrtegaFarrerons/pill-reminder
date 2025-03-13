<?php

namespace App\Subscriber\PillIntake;

use App\Entity\Pill;
use App\Event\PillCreatedEvent;
use App\Event\PillIntake\PillIntakeStatusTakenEvent;
use App\Service\PillIntake\CreatePillIntakeService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PillIntakeStatusTakenSubscriber implements EventSubscriberInterface
{


    public function __construct(private readonly CreatePillIntakeService $createPillIntakeService)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            PillIntakeStatusTakenEvent::class => 'onPillIntakeStatusTaken'
        ];
    }

    public function onPillIntakeStatusTaken(PillIntakeStatusTakenEvent $event)
    {
        $pillIntake = $event->getPillIntake();
        $this->createPillIntakeService->createNewPillIntake($pillIntake);

    }
}