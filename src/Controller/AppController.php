<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

final class AppController extends AbstractController
{
    /**
     *
     * @Route("/")
     * @return void
     *
     */
    public function index()
    {
        return $this->render('base.html.twig');
    }
}
