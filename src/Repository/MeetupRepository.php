<?php

namespace App\Repository;

use App\Entity\Meetup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Meetup>
 */
class MeetupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Meetup::class);
    }

    public function findByFilters(array $cityIds, array $gameIds, array $barIds): array {
    $qb = $this->createQueryBuilder('e');

    if ($cityIds) {
        $qb->andWhere('e.city IN (:cityIds)')
           ->setParameter('cityIds', $cityIds);
    }

    if ($gameIds) {
        $qb->andWhere('e.game IN (:gameIds)')
           ->setParameter('gameIds', $gameIds);
    }

    if ($barIds) {
        $qb->andWhere('e.place IN (:barIds)')
           ->setParameter('barIds', $barIds);
    }

    return $qb->getQuery()->getResult();
}
}
