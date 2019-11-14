<?php
declare(strict_types=1);

namespace App\Service\Notification;

use App\Entity\Notification;
use App\Entity\User;
use App\Repository\NotificationRepositoryInterface;

final class NotificationService implements NotificationServiceInterface
{
    /**
     * @var \App\Repository\NotificationRepositoryInterface
     */
    private $repository;

    /**
     * NotificationService constructor.
     *
     * @param \App\Repository\NotificationRepositoryInterface $repository
     */
    public function __construct(NotificationRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * {@inheritdoc}
     */
    public function acknowledge(Notification $notification): void
    {
        $notification->setSeen(true);

        $this->repository->save($notification);
    }

    /**
     * {@inheritdoc}
     */
    public function acknowledgeAll(User $currentUser): void
    {
        $this->repository->markAllAsReadByUser($currentUser);
    }

    /**
     * {@inheritdoc}
     */
    public function notifications(User $currentUser): array
    {
        return
            $this->repository->findBy(
                [
                    'seen' => false,
                    'user' => $currentUser,
                ]
            );
    }

    /**
     * {@inheritdoc}
     */
    public function unreadCount(User $currentUser): int
    {
        return $this->repository->findUnseenByUser($currentUser);
    }
}
