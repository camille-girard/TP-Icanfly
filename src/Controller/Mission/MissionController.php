<?php

namespace App\Controller\Mission;

use App\Entity\Mission;
use App\Entity\ScientificMission;
use App\Entity\TouristMission;
use App\Form\MissionType;
use App\Repository\MissionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class MissionController extends AbstractController
{
    #[Route(path: '/mission', name: 'page_mission')]
    public function index(): Response
    {
        return $this->render('parts/missions/mission.html.twig');
    }

    #[Route(path: '/dashboard/mission', name: 'dashboard_mission_index', methods: ['GET'])]
    public function index_dashboard(MissionRepository $missionRepository): Response
    {
        return $this->render('dashboard/mission/index.html.twig', [
            'missions' => $missionRepository->findAll(),
        ]);
    }

    #[Route('/dashboard/mission/new', name: 'dashboard_mission_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MissionType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $type = $form->get('type')->getData(); // Get the selected type
            $mission = null;

            // Create the appropriate child entity
            if ($type === 'scientific') {
                $mission = new ScientificMission();
                $mission->setSpecialEquipement($form->get('specialEquipement')->getData());
                $mission->setObjective($form->get('objective')->getData());
            } elseif ($type === 'travel') {
                $mission = new TouristMission();
                $mission->setHasGuide($form->get('hasGuide')->getData());
                $mission->setActivities($form->get('activities')->getData());
            }

            // Set common fields
            $mission->setDestination($form->get('destination')->getData());
            $mission->setDescription($form->get('description')->getData());
            $mission->setDate($form->get('date')->getData());
            $mission->setSeatPrice($form->get('seatPrice')->getData());
            $mission->setImage($form->get('image')->getData());
            $mission->setDuration($form->get('duration')->getData());

            $entityManager->persist($mission);
            $entityManager->flush();

            return $this->redirectToRoute('dashboard_mission_index');
        }

        return $this->render('dashboard/mission/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/dashboard/mission/{id}', name: 'dashboard_mission_show', methods: ['GET'])]
    public function show(Mission $mission): Response
    {
        return $this->render('dashboard/mission/show.html.twig', [
            'mission' => $mission,
        ]);
    }

    #[Route('/dashboard/mission/{id}/edit', name: 'dashboard_mission_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Mission $mission, EntityManagerInterface $entityManager): Response
    {
        // Determine the current type of mission (scientific or travel)
        $type = $mission instanceof ScientificMission ? 'scientific' : 'travel';

        $form = $this->createForm(MissionType::class, $mission, [
            'current_type' => $type,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Update child-specific fields based on the type
            if ($mission instanceof ScientificMission) {
                $mission->setSpecialEquipement($form->get('specialEquipement')->getData());
                $mission->setObjective($form->get('objective')->getData());
            } elseif ($mission instanceof TouristMission) {
                $mission->setHasGuide($form->get('hasGuide')->getData());
                $mission->setActivities($form->get('activities')->getData());
            }

            $entityManager->flush();

            return $this->redirectToRoute('dashboard_mission_index');
        }

        return $this->render('dashboard/mission/edit.html.twig', [
            'form' => $form->createView(),
            'mission' => $mission,
        ]);
    }

    #[Route('/dashboard/mission/{id}', name: 'dashboard_mission_delete', methods: ['POST'])]
    public function delete(Request $request, Mission $mission, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mission->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($mission);
            $entityManager->flush();
        }

        return $this->redirectToRoute('dashboard_mission_index', [], Response::HTTP_SEE_OTHER);
    }
}
