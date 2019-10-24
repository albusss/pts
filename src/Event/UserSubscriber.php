<?php
declare(strict_types=1);

namespace App\Event;

use App\Mailer\Mailer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class UserSubscriber implements EventSubscriberInterface
{
    /**
     * @var \App\Mailer\Mailer|\Swift_Mailer
     */
    private $mailer;

    /**
     * UserSubscriber constructor.
     *
     * @param \Swift_Mailer $mailer
     * @param \Twig\Environment $twig
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

    public function onUserRegister(UserRegisterEvent $event)
    {
        $this->mailer->sendConfirmation($event->getRegisteredUser());
    }
}
