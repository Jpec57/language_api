<?php

namespace App\Repository;

use App\Entity\SRSCard;
use App\Entity\User;
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

    public function findAvailableCards(User $user, \DateTime $date)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.nextAvailabilityDate >= :date')
            ->andWhere('c.user = :user')
            ->setParameters([
                'date'=> $date,
                'user'=> $user,
            ])
            ->getQuery()
            ->getResult();
    }
}
