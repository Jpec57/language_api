<?php

namespace App\Repository;

use App\Entity\SRSCard;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method VocabCard|null find($id, $lockMode = null, $lockVersion = null)
 * @method VocabCard|null findOneBy(array $criteria, array $orderBy = null)
 * @method VocabCard[]    findAll()
 * @method VocabCard[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SRSCardRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SRSCard::class);
    }

    public function findByTypeBuilder(string $type): QueryBuilder
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.type = :type')
            ->setParameter('type', $type);
    }
}
