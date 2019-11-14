<?php

namespace App\Repository;

use App\Entity\MicroPost;
use Doctrine\Common\Collections\Collection;

class MicroPostRepository extends BaseRepository implements MicroPostRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function findAllByUsers(Collection $users): array
    {
        $qb = $this->createQueryBuilder('p');

        $qb
            ->select('p')
            ->andWhere($qb->expr()->in('p.user', ':following'))
            ->setParameter('following', $users)
            ->orderBy($qb->expr()->desc('p.time'));

        return $qb->getQuery()->getResult();
    }

    /**
     * {@inheritdoc}
     */
    protected function getEntityClass(): string
    {
        return MicroPost::class;
    }
}
