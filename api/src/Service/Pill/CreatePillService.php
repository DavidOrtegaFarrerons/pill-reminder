<?php

namespace App\Service\Pill;

use App\Entity\Pill;
use App\Entity\User;
use App\Event\Pill\PillCreatedEvent;
use App\Factory\Pill\PillDtoFactory;
use App\Mapper\Pill\PillMapper;
use App\Repository\PillRepository;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreatePillService
{
    public function __construct(
        private readonly PillDtoFactory $factory,
        private readonly ValidatorInterface $validator,
        private readonly PillMapper $mapper,
        private readonly PillRepository $repository,
        private readonly EventDispatcherInterface $eventDispatcher
    )
    {
    }

    public function create(User $user, array $formData) : Pill
    {
        $pillDto = $this->factory->createFromArray($formData);
        $errors = $this->validator->validate($pillDto);

        if ($errors->count() > 0) {
            throw new ValidationFailedException("Pill form data is not correct", $errors);
        }

        $pill = $this->mapper->mapDtoToEntity($pillDto, new Pill());

        $pill->setUser($user);
        $this->repository->save($pill);

        $this->eventDispatcher->dispatch(new PillCreatedEvent($pill));

        return $pill;
    }
}