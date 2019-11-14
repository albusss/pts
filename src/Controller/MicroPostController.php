<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\MicroPost;
use App\Entity\User;
use App\Form\MicroPostType;
use App\Security\MicroPostVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/micro-post")
 */
final class MicroPostController extends AbstractController
{
    /**
     * Adds a new Post.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     *
     * @Route("/add", name="micro_post_add")
     */
    public function add(Request $request): Response
    {
        if (false === $this->isGranted(User::ROLE_USER)) {
            return $this->redirectToRoute('security_login');
        }

        $user = $this->getUser();
        $microPost = new  MicroPost();
        $microPost->setUser($user);

        $form = $this->createForm(MicroPostType::class, $microPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($microPost);
            $em->flush();

            return new RedirectResponse($this->generateUrl('micro_post_index'));
        }

        return
            $this->render(
                'micro-post/add.html.twig',
                [
                    'form' => $form->createView(),
                ]
            );
    }

    /**
     * Deletes the Post.
     *
     * @param \App\Entity\MicroPost $post
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/delete/{id}", name="micro_post_delete")
     */
    public function delete(MicroPost $post): Response
    {
        $this->denyAccessUnlessGranted(MicroPostVoter::EDIT, $post);

        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();

        $this->addFlash('notice', 'micro post was deleted');

        return new RedirectResponse($this->generateUrl('micro_post_index'));
    }

    /**
     * Edits the Post.
     *
     * @param \App\Entity\MicroPost $post
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/edit/{id}", name="micro_post_edit")
     */
    public function edit(MicroPost $post, Request $request): Response
    {
        $this->denyAccessUnlessGranted(MicroPostVoter::EDIT, $post);

        $form = $this->createForm(MicroPostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return new RedirectResponse($this->generateUrl('micro_post_index'));
        }

        return $this->render('micro-post/add.html.twig', ['form' => $form->createView()]);
    }

    /**
     * Index page.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/", name="micro_post_index")
     */
    public function index(): Response
    {
        /** @var \App\Repository\MicroPostRepository $postRepository */
        $postRepository = $this->getDoctrine()->getRepository(MicroPost::class);
        /** @var \App\Repository\UserRepository $userRepository */
        $userRepository = $this->getDoctrine()->getRepository(User::class);
        $currentUser = $this->getUser();
        $usersToFollow = [];
        if ($currentUser instanceof User) {
            $posts = $postRepository->findAllByUsers($currentUser->getFollowing());
            $usersToFollow = \count($posts) === 0
                ? $userRepository->findAllMoreThanPostsExceptUser(5, $currentUser)
                : [];
        } else {
            $posts = $postRepository->findBy([], ['time' => 'DESC']);
        }

        return
            $this->render(
                'micro-post/index.html.twig',
                [
                    'posts' => $posts,
                    'usersToFollow' => $usersToFollow,
                ]
            );
    }

    /**
     * Shows the single Post.
     *
     * @param \App\Entity\MicroPost $post
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/{id}", name="micro_post_post")
     */
    public function post(MicroPost $post): Response
    {
        return
            $this->render(
                'micro-post/post.html.twig',
                [
                    'post' => $post,
                ]
            );
    }

    /**
     * All User Posts.
     *
     * @param \App\Entity\User $user
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/user/{username}", name="micro_post_user")
     */
    public function userPosts(User $user): Response
    {
        return
            $this->render(
                'micro-post/user-posts.html.twig',
                [
                    'posts' => $user->getPosts(),
                    'user' => $user,
                ]
            );
    }
}
