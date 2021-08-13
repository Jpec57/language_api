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


    public function searchCards(User $viewer, ?string $type = null, int $page = 0, int $limit = 10)
    {
        $params = [
            'viewer' => $viewer
        ];
        $qb = $this->createQueryBuilder('c')
            ->where('c.user = :viewer');
        if ($type) {
            $qb = $qb
                ->andWhere('c.type = :type');
        }

        $qb
            ->setParameters($params)
//            ->orderBy("c.cardLocale", "ASC")
//            ->addOrderBy("c.wordToTranslate", "ASC")
            ->setFirstResult($page * $limit)
            ->setMaxResults($limit);
        return $qb->getQuery()->getResult();
    }
}
