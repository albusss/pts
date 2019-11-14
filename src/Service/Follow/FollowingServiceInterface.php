<?php
declare(strict_types=1);

namespace App\Service\Follow;

use App\Entity\User;

interface FollowingServiceInterface
{
    /**
     * Follow User.
     *
     * @param \App\Entity\User $currentUser
     * @param \App\Entity\User $userToFollow
     *
     * @return void
     */
    public function follow(User $currentUser, User $userToFollow): void;

    /**
     * Unfollow User.
     *
     * @param \App\Entity\User $currentUser
     * @param \App\Entity\User $userToUnfollow
     *
     * @return void
     */
    public function unfollow(User $currentUser, User $userToUnfollow): void;
}
