<?php

namespace App\Repository;

use App\Entity\SantaList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SantaList|null find($id, $lockMode = null, $lockVersion = null)
 * @method SantaList|null findOneBy(array $criteria, array $orderBy = null)
 * @method SantaList[]    findAll()
 * @method SantaList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SantaListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SantaList::class);
    }

    // /**
    //  * @return SantaList[] Returns an array of SantaList objects
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
    public function findOneBySomeField($value): ?SantaList
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
