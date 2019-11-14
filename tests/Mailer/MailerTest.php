<?php
declare(strict_types=1);

namespace App\Tests\Mailer;

use App\Entity\User;
use App\Mailer\Mailer;
use PHPUnit\Framework\TestCase;
use Twig\Environment;

final class MailerTest extends TestCase
{
    /**
     * Tests sendConfirmation method.
     *
     * @return void
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function testConfirmationEmail(): void
    {
        $user = new User();
        $user->setEmail('test@mail.com');
        $mailer = new Mailer(
            $this->createSwiftMailerMock(),
            $this->createTwigMock($user),
            'from@mail.com'
        );

        $mailer->sendConfirmation($user);
    }

    /**
     * Mock of the Swift_Mailer.
     *
     * @return \Swift_Mailer
     */
    private function createSwiftMailerMock(): \Swift_Mailer
    {
        $swiftMailer =
            $this
                ->getMockBuilder(\Swift_Mailer::class)
                ->disableOriginalConstructor()
                ->getMock();

        $swiftMailer
            ->expects(self::once())
            ->method('send')
            ->with(
                self::callback(
                    static function ($subject) {
                        $messageStr = (string)$subject;

                        return (
                                \strpos($messageStr, 'From: from@mail.com')
                                && \strpos($messageStr, 'Subject: Welcom to the Micro-post app!')
                                && \strpos($messageStr, 'To: test@mail.com')
                                && \strpos($messageStr, 'expected email body')
                            ) !== false;
                    }
                )
            );

        return $swiftMailer;
    }

    /**
     * Mock of the Twig.
     *
     * @param \App\Entity\User $user
     *
     * @return \Twig\Environment
     */
    private function createTwigMock(User $user): Environment
    {
        $twig =
            $this
                ->getMockBuilder(Environment::class)
                ->disableOriginalConstructor()
                ->getMock();

        $twig
            ->expects(self::once())
            ->method('render')
            ->with('email/register.html.twig', ['user' => $user])
            ->willReturn('expected email body');

        return $twig;
    }
}
