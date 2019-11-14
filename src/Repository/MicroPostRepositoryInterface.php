<?php

namespace App\Repository;

use Doctrine\Common\Collections\Collection;

interface MicroPostRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Find all microposts by Users.
     *
     * @param \Doctrine\Common\Collections\Collection $users
     *
     * @return mixed[]
     */
    public function findAllByUsers(Collection $users): array;
}
