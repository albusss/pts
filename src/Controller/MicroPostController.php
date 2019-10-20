<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\MicroPost;
use App\Entity\User;
use App\Form\MicroPostType;
use App\Repository\MicroPostRepository;
use App\Security\MicroPostVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

/**
 * @Route("/micro-post")
 */
final class MicroPostController extends AbstractController
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var MicroPostRepository
     */
    private $repository;

    /**
     * @var \Twig\Environment
     */
    private $twig;

    /**
     * MicroPostController constructor.
     *
     * @param \App\Repository\MicroPostRepository $repository
     * @param \Twig\Environment $twig
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     */
    public function __construct(
        MicroPostRepository $repository,
        Environment $twig,
        EntityManagerInterface $entityManager
    ) {
        $this->repository = $repository;
        $this->twig = $twig;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/add", name="micro_post_add")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function add(Request $request)
    {
        if ($this->isGranted(User::ROLE_USER) === false) {
            return $this->redirectToRoute('security_login');
        }
        $user = $this->getUser();
        $microPost = new  MicroPost();
        $microPost
            ->setTime(new \DateTime())
            ->setUser($user);

        $form = $this->createForm(MicroPostType::class, $microPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($microPost);
            $this->entityManager->flush();

            return new RedirectResponse($this->generateUrl('micro_post_index'));
        }

        return new Response(
            $this->twig->render('micro-post/add.html.twig', [
                'form' => $form->createView()
            ])
        );
    }

    /**
     * @Route("/delete/{id}", name="micro_post_delete")
     * @param \App\Entity\MicroPost $post
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(MicroPost $post)
    {
        $this->denyAccessUnlessGranted(MicroPostVoter::EDIT, $post);

        $this->entityManager->remove($post);
        $this->entityManager->flush();

        $this->addFlash('notice', 'micro post was deleted');

        return new RedirectResponse($this->generateUrl('micro_post_index'));
    }

    /**
     * @Route("/edit/{id}", name="micro_post_edit")
     * @param \App\Entity\MicroPost $post
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function edit(MicroPost $post, Request $request)
    {
        $this->denyAccessUnlessGranted(MicroPostVoter::EDIT, $post);

        $form = $this->createForm(MicroPostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return new RedirectResponse($this->generateUrl('micro_post_index'));
        }

        return new Response(
            $this->twig->render('micro-post/add.html.twig', [
                'form' => $form->createView()
            ])
        );
    }

    /**
     * @Route("/", name="micro_post_index")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index(): Response
    {
        $html = $this->twig->render('micro-post/index.html.twig', [
            'posts' => $this->repository->findBy([], ['time' => 'DESC'])
        ]);

        return new Response($html);
    }

    /**
     * @Route("/{id}", name="micro_post_post")
     * @param \App\Entity\MicroPost $post
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function post(MicroPost $post)
    {
        return new Response(
            $this->twig->render('micro-post/post.html.twig', [
                'post' => $post
            ])
        );
    }
}
