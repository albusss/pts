<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\MicroPost;
use App\Service\Like\LikeServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Security("is_granted('ROLE_USER')")
 *
 * @Route("/likes")
 */
final class LikesController extends AbstractController
{
    /**
     * @var \App\Service\Like\LikeServiceInterface
     */
    private $likeService;

    /**
     * LikesController constructor.
     *
     * @param \App\Service\Like\LikeServiceInterface $likeService
     */
    public function __construct(LikeServiceInterface $likeService)
    {
        $this->likeService = $likeService;
    }

    /**
     * Likes the Post.
     *
     * @param \App\Entity\MicroPost $post
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Doctrine\ORM\ORMException
     *
     * @Route("/like/{id}", name="likes_like")
     */
    public function like(MicroPost $post): Response
    {
        /** @var \App\Entity\User $currentUser */
        $currentUser = $this->getUser();

        $this->likeService->like($currentUser, $post);

        return
            new JsonResponse(
                [
                    'count' => $post->getLikedBy()->count(),
                ]
            );
    }

    /**
     * Unlikes the Post.
     *
     * @param \App\Entity\MicroPost $post
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Doctrine\ORM\ORMException
     *
     * @Route("/unlike/{id}", name="likes_unlike")
     */
    public function unlike(MicroPost $post): Response
    {
        /** @var \App\Entity\User $currentUser */
        $currentUser = $this->getUser();

        $this->likeService->unlike($currentUser, $post);

        return
            new JsonResponse(
                [
                    'count' => $post->getLikedBy()->count(),
                ]
            );
    }
}
