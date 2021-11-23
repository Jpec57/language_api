<?php

namespace App\Traits;

use App\Entity\User;
use Exception;

trait SrsRepositoryTrait
{

    public function findAvailableCardCount(User $user, \DateTime $date, array $locales = [])
    {
        $params = [
            'date' => $date,
            'user' => $user,
        ];
        $qb = $this->createQueryBuilder('c')
            ->select('COUNT(c.id) as count')
            ->andWhere('c.nextAvailabilityDate <= :date')
            ->andWhere('c.isActivated = 1')
            ->andWhere('c.user = :user');
        if (!empty($locales)) {
            $params['locales'] = $locales;
            $params['locales2'] = $locales;
            $qb = $qb->andWhere('c.cardLocale IN (:locales) AND c.translationLocale IN (:locales2)');
        }
        $qb
            ->setParameters($params);
        return $qb->getQuery()
            ->getSingleResult();
    }

    public function findAvailableCardCountSummary(User $user, \DateTime $date)
    {
        $params = [
            'date' => $date,
            'user' => $user,
        ];
        $qb = $this->createQueryBuilder('c')
            ->select('COUNT(c.id) as count, CONCAT(CONCAT(c.cardLocale, \'|\'), c.translationLocale) as localeRef')
            ->andWhere('c.nextAvailabilityDate <= :date')
            ->andWhere('c.isActivated = 1')
            ->andWhere('c.user = :user');
        $qb
            ->setParameters($params)
            ->groupBy('localeRef');
        return $qb->getQuery()
            ->getResult();
    }

    /**
     * Goal locale is second param
     */
    public function findAvailableCards(User $user, \DateTime $date, array $locales = [])
    {
        $params = [
            'date' => $date,
            'user' => $user,
        ];

        $qb = $this->createQueryBuilder('c')

            ->select("c as card")
            ->andWhere('c.nextAvailabilityDate <= :date')
            ->andWhere('c.isActivated = 1')
            ->andWhere('c.user = :user');
        if (!empty($locales)) {
            $params['locales'] = $locales;
            $params['locales2'] = $locales;
            try {
                $len = strlen($locales[1]);
                if ($len > 4 || ($len == 4 && false === strpos($locales[1], "_"))) {
                    throw new Exception("Hop hop hop, i am not safe but not dumb.");
                }
                $qb = $qb->addSelect('(CASE WHEN c.cardLocale = \'' . $locales[1] . '\' THEN 1 ELSE 0 END) AS to');
                $qb = $qb->addOrderBy('to', 'ASC');
            } catch (Exception $e) {
            }


            $qb = $qb->andWhere('c.cardLocale IN (:locales) AND c.translationLocale IN (:locales2)');
        }
        $qb
            ->setParameters($params)
            // ->orderBy('')
        ;
        $res = $qb->getQuery()
            ->getArrayResult();
        $arr = [];
        foreach ($res as $item) {
            $arr[] = $item["card"];
        }
        return $arr;
    }

    public function findAwaitingCards(User $viewer, array $locales = [])
    {
        $params = [
            'user' => $viewer,
        ];
        $qb = $this->createQueryBuilder('c')
            ->andWhere('c.user = :user')
            ->andWhere('c.isActivated = 0');
        if (!empty($locales)) {
            $params['locales'] = $locales;
            $params['locales2'] = $locales;
            $qb = $qb->andWhere('c.cardLocale IN (:locales) AND c.translationLocale IN (:locales2)');
        }
        return $qb
            ->setParameters($params)
            ->getQuery()
            ->getResult();
    }

    public function findOrderedCardSchedule(User $viewer, array $locales = [])
    {
        $params = [
            'user' => $viewer,
        ];
        $qb = $this->createQueryBuilder('c')
            ->andWhere('c.user = :user')
            ->andWhere('c.isActivated = 1');
        if (!empty($locales)) {
            $params['locales'] = $locales;
            $params['locales2'] = $locales;
            $qb = $qb->andWhere('c.cardLocale IN (:locales) AND c.translationLocale IN (:locales2)');
        }
        $qb
            ->setParameters($params)
            ->orderBy('c.nextAvailabilityDate', 'ASC');
        return $qb->getQuery()
            ->getResult();
    }
}
