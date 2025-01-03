<?php

declare(strict_types=1);

namespace App\Controller\Other;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LivesController extends AbstractController
{
    #[Route(path: '/lives', name: 'page_lives')]
    public function index(): Response
    {
        return $this->render('other/lives.html.twig');
    }
}
