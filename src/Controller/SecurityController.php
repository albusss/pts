<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

final class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="security_login")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function login(AuthenticationUtils $utils)
    {
        return $this->render(
            'security/login.html.twig',
            [
                'last_username' => $utils->getLastUsername(),
                'error' => $utils->getLastAuthenticationError()
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
