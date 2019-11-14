<?php
declare(strict_types=1);

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

abstract class BaseRepository extends ServiceEntityRepository
{
    /**
     *  Base Repository constructor.
     *
     * @param \Doctrine\Common\Persistence\ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, $this->getEntityClass());
    }

    /**
     * Delete entity.
     *
     * @param $object
     *
     * @return void
     *
     * @throws \Doctrine\ORM\ORMException
     */
    public function delete($object): void
    {
        $this->_em->remove($object);
        $this->_em->flush();
    }

    /**
     * Save entity.
     *
     * @param $object
     *
     * @return void
     *
     * @throws \Doctrine\ORM\ORMException
     */
    public function save($object): void
    {
        $this->_em->persist($object);
        $this->_em->flush();
    }

    /**
     * Gets entity class.
     *
     * @return string
     */
    abstract protected function getEntityClass(): string;
}
