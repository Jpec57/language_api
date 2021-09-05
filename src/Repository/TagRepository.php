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

    
    public function findCardCountByTagAndUser(int $userId, array $locales = [])
    {
        $params = [
            'userId' => $userId,
            'date' => new \DateTime()
        ];

        $subQueryQb = $this->createQueryBuilder('it');
        $qb = $this->createQueryBuilder('t')
            ->select('t as tag')
            ->addSelect("(" .
                $subQueryQb->select('COUNT(ic)')
                    ->innerJoin('it.srsCards', 'ic')
                    ->andWhere('ic.nextAvailabilityDate <= :date')
                    ->andWhere("it.id = t.id")
                    ->getDQL() . ") AS reviewCount"
            )
            ->innerJoin('t.user', 'u')
            ->join('t.srsCards', 'c')
            ->andWhere('u.id = :userId');
        if (!empty($locales)){
            $params['locales1'] = $locales;
            $params['locales2'] = $locales;
            $qb = $qb->andWhere('t.locale1 IN (:locales1) AND t.locale2 IN (:locales2)');
        }
        $qb = $qb
            ->setParameters($params)
            ->groupBy('t.id')
            ->orderBy('t.label', 'ASC');
        return $qb->getQuery()
            ->getResult();
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
        if (!empty($locales)){
            $params['locales1'] = $locales;
            $params['locales2'] = $locales;
            $qb = $qb->andWhere('t.locale1 IN (:locales1) AND t.locale2 IN (:locales2)');
        }
        $qb = $qb
            ->setParameters($params)
            ->orderBy('t.lastUseDate', 'DESC')
            ->setMaxResults(5)
        ;
        return $qb->getQuery()
            ->getResult();
    }

    public function findForUser(int $userId, array $locales = []){
        $params = [
            'userId' => $userId,
        ];
        $qb = $this->createQueryBuilder('t')
            ->andWhere('t.user = :userId');
        if (!empty($locales)){
            $params['locales1'] = $locales;
            $params['locales2'] = $locales;
            $qb = $qb->andWhere('t.locale1 IN (:locales1) AND t.locale2 IN (:locales2)');
        }
        $qb = $qb
            ->setParameters($params);
        return $qb
            ->getQuery()
            ->getResult();
    }

    public function findByLabelsAndLocalesForUser(int $userId, array $labels, array $locales){
        $qb = $this->createQueryBuilder('t')
            ->andWhere('t.user = :userId')
            ->andWhere('t.label IN (:labels)')
            ->andWhere('t.locale1 IN (:locales1) AND t.locale2 IN (:locales2)')
            ->setParameters([
                'userId' => $userId,
                'labels' => $labels,
                'locales1' => $locales,
                'locales2' => $locales
            ]);
        return $qb
            ->getQuery()
            ->getResult();
    }
}
