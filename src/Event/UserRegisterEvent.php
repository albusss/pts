<?php
declare(strict_types=1);

namespace App\Event;

use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

final class UserRegisterEvent extends Event
{
    public const NAME = 'user.register';

    /**
     * @var \App\Entity\User
     */
    private $registeredUser;

    /**
     * UserRegisterEvent constructor.
     *
     * @param \App\Entity\User $registeredUser
     */
    public function __construct(User $registeredUser)
    {
        $this->registeredUser = $registeredUser;
    }

    /**
     * @return \App\Entity\User
     */
    public function getRegisteredUser(): User
    {
        return $this->registeredUser;
    }
}
