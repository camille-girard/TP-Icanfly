<?php

namespace App\Controller\SpaceX;

use App\Service\SpaceXServiceAPI;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LaunchesController extends AbstractController
{
    public function __construct(private readonly SpaceXServiceAPI $spaceXServiceAPI)
    {
    }

    #[Route('/launches', name: 'app_launches')]
    public function index(
        SpaceXServiceAPI $spaceXServiceAPI,
    ): Response {
        $launchesLatest = $this->spaceXServiceAPI->getLatestLaunches();
        $launchesUpcoming = $this->spaceXServiceAPI->getUpcomingLaunches();
        $launchesPast = $this->spaceXServiceAPI->getPastLaunches();

        return $this->render('space_x/launches.html.twig', [
            'launchesLatest' => $launchesLatest,
            'launchesUpcoming' => $launchesUpcoming,
            'launchesPast' => $launchesPast,
        ]);
    }

    #[Route('/launches/{id}', name: 'app_launch')]
    public function show(
        string $id,
    ): Response {
        $launch = $this->spaceXServiceAPI->getLaunchDetails($id);

        return $this->render('space_x/launch_detail.html.twig', [
            'launch' => $launch,
        ]);
    }
}
