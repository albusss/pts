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
     * @Route("/add", name="micro_post_add")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     */
    public function add(Request $request)
    {
        if ($this->isGranted(User::ROLE_USER) === false) {
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

        return new Response(
            $this->renderView('micro-post/add.html.twig', [
                'form' => $form->createView()
            ])
        );
    }

    /**
     * @Route("/delete/{id}", name="micro_post_delete")
     *
     * @param \App\Entity\MicroPost $post
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(MicroPost $post)
    {
        $this->denyAccessUnlessGranted(MicroPostVoter::EDIT, $post);

        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();

        $this->addFlash('notice', 'micro post was deleted');

        return new RedirectResponse($this->generateUrl('micro_post_index'));
    }

    /**
     * @Route("/edit/{id}", name="micro_post_edit")
     *
     * @param \App\Entity\MicroPost $post
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function edit(MicroPost $post, Request $request)
    {
        $this->denyAccessUnlessGranted(MicroPostVoter::EDIT, $post);

        $form = $this->createForm(MicroPostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return new RedirectResponse($this->generateUrl('micro_post_index'));
        }

        return new Response(
            $this->renderView('micro-post/add.html.twig', [
                'form' => $form->createView()
            ])
        );
    }

    /**
     * @Route("/", name="micro_post_index")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(): Response
    {
        $repo = $this->getDoctrine()->getRepository(MicroPost::class);
        $html = $this->renderView('micro-post/index.html.twig', [
            'posts' => $repo->findBy([], ['time' => 'DESC'])
        ]);

        return new Response($html);
    }

    /**
     * @Route("/{id}", name="micro_post_post")
     * @param \App\Entity\MicroPost $post
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function post(MicroPost $post)
    {
        return new Response(
            $this->renderView('micro-post/post.html.twig', [
                'post' => $post
            ])
        );
    }

    /**
     *
     * @Route("user/{username}", name="micro_post_user")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function userPosts(User $user)
    {
        $html = $this->renderView('micro-post/index.html.twig', [
            'posts' => $user->getPosts()
        ]);

        return new Response($html);
    }
}