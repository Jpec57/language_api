<?php

namespace App\Repository;

use App\Entity\ContextSentence;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ContextSentence|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContextSentence|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContextSentence[]    findAll()
 * @method ContextSentence[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContextSentenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContextSentence::class);
    }

    // /**
    //  * @return ContextSentence[] Returns an array of ContextSentence objects
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
    public function findOneBySomeField($value): ?ContextSentence
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
