<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\VocabCard;
use App\Trait\SrsRepositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method VocabCard|null find($id, $lockMode = null, $lockVersion = null)
 * @method VocabCard|null findOneBy(array $criteria, array $orderBy = null)
 * @method VocabCard[]    findAll()
 * @method VocabCard[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VocabCardRepository extends ServiceEntityRepository
{
    use SrsRepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VocabCard::class);
    }

}
