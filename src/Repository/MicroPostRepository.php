<?php

namespace App\Repository;

use App\Entity\MicroPost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MicroPost|null find($id, $lockMode = null, $lockVersion = null)
 * @method MicroPost|null findOneBy(array $criteria, array $orderBy = null)
 * @method MicroPost[]    findAll()
 * @method MicroPost[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MicroPostRepository extends ServiceEntityRepository
{
    /**
     * MicroPostRepository constructor.
     *
     * @param \Doctrine\Common\Persistence\ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MicroPost::class);
    }

    /**
     * Find all microposts by Users.
     *
     * @param \Doctrine\Common\Collections\Collection $users
     *
     * @return mixed[]
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
}
