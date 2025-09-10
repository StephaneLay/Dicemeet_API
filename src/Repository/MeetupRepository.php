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
    public function findByFilters(array $cityNames, array $gameNames, array $barNames)
    {
        $qb = $this->createQueryBuilder('m')
            ->leftJoin('m.game', 'g')
            ->leftJoin('m.place', 'p')
            ->leftJoin('p.city', 'c')
            ->addSelect('g', 'p', 'c');

        if (!empty($cityNames)) {
            $qb->andWhere('c.name IN (:cities)')
                ->setParameter('cities', $cityNames);
        }

        if (!empty($gameNames)) {
            $qb->andWhere('g.name IN (:games)')
                ->setParameter('games', $gameNames);
        }

        if (!empty($barNames)) {
            $qb->andWhere('p.name IN (:bars)')
                ->setParameter('bars', $barNames);
        }

        return $qb->getQuery()->getResult();
    }
}