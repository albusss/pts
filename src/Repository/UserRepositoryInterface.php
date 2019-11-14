<?php

namespace App\Repository;

use App\Entity\User;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Finds more than `count` posts except current User's posts.
     *
     * @param int $count
     * @param \App\Entity\User $user
     *
     * @return mixed[]
     */
    public function findAllMoreThanPostsExceptUser(int $count, User $user): array;
}
