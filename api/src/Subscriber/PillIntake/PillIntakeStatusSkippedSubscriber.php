<?php

namespace App\Subscriber\PillIntake;

use App\Entity\Pill;
use App\Event\PillCreatedEvent;
use App\Event\PillIntake\PillIntakeStatusSkippedEvent;
use App\Event\PillIntake\PillIntakeStatusTakenEvent;
use App\Service\PillIntake\CreatePillIntakeService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PillIntakeStatusSkippedSubscriber implements EventSubscriberInterface
{


    public function __construct(private readonly CreatePillIntakeService $createPillIntakeService)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            PillIntakeStatusSkippedEvent::class => 'onPillCreated'
        ];
    }

    public function onPillIntakeStatusSkipped(PillIntakeStatusSkippedEvent $event)
    {
        $pillIntake = $event->getPillIntake();
        $this->createPillIntakeService->createNewPillIntake($pillIntake);
    }
}