<?php
declare(strict_types=1);

namespace App\Service\Follow;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

final class FollowingService implements FollowingServiceInterface
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $entityManager;

    /**
     * FollowingService constructor.
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
    public function follow(User $currentUser, User $userToFollow): void
    {
        if ($currentUser->getId() !== $userToFollow->getId()) {
            $currentUser->follow($userToFollow);

            $this->entityManager->flush();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function unfollow(User $currentUser, User $userToUnfollow): void
    {
        $currentUser->getFollowing()->removeElement($userToUnfollow);

        $this->entityManager->flush();
    }
}
