<?php

declare(strict_types=1);

namespace App\Controller\Other;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FlightController extends AbstractController
{
    #[Route(path: '/flight', name: 'page_flight')]
    public function index(): Response
    {
        return $this->render('parts/flights/flight.html.twig');
    }
}
