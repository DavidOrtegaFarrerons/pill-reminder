<?php

namespace App\Repository;

use App\Entity\Pill;
use App\Entity\PillIntake;
use App\Entity\User;
use App\Enum\PillIntakeStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends BaseRepository<PillIntake>
 */
class PillIntakeRepository extends BaseRepository implements RepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PillIntake::class);
    }

    public function getById(int $id) : ?PillIntake
    {
        return $this->createQueryBuilder('pi')
            ->andWhere('pi.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult()

        ;
    }

    public function getHistory(User $user): array
    {
        return $this->createQueryBuilder('pi')
            ->select('pi.id, pi.actualTime, pi.status, p.id as pillId, p.name as pillName, p.frequency')
            ->leftJoin('pi.pill', 'p')
            ->where('p.user = :user')
            ->andWhere('pi.status != :status')
            ->setParameter('user', $user->getId()->toBinary())
            ->setParameter('status', PillIntakeStatus::PENDING)
            ->orderBy('pi.actualTime', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function removeInvalidIntakes(Pill $pill) : void
    {
        $this->createQueryBuilder('pi')
            ->delete('App\Entity\PillIntake', 'pi')
            ->where('pi.pill = :pill')
            ->andWhere('pi.scheduledTime > :endDate')
            ->setParameter('pill', $pill)
            ->setParameter('endDate', $pill->getEndDate())
            ->getQuery()
            ->execute()
        ;
    }
}
