<?php

declare(strict_types=1);

namespace App\Controller\Other;

use App\Repository\MissionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FlightController extends AbstractController
{
    #[Route(path: '/flight', name: 'page_flight')]
    public function index(
        MissionRepository $missionRepository,
    ): Response {
        $flights = $missionRepository->findAllMissions(page: 3);

        return $this->render('parts/flights/flight.html.twig', [
            'flights' => $flights,
        ]);
    }

    #[Route(path: '/flight/{id}', name: 'page_flight_show')]
    public function show(
        MissionRepository $missionRepository,
        int $id,
    ): Response {
        $flight = $missionRepository->find($id);

        return $this->render('parts/flights/flight-detail.html.twig', [
            'flight' => $flight,
        ]);
    }
}
