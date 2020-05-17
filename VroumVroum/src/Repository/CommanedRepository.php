<?php

namespace App\Repository;

use App\Entity\Commaned;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Commaned|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commaned|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commaned[]    findAll()
 * @method Commaned[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommanedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commaned::class);
    }

    // /**
    //  * @return Commaned[] Returns an array of Commaned objects
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
    public function findOneBySomeField($value): ?Commaned
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
