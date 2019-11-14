<?php
declare(strict_types=1);

namespace App\Service\Notification;

use App\Entity\Notification;
use App\Entity\User;

interface NotificationServiceInterface
{
    /**
     * Acknowledge notification.
     *
     * @param \App\Entity\Notification $notification
     *
     * @return void
     *
     * @throws \Doctrine\ORM\ORMException
     */
    public function acknowledge(Notification $notification): void;

    /**
     * Acknowledge all notifications.
     *
     * @param \App\Entity\User $currentUser
     *
     * @return void
     */
    public function acknowledgeAll(User $currentUser): void;

    /**
     * Returns all of unread User notifications.
     *
     * @param \App\Entity\User $currentUser
     *
     * @return \App\Entity\Notification[]
     */
    public function notifications(User $currentUser): array;

    /**
     * Returns count of unread notifications.
     *
     * @param \App\Entity\User $currentUser
     *
     * @return int
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function unreadCount(User $currentUser): int;
}
