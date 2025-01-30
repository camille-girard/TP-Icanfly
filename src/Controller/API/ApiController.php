<?php

declare(strict_types=1);

namespace App\Controller\API;

use App\Entity\Mission;
use App\Repository\MissionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class ApiController extends AbstractController
{
    #[Route('/api/missions', name: 'api_missions', methods: ['GET'])]
    public function index(
        MissionRepository $missionRepository,
    ): JsonResponse {
        $missions = array_map(fn (Mission $mission) => $mission->toArray(), $missionRepository->findAll());

        return new JsonResponse($missions, 200);
    }

    #[Route('/api/missions/{id}', name: 'api_mission', methods: ['GET'])]
    public function show(
        MissionRepository $missionRepository,
        int $id,
    ): JsonResponse {
        $mission = $missionRepository->find($id);
        if (!$mission) {
            return new JsonResponse(['error' => 'Mission not found'], 404);
        }
        $mission = $mission->toArray();

        return new JsonResponse($mission);
    }
}
