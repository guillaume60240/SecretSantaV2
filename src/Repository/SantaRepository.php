<?php

namespace App\Repository;

use App\Entity\Santa;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Santa|null find($id, $lockMode = null, $lockVersion = null)
 * @method Santa|null findOneBy(array $criteria, array $orderBy = null)
 * @method Santa[]    findAll()
 * @method Santa[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SantaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Santa::class);
    }

    // /**
    //  * @return Santa[] Returns an array of Santa objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Santa
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
