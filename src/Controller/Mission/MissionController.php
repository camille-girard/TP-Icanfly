<?php

declare(strict_types=1);

namespace App\Controller\Mission;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MissionController extends AbstractController
{
    #[Route('/mission')]
    public function index(): Response
    {
        return $this->render('parts/missions/mission.html.twig');
    }
}
