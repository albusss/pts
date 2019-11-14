<?php

namespace App\Repository;

use App\Entity\Notification;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Notification|null find($id, $lockMode = null, $lockVersion = null)
 * @method Notification|null findOneBy(array $criteria, array $orderBy = null)
 * @method Notification[]    findAll()
 * @method Notification[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NotificationRepository extends ServiceEntityRepository
{
    /**
     * NotificationRepository constructor.
     *
     * @param \Doctrine\Common\Persistence\ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Notification::class);
    }

    /**
     * Returns count of unseen notifications.
     *
     * @param \App\Entity\User $user
     *
     * @return int
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findUnseenByUser(User $user): int
    {
        $qb = $this->createQueryBuilder('n');

        $qb
            ->select('count(n)')
            ->where('n.user = :user')
            ->andWhere('n.seen = :seen')
            ->setParameter('user', $user)
            ->setParameter('seen', false);

        $result = $qb->getQuery()->getSingleScalarResult();

        if ($result === null) {
            return 0;
        }

        return $result;
    }

    /**
     * Mark all as read.
     *
     * @param \App\Entity\User $user
     *
     * @return void
     */
    public function markAllAsReadByUser(User $user): void
    {
        $qb = $this->createQueryBuilder('n');

        $qb
            ->update(Notification::class, 'n')
            ->set('n.seen', true)
            ->where('n.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->execute();
    }
}
