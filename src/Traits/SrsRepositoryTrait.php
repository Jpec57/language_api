<?php

namespace App\Traits;

use App\Entity\User;

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
            ->andWhere('c.user = :user');
        $qb
            ->setParameters($params)
            ->groupBy('localeRef');
            return $qb->getQuery()
            ->getResult();
    }
    

    public function findAvailableCards(User $user, \DateTime $date, array $locales = [])
    {
        $params = [
            'date' => $date,
            'user' => $user,
        ];

        $qb = $this->createQueryBuilder('c')
            ->andWhere('c.nextAvailabilityDate <= :date')
            ->andWhere('c.user = :user');
        if (!empty($locales)) {
            $params['locales'] = $locales;
            $params['locales2'] = $locales;
            $qb = $qb->andWhere('c.cardLocale IN (:locales) AND c.translationLocale IN (:locales2)');
        }
        $qb
            ->setParameters($params);
        return $qb->getQuery()
            ->getResult();
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