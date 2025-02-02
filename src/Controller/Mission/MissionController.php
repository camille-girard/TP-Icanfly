<?php

namespace App\Controller\Mission;

use App\Entity\Mission;
use App\Entity\ScientificMission;
use App\Entity\TouristMission;
use App\Form\MissionType;
use App\Repository\BookingRepository;
use App\Repository\MissionRepository;
use App\Service\NotificationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Security\Voter\MissionVoter;

class MissionController extends AbstractController
{
    #[Route(path: '/mission', name: 'page_mission')]
    public function index(Request $request, MissionRepository $missionRepository): Response
    {
        $destination = $request->query->get('destination');
        $date = $request->query->get('date');
        $price = $request->query->get('price');

        $missions = $missionRepository->findFilteredMissions($destination, $date, $price);
        $destinations = array_column($missionRepository->findDistinctDestinations(), 'destination');

        return $this->render('parts/missions/mission.html.twig', [
            'missions' => $missions,
            'destinations' => $destinations,
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
        $user = $this->getUser();

        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            $missions = $missionRepository->findAll();
        } else {
            $missions = $missionRepository->findByOperator($user->getId());
        }

        return $this->render('dashboard/mission/index.html.twig', [
            'missions' => $missions,
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
            if ('scientific' === $type) {
                $mission = new ScientificMission();
                $mission->setSpecialEquipement($form->get('specialEquipement')->getData());
                $mission->setObjective($form->get('objective')->getData());
            } elseif ('travel' === $type) {
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
            $mission->setOperator($this->getUser());

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
    public function edit(
        Request $request,
        Mission $mission,
        EntityManagerInterface $entityManager,
        NotificationService $notificationService,
        BookingRepository $bookingRepository,
    ): Response {

        $this->denyAccessUnlessGranted('MISSION_EDIT', $mission);

        $currentType = $mission instanceof ScientificMission ? 'scientific' : 'travel';

        $form = $this->createForm(MissionType::class, $mission, [
            'current_type' => $currentType,
        ]);

        // Set default values for non-mapped fields
        if ('scientific' === $currentType) {
            $form->get('specialEquipement')->setData($mission->getSpecialEquipement());
            $form->get('objective')->setData($mission->getObjective());
        } elseif ('travel' === $currentType) {
            $form->get('hasGuide')->setData($mission->hasGuide());
            $form->get('activities')->setData($mission->getActivities());
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ('scientific' === $currentType) {
                $mission->setSpecialEquipement($form->get('specialEquipement')->getData());
                $mission->setObjective($form->get('objective')->getData());
            } elseif ('travel' === $currentType) {
                $mission->setHasGuide($form->get('hasGuide')->getData());
                $mission->setActivities($form->get('activities')->getData());
            }

            $entityManager->flush();

            // Notify all users who booked the mission
            $bookings = $bookingRepository->findBy(['Mission' => $mission]);
            foreach ($bookings as $booking) {
                $customer = $booking->getCustomer();
                if ($customer) {
                    $notificationService->sendNotification(
                        $customer,
                        sprintf('La mission "%s" a été modifiée.', $mission->getDestination()),
                        'mail/notification_email.html.twig',
                        [
                            'missionName' => $mission->getDestination(),
                            'changeDate' => (new \DateTime())->format('d/m/Y H:i'),
                        ]
                    );
                }
            }

            return $this->redirectToRoute('dashboard_mission_index');
        }

        return $this->render('dashboard/mission/edit.html.twig', [
            'form' => $form->createView(),
            'mission' => $mission,
        ]);
    }

    #[IsGranted('ROLE_OPERATOR')]
    #[Route('/dashboard/mission/{id}', name: 'dashboard_mission_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Mission $mission,
        EntityManagerInterface $entityManager,
        NotificationService $notificationService,
        BookingRepository $bookingRepository,
    ): Response {
        $this->denyAccessUnlessGranted('MISSION_DELETE', $mission);

        if ($this->isCsrfTokenValid('delete'.$mission->getId(), $request->get('_token'))) {
            // Notify all users who booked the mission
            $bookings = $bookingRepository->findBy(['Mission' => $mission]);
            foreach ($bookings as $booking) {
                $customer = $booking->getCustomer();
                if ($customer) {
                    $notificationService->sendNotification(
                        $customer,
                        sprintf('La mission "%s" a été annulée.', $mission->getDestination()),
                        'mail/notification_email.html.twig',
                        [
                            'missionName' => $mission->getDestination(),
                            'cancelDate' => (new \DateTime())->format('d/m/Y H:i'),
                        ]
                    );
                }
            }

            $entityManager->remove($mission);
            $entityManager->flush();

            $this->addFlash('success', 'La mission a été supprimée avec succès.');
        } else {
            $this->addFlash('error', 'Token CSRF invalide, impossible de supprimer la mission.');
        }

        return $this->redirectToRoute('dashboard_mission_index', [], Response::HTTP_SEE_OTHER);
    }
}
