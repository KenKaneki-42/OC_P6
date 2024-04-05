<?php

namespace App\Repository;

use App\Entity\TrickCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TrickCategory>
 *
 * @method TrickCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrickCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrickCategory[]    findAll()
 * @method TrickCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrickCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrickCategory::class);
    }

    //    /**
    //     * @return TrickCategory[] Returns an array of TrickCategory objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?TrickCategory
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
