<?php
declare(strict_types=1);

namespace App\Event;

use App\Mailer\Mailer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class UserSubscriber implements EventSubscriberInterface
{
    /**
     * @var \App\Mailer\Mailer
     */
    private $mailer;

    /**
     * UserSubscriber constructor.
     *
     * @param \App\Mailer\Mailer $mailer
     */
    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            UserRegisterEvent::NAME => 'onUserRegister'
        ];
    }

    /**
     * Sends confirm email to User.
     *
     * @param \App\Event\UserRegisterEvent $event
     *
     * @return void
     */
    public function onUserRegister(UserRegisterEvent $event): void
    {
        $this->mailer->sendConfirmation($event->getRegisteredUser());
    }
}
