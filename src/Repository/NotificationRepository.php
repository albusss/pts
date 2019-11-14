<?php

namespace App\Repository;

use App\Entity\Notification;
use App\Entity\User;

class NotificationRepository extends BaseRepository implements NotificationRepositoryInterface
{
    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
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

    /**
     * {@inheritdoc}
     */
    protected function getEntityClass(): string
    {
        return Notification::class;
    }
}
