<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    /**
     * UserRepository constructor.
     *
     * @param \Doctrine\Common\Persistence\ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Finds more than `count` posts except current User's posts.
     *
     * @param int $count
     * @param \App\Entity\User $user
     *
     * @return mixed[]
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
}
