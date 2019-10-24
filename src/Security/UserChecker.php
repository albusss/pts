<?php
declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\AccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class UserChecker implements UserCheckerInterface
{
    /**
     * Checks the user account after authentication.
     *
     * @param \App\Entity\User $user
     */
    public function checkPostAuth(UserInterface $user)
    {
        if ($user instanceof User === false) {
            return;
        }

        if (false === $user->getEnabled()) {
            throw new AccessDeniedException('Verify email before');
        }
    }

    /**
     * Checks the user account before authentication.
     *
     * @throws AccountStatusException
     */
    public function checkPreAuth(UserInterface $user)
    {
        if ($user instanceof User === false) {
            return;
        }
    }
}
