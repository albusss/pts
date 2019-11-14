<?php
declare(strict_types=1);

namespace App\Service\Like;

use App\Entity\MicroPost;
use App\Entity\User;
use App\Repository\LikeNotificationRepositoryInterface;

final class LikeService implements LikeServiceInterface
{
    /**
     * @var \App\Repository\LikeNotificationRepository
     */
    private $repository;

    /**
     * LikeService constructor.
     *
     * @param \App\Repository\LikeNotificationRepositoryInterface $repository
     */
    public function __construct(LikeNotificationRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * {@inheritdoc}
     */
    public function like(User $currentUser, MicroPost $post): void
    {
        $post->like($currentUser);

        $this->repository->save($post);
    }

    /**
     * {@inheritdoc}
     */
    public function unlike(User $currentUser, MicroPost $post): void
    {
        $post->getLikedBy()->removeElement($currentUser);

        $this->repository->save($post);
    }
}
