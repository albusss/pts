<?php
declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class UserChecker implements UserCheckerInterface
{
    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function checkPreAuth(UserInterface $user)
    {
        if ($user instanceof User === false) {
            return;
        }
    }
}
