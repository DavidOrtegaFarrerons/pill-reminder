<?php

namespace App\Repository;

use App\Entity\Pill;
use App\Entity\User;
use App\Enum\PillIntakeStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Pill>
 */
class PillRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pill::class);
    }

    /**
     * @param User $user
     * @return Pill[] Returns all Pills from a user
     *
     */
    public function getAllByUser(User $user) : array
    {
        return $this->createQueryBuilder('p')
            ->select('p.id, p.name, p.startDate, p.frequency, p.endDate, pil.id as intakeId, pil.status as taken, pil.scheduledTime as nextPillTime')
            ->leftJoin('p.pillIntakeLogs', 'pil')
            ->andWhere('p.user = :user')
            ->andWhere('pil.status = :pending_status')
            ->setParameter('user', $user->getId()->toBinary())
            ->setParameter('pending_status', PillIntakeStatus::PENDING)
            ->getQuery()
            ->getResult()
        ;
    }
}
