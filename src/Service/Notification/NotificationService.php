<?php
declare(strict_types=1);

namespace App\Service\Notification;

use App\Entity\Notification;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

final class NotificationService implements NotificationServiceInterface
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $entityManager;

    /**
     * NotificationService constructor.
     *
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function acknowledge(Notification $notification): void
    {
        $notification->setSeen(true);

        $this->entityManager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function acknowledgeAll(User $currentUser): void
    {
        /** @var \App\Repository\NotificationRepository $repo */
        $repo = $this->entityManager->getRepository(Notification::class);

        $repo->markAllAsReadByUser($currentUser);

        $this->entityManager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function notifications(User $currentUser): array
    {
        /** @var \App\Repository\NotificationRepository $repo */
        $repo = $this->entityManager->getRepository(Notification::class);

        return
            $repo->findBy(
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
        /** @var \App\Repository\NotificationRepository $repo */
        $repo = $this->entityManager->getRepository(Notification::class);

        return $repo->findUnseenByUser($currentUser);
    }
}
