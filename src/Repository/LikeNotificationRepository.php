<?php

namespace App\Repository;

use App\Entity\LikeNotification;

class LikeNotificationRepository extends BaseRepository implements LikeNotificationRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    protected function getEntityClass(): string
    {
        return LikeNotification::class;
    }
}
