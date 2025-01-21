<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(): Response
    {
        // Données fictives pour l'instant
        $upcomingMissions = [
            ['name' => 'Mission to Mars', 'countdown' => '3 jours'],
            ['name' => 'Lunar Orbital', 'countdown' => '10 jours'],
        ];

        $quickActions = [
            ['name' => 'Réserver un vol', 'link' => '/reserve'],
            ['name' => 'Voir les missions', 'link' => '/missions'],
        ];

        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
            'upcomingMissions' => $upcomingMissions,
            'quickActions' => $quickActions,
        ]);
    }
}
