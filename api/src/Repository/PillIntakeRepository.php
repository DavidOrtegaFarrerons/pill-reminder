<?php

namespace App\Repository;

use App\Entity\PillIntake;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PillIntake>
 */
class PillIntakeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PillIntake::class);
    }

    public function getById(int $id) : ?PillIntake
    {
        return $this->createQueryBuilder('pil')
            ->andWhere('pil.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleResult()

        ;
    }

    //    /**
    //     * @return PillIntakeLog[] Returns an array of PillIntakeLog objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?PillIntakeLog
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
