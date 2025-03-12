<?php

namespace App\Repository;

use App\Entity\PillIntake;
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
            ->getSingleResult()

        ;
    }
}
