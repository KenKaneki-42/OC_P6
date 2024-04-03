<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\ProfilPicture;

/**
 * @extends ServiceEntityRepository<ProfilPicture>
 *
 * @method ProfilPicture|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProfilPicture|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProfilPicture[]    findAll()
 * @method ProfilPicture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */

class ProfilPictureRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, ProfilPicture::class);
  }

  //    /**
  //     * @return Image[] Returns an array of Image objects
  //     */
  /*
    public function findByExampleField($value): array
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

  /*
    public function findOneBySomeField($value): ?Image
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
