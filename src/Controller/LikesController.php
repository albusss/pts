<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\MicroPost;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/likes")
 */
final class LikesController extends AbstractController
{
    /**
     * @Route("/like/{id}", name="likes_like")
     * @param \App\Entity\MicroPost $post
     */
    public function like(MicroPost $post)
    {
        $currentUser = $this->getUser();

        if ($currentUser instanceof User === false) {
            return new JsonResponse([], Response::HTTP_UNAUTHORIZED);
        }

        $post->like($currentUser);

        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse(['count' => $post->getLikedBy()->count()]);
    }

    /**
     * @Route("/unlike/{id}", name="likes_unlike")
     * @param \App\Entity\MicroPost $post
     */
    public function unlike(MicroPost $post)
    {
        $currentUser = $this->getUser();

        if ($currentUser instanceof User === false) {
            return new JsonResponse([], Response::HTTP_UNAUTHORIZED);
        }

        $post->getLikedBy()->removeElement($currentUser);

        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse(['count' => $post->getLikedBy()->count()]);
    }
}
