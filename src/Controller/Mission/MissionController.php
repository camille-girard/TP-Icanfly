<?php

declare(strict_types=1);

namespace App\Controller\Mission;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MissionController extends AbstractController
{
    #[Route(path: '/mission', name: 'page_mission')]
    public function index(): Response
    {
        return $this->render('parts/missions/mission.html.twig');
    }

    #[Route(path: '/dashboard/mission', name: 'dashboard_mission')]
    public function index_dashboard(): Response
    {
        return $this->render('dashboard/missions/missions.html.twig');
    }
}
