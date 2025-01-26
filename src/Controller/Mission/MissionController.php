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
use Symfony\Component\Security\Http\Attribute\IsGranted;


class MissionController extends AbstractController
{
    #[Route(path: '/mission', name: 'page_mission')]
    public function index(Request $request, MissionRepository $missionRepository): Response
    {
        $destination = $request->query->get('destination');
        $date = $request->query->get('date');
        $price = $request->query->get('price');

        $queryBuilder = $missionRepository->createQueryBuilder('m');

        // Filtre par destination
        if ($destination) {
            $queryBuilder->andWhere('m.destination = :destination')
                ->setParameter('destination', $destination);
        }

        // Filtre par date
        if ($date) {
            $queryBuilder->andWhere('m.date = :date')
                ->setParameter('date', new \DateTime($date));
        }

        // Filtre par gamme de prix
        if ($price) {
            if ($price === 'cheap') {
                $queryBuilder->andWhere('m.seatPrice < 1000');
            } elseif ($price === 'medium') {
                $queryBuilder->andWhere('m.seatPrice >= 1000 AND m.seatPrice <= 5000');
            } elseif ($price === 'expensive') {
                $queryBuilder->andWhere('m.seatPrice > 5000');
            }
        }

        $missions = $queryBuilder->getQuery()->getResult();

        // Récupérer toutes les destinations pour le filtre
        $destinations = $missionRepository->createQueryBuilder('m')
            ->select('DISTINCT m.destination')
            ->getQuery()
            ->getResult();

        return $this->render('parts/missions/mission.html.twig', [
            'missions' => $missions,
            'destinations' => array_column($destinations, 'destination'),
        ]);
    }

    #[Route('/mission/{id}', name: 'mission_detail', methods: ['GET'])]
    public function detail(Mission $mission): Response
    {
        return $this->render('parts/missions/mission-detail.html.twig', [
            'mission' => $mission,
        ]);
    }

    #[IsGranted('ROLE_OPERATOR')]
    #[Route(path: '/dashboard/mission', name: 'dashboard_mission_index', methods: ['GET'])]
    public function index_dashboard(MissionRepository $missionRepository): Response
    {
        return $this->render('dashboard/mission/index.html.twig', [
            'missions' => $missionRepository->findAll(),
        ]);
    }

    #[IsGranted('ROLE_OPERATOR')]
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


    #[IsGranted('ROLE_OPERATOR')]
    #[Route('/dashboard/mission/{id}', name: 'dashboard_mission_show', methods: ['GET'])]
    public function show(Mission $mission): Response
    {
        return $this->render('dashboard/mission/show.html.twig', [
            'mission' => $mission,
        ]);
    }

    #[IsGranted('ROLE_OPERATOR')]
    #[Route('/dashboard/mission/{id}/edit', name: 'dashboard_mission_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Mission $mission, EntityManagerInterface $entityManager): Response
    {
        // Determine the type of mission
        $currentType = $mission instanceof ScientificMission ? 'scientific' : 'travel';

        // Create the form with current type
        $form = $this->createForm(MissionType::class, $mission, [
            'current_type' => $currentType,
        ]);

        // Set default values for non-mapped fields
        if ($currentType === 'scientific') {
            $form->get('specialEquipement')->setData($mission->getSpecialEquipement());
            $form->get('objective')->setData($mission->getObjective());
        } elseif ($currentType === 'travel') {
            $form->get('hasGuide')->setData($mission->hasGuide());
            $form->get('activities')->setData($mission->getActivities());
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Update special fields
            if ($currentType === 'scientific') {
                $mission->setSpecialEquipement($form->get('specialEquipement')->getData());
                $mission->setObjective($form->get('objective')->getData());
            } elseif ($currentType === 'travel') {
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

    #[IsGranted('ROLE_OPERATOR')]
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
