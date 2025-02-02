<?php

namespace App\Controller;

use App\Repository\BookingRepository;
use App\Repository\MissionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(BookingRepository $bookingRepository, MissionRepository $missionRepository): Response
    {
        $user = $this->getUser();

        $emailNotConfirmed = in_array('ROLE_USER', $user->getRoles()) && !$user->isVerified();

        if (!$emailNotConfirmed) {
            $reservations = $bookingRepository->findBy(['Customer' => $user]);

            $missions = $missionRepository->findUpcomingMissions(new \DateTime());

            $upcomingMissions = [];
            foreach ($missions as $mission) {
                $now = new \DateTime();
                $interval = $now->diff($mission->getDate());
                $countdown = $interval->format('%a jours, %h heures');

                $upcomingMissions[] = [
                    'destination' => $mission->getDestination(),
                    'countdown' => $countdown,
                ];
            }

            $quickActions = [
                ['name' => 'Réserver un vol', 'link' => $this->generateUrl('dashboard_reservations_new')],
                ['name' => 'Voir les missions', 'link' => $this->generateUrl('page_mission')],
            ];
        }

        return $this->render('dashboard/index.html.twig', [
            'emailNotConfirmed' => $emailNotConfirmed,
            'reservations' => $reservations ?? [],
            'upcomingMissions' => $upcomingMissions ?? [],
            'quickActions' => $quickActions ?? [],
        ]);
    }

}
