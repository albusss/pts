<?php
declare(strict_types=1);

namespace App\Repository;

use Doctrine\Common\Persistence\ObjectRepository;

interface BaseRepositoryInterface extends ObjectRepository
{
    /**
     * Delete entity.
     *
     * @param $object
     *
     * @return void
     *
     * @throws \Doctrine\ORM\ORMException
     */
    public function delete($object): void;

    /**
     * Save entity.
     *
     * @param $object
     *
     * @return void
     *
     * @throws \Doctrine\ORM\ORMException
     */
    public function save($object): void;
}
