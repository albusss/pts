<?php

namespace App\Repository;

use App\Entity\User;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function findAllMoreThanPostsExceptUser(int $count, User $user): array
    {
        $qb = $this->createQueryBuilder('u');

        $qb
            ->select('u')
            ->innerJoin('u.posts', 'p')
            ->groupBy('u')
            ->having('COUNT(p) > :count AND u != :user')
            ->setParameters(\compact('count', 'user'));

        return $qb->getQuery()->getResult();
    }

    /**
     * {@inheritdoc}
     */
    protected function getEntityClass(): string
    {
        return User::class;
    }
}
