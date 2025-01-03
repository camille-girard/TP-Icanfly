<?php

declare(strict_types=1);

namespace App\Controller\Other;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AboutUsController extends AbstractController
{
    #[Route(path: '/about-us', name: 'page_about_us')]
    public function index(): Response
    {
        return $this->render('other/about-us.html.twig');
    }
}
