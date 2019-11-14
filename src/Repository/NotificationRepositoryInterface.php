<?php

namespace App\Repository;

use App\Entity\User;

interface NotificationRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Returns count of unseen notifications.
     *
     * @param \App\Entity\User $user
     *
     * @return int
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findUnseenByUser(User $user): int;

    /**
     * Mark all as read.
     *
     * @param \App\Entity\User $user
     *
     * @return void
     */
    public function markAllAsReadByUser(User $user): void;
}
