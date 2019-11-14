<?php
declare(strict_types=1);

namespace App\Service\Like;

use App\Entity\MicroPost;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

final class LikeService implements LikeServiceInterface
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $entityManager;

    /**
     * LikeService constructor.
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
    public function like(User $currentUser, MicroPost $post): void
    {
        $post->like($currentUser);

        $this->entityManager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function unlike(User $currentUser, MicroPost $post): void
    {
        $post->getLikedBy()->removeElement($currentUser);

        $this->entityManager->flush();
    }
}
