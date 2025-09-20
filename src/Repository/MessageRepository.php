<?php

namespace App\Repository;

use App\Entity\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Message>
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    public function findUserInterlocutorsByLastMessage(int $userId): array
    {
        $qb = $this->createQueryBuilder('m');

        $qb->select('
        CASE 
            WHEN m.sender = :userId THEN IDENTITY(m.receiver)
            ELSE IDENTITY(m.sender)
        END AS interlocutorId,
        MAX(m.time) AS lastMessageTime
    ')
            ->where('m.meetup IS NULL')
            ->andWhere('m.sender = :userId OR m.receiver = :userId')
            ->setParameter('userId', $userId)
            ->groupBy('interlocutorId')
            ->orderBy('lastMessageTime', 'DESC');

        return $qb->getQuery()->getResult();
    }

    public function findMessagesBetweenUsers(int $userId, int $interlocutorId): array
    {
        return $this->createQueryBuilder('m')
            ->andWhere('(m.sender = :userId AND m.receiver = :interlocutorId) OR (m.sender = :interlocutorId AND m.receiver = :userId)')
            ->setParameter('userId', $userId)
            ->setParameter('interlocutorId', $interlocutorId)
            ->orderBy('m.time', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
