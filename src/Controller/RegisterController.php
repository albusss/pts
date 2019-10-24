<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Event\UserRegisterEvent;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class RegisterController extends AbstractController
{
    /**
     * @Route("/register", name="user_register")
     */
    public function register(
        Request $request,
        UserPasswordEncoderInterface $encoder,
        EventDispatcherInterface $dispatcher
    ) {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $encoder->encodePassword($user, $user->getPlainPassword());
            $confirmToken = \md5($user->getUsername() . \random_int(1, 100));
            $user
                ->setPassword($password)
                ->setRoles([User::ROLE_USER])
                ->setConfirmationToken($confirmToken);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $userRegisterEvent = new UserRegisterEvent($user);

            $dispatcher->dispatch($userRegisterEvent, UserRegisterEvent::NAME);

            return $this->redirectToRoute('micro_post_index');
        }

        return $this->render('register/register.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
