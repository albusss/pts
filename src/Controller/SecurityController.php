<?php
declare(strict_types=1);

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

final class SecurityController extends AbstractController
{
    /**
     * Confirm email.
     *
     * @param string $token
     * @param \App\Repository\UserRepository $userRepository
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/confirm/{token}", name="security_confirm")
     */
    public function confirm(string $token, UserRepository $userRepository): Response
    {
        /** @var \App\Entity\User $user */
        $user = $userRepository->findBy(['confirmationToken' => $token]);

        if ($user !== null) {
            $user
                ->setEnabled(true)
                ->setConfirmationToken('');

            $this->getDoctrine()->getManager()->flush();
        }

        return $this->render('security/confirmation.html.twig', ['user' => $user]);
    }

    /**
     * Login.
     *
     * @param \Symfony\Component\Security\Http\Authentication\AuthenticationUtils $utils
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/login", name="security_login")
     */
    public function login(AuthenticationUtils $utils): Response
    {
        return
            $this->render(
                'security/login.html.twig',
                [
                    'last_username' => $utils->getLastUsername(),
                    'error' => $utils->getLastAuthenticationError(),
                ]
            );
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout()
    {
    }
}
