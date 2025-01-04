<?php

namespace App\Controller\Reservations;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ReservationsController extends AbstractController
{
    #[Route('/dashboard/reservations', name: 'dashboard_reservations')]
    public function index(): Response
    {
        return $this->render('dashboard/reservations/index.html.twig', [
            'controller_name' => 'ReservationsController',
        ]);
    }
}
