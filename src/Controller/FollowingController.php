<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Service\Follow\FollowingServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Security("is_granted('ROLE_USER')")
 *
 * @Route("/following")
 */
final class FollowingController extends AbstractController
{
    /**
     * @var \App\Service\Follow\FollowingServiceInterface
     */
    private $service;

    /**
     * FollowingController constructor.
     *
     * @param \App\Service\Follow\FollowingServiceInterface $service
     */
    public function __construct(FollowingServiceInterface $service)
    {
        $this->service = $service;
    }

    /**
     * Follow User.
     *
     * @param \App\Entity\User $userToFollow
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/follow/{id}", name="following_follow")
     */
    public function follow(User $userToFollow): Response
    {
        /** @var \App\Entity\User $currentUser */
        $currentUser = $this->getUser();

        $this->service->follow($currentUser, $userToFollow);

        return
            $this->redirectToRoute(
                'micro_post_user',
                [
                    'username' => $userToFollow->getUsername(),
                ]
            );
    }

    /**
     * Unfollow User.
     *
     * @param \App\Entity\User $userToUnfollow
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/unfollow/{id}", name="following_unfollow")
     */
    public function unfollow(User $userToUnfollow): Response
    {
        /** @var \App\Entity\User $currentUser */
        $currentUser = $this->getUser();

        $this->service->unfollow($currentUser, $userToUnfollow);

        return
            $this->redirectToRoute(
                'micro_post_user',
                [
                    'username' => $userToUnfollow->getUsername(),
                ]
            );
    }
}
