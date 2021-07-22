<?php

namespace App\Trait;

use App\Entity\User;

trait SrsRepositoryTrait
{
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

    public function findOrderedCardSchedule(User $viewer)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.user = :user')
            ->setParameters([
                'user'=> $viewer,
            ])
            ->orderBy('c.nextAvailabilityDate', 'ASC')
            ->getQuery()
            ->getResult();
    }
}