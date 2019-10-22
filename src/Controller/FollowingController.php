<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Security("is_granted('ROLE_USER')")
 * @Route("/following")
 */
final class FollowingController extends AbstractController
{
    /**
     *
     * @Route("/follow/{id}", name="following_follow")
     */
    public function follow(User $userToFollow)
    {
        /** @var \App\Entity\User $currentUser */
        $currentUser = $this->getUser();

        if ($userToFollow->getId() !== $currentUser->getId()) {
            $currentUser->getFollowing()->add($userToFollow);

            $this->getDoctrine()->getManager()->flush();
        }

        return $this->redirectToRoute(
            'micro_post_user',
            ['username' => $userToFollow->getUsername()]
        );
    }

    /**
     *
     * @Route("/unfollow/{id}", name="following_unfollow")
     */
    public function unfollow(User $userToFollow)
    {
        /** @var \App\Entity\User $currentUser */
        $currentUser = $this->getUser();

        $currentUser->getFollowing()->removeElement($userToFollow);

        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute(
            'micro_post_user',
            ['username' => $userToFollow->getUsername()]
        );
    }
}
