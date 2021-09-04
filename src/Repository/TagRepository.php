<?php

namespace App\Repository;

use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Tag|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tag|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tag[]    findAll()
 * @method Tag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tag::class);
    }

    
    public function findCardCountByTagAndUser(int $userId, bool $onlyToReview = false)
    {
        $params = [
            'userId' => $userId,
        ];
        $qb = $this->createQueryBuilder('t')
            ->select('t as tag, COUNT(c) as count')
            ->innerJoin('t.user', 'u')
            ->innerJoin('t.srsCards', 'c')
            ->andWhere('u.id = :userId');
        if ($onlyToReview){
            $params['date'] = new \DateTime();
            $qb = $qb->andWhere('c.nextAvailabilityDate <= :date');
        }
        $qb = $qb
            ->setParameters($params)
            ->groupBy('t.id')
            ->orderBy('t.label', 'ASC');
        return $qb->getQuery()
            ->getResult()
        ;
    }

    public function findRecentTags(int $userId, array $locales = [])
    {
        $params = [
            'userId' => $userId,
        ];
        $qb = $this->createQueryBuilder('t')
            ->innerJoin('t.user', 'u')
            ->innerJoin('t.srsCards', 'c')
            ->andWhere('u.id = :userId');
//        if (!empty($locales)){
//            $params['locales'] = $locales;
//            $qb = $qb->andWhere('t.');
//        }
        $qb = $qb
            ->setParameters($params)
            ->orderBy('t.lastUseDate', 'DESC')
            ->setMaxResults(5)
        ;
        return $qb->getQuery()
            ->getResult();
    }
}
