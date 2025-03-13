<?php

namespace App\Subscriber\PillIntake;

use App\Entity\Pill;
use App\Event\PillCreatedEvent;
use App\Event\PillIntake\PillIntakeStatusAdjustedEvent;
use App\Event\PillIntake\PillIntakeStatusTakenEvent;
use App\Service\PillIntake\CreatePillIntakeService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PillIntakeStatusAdjustedSubscriber implements EventSubscriberInterface
{


    public function __construct(private readonly CreatePillIntakeService $createPillIntakeService)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            PillIntakeStatusAdjustedEvent::class => 'onPillIntakeStatusAdjusted'
        ];
    }

    public function onPillIntakeStatusAdjusted(PillIntakeStatusAdjustedEvent $event)
    {
        $pillIntake = $event->getPillIntake();
        $this->createPillIntakeService->createAdjustedPillIntake($pillIntake);
    }
}