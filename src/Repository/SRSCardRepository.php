<?php

namespace App\Repository;

use App\Entity\SRSCard;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SRSCard|null find($id, $lockMode = null, $lockVersion = null)
 * @method SRSCard|null findOneBy(array $criteria, array $orderBy = null)
 * @method SRSCard[]    findAll()
 * @method SRSCard[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SRSCardRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SRSCard::class);
    }
}
