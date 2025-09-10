<?php

namespace App\Repository;

use App\Entity\Game;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Game>
 */
class GameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    public function searchByName(string $query): array
{
    return $this->createQueryBuilder('c')
        ->where('c.name LIKE :query')
        ->setParameter('query', '%' . $query . '%')
        ->setMaxResults(10)
        ->getQuery()
        ->getResult();
}

}
