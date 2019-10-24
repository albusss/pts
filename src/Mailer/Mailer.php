<?php
declare(strict_types=1);

namespace App\Mailer;

use App\Entity\User;
use Twig\Environment;

final class Mailer
{
    /**
     * @var string
     */
    private $mailFrom;

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var \Twig\Environment
     */
    private $twig;

    /**
     * Mailer constructor.
     *
     * @param \Swift_Mailer $mailer
     * @param \Twig\Environment $twig
     * @param string $mailFrom
     */
    public function __construct(\Swift_Mailer $mailer, Environment $twig, string $mailFrom)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->mailFrom = $mailFrom;
    }

    public function sendConfirmation(User $user)
    {
        $message =
            (new \Swift_Message())
                ->setSubject('Welcom to the Micro-post app!')
                ->setFrom($this->mailFrom)
                ->setTo($user->getEmail())
                ->setBody(
                    $this->twig->render('email/register.html.twig', ['user' => $user]),
                    'text/html'
                );

        $this->mailer->send($message);
    }
}
