<?php

namespace App\Repository;

use App\Entity\CodeReduc;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method CodeReduc|null find($id, $lockMode = null, $lockVersion = null)
 * @method CodeReduc|null findOneBy(array $criteria, array $orderBy = null)
 * @method CodeReduc[]    findAll()
 * @method CodeReduc[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CodeReducRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CodeReduc::class);
    }

    // /**
    //  * @return CodeReduc[] Returns an array of CodeReduc objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CodeReduc
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
