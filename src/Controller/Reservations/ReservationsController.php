<?php

namespace App\Controller\Reservations;

use App\Entity\Booking;
use App\Entity\Mission;
use App\Enum\BookingStatus;
use App\Form\ReservationFormType;
use App\Repository\BookingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReservationsController extends AbstractController
{
    #[Route('/reservations', name: 'dashboard_reservations')]
    #[Route('/reservations/admin', name: 'dashboard_reservations_admin')]
    public function index(BookingRepository $reservationRepository, Request $request): Response
    {
        $adminMode = 'dashboard_reservations_admin' === $request->get('_route');

        $bookings = $adminMode
            ? $reservationRepository->findAll()
            : $reservationRepository->findBy(['Customer' => $this->getUser()]);

        return $this->render('dashboard/reservations/index.html.twig', [
            'bookings' => $bookings,
            'admin_mode' => $adminMode,
        ]);
    }

    #[Route('/reservations/new', name: 'dashboard_reservations_new')]
    #[Route('/reservations/admin/new', name: 'admin_reservations_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $adminMode = 'admin_reservations_new' === $request->get('_route');
        $reservation = new Booking();

        $missions = $entityManager->getRepository(Mission::class)->findAll();
        $missionPrices = [];
        foreach ($missions as $mission) {
            $missionPrices[$mission->getId()] = $mission->getSeatPrice();
        }

        $form = $this->createForm(ReservationFormType::class, $reservation, [
            'email' => $this->getUser()?->getEmail(),
            'is_admin' => $adminMode,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($adminMode) {
                $customer = $form->get('customer')->getData();
                $reservation->setCustomer($customer);
            } else {
                $reservation->setCustomer($this->getUser());
            }

            $reservation->setDestination($reservation->getMission()?->getDestination());
            $reservation->setTotalPrice(
                ($reservation->getMission()?->getSeatPrice() ?? 0) * $reservation->getSeatCount()
            );
            $reservation->setStatus(BookingStatus::PENDING);

            $entityManager->persist($reservation);
            $entityManager->flush();

            return $this->redirectToRoute($adminMode ? 'dashboard_reservations_admin' : 'dashboard_reservations');
        }

        return $this->render('dashboard/reservations/new.html.twig', [
            'form' => $form->createView(),
            'admin_mode' => $adminMode,
            'missions' => $missionPrices,
        ]);
    }

    #[Route('/reservations/{id}/edit', name: 'dashboard_reservations_edit')]
    #[Route('/reservations/admin/{id}/edit', name: 'admin_reservations_edit')]
    public function edit(Booking $reservation, Request $request, EntityManagerInterface $entityManager): Response
    {
        $adminMode = 'admin_reservations_edit' === $request->get('_route');

        $missions = $entityManager->getRepository(Mission::class)->findAll();
        $missionPrices = [];
        foreach ($missions as $mission) {
            $missionPrices[$mission->getId()] = $mission->getSeatPrice();
        }

        $form = $this->createForm(ReservationFormType::class, $reservation, [
            'email' => $reservation->getCustomer()?->getEmail(),
            'created_at' => $reservation->getCreatedAt()?->format('d/m/Y H:i'),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservation->setDestination($reservation->getMission()?->getDestination());
            $reservation->setTotalPrice(
                ($reservation->getMission()?->getSeatPrice() ?? 0) * $reservation->getSeatCount()
            );

            $entityManager->flush();

            return $this->redirectToRoute(
                $adminMode ? 'dashboard_reservations_admin' : 'dashboard_reservations'
            );
        }

        return $this->render('dashboard/reservations/edit.html.twig', [
            'form' => $form->createView(),
            'booking' => $reservation,
            'admin_mode' => $adminMode,
            'missions' => $missionPrices, // Ajoute les prix des missions ici
        ]);
    }

    #[Route('/reservations/{id}/delete', name: 'dashboard_reservations_delete', methods: ['POST'])]
    #[Route('/reservations/admin/{id}/delete', name: 'admin_reservations_delete', methods: ['POST'])]
    public function delete(Booking $reservation, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Vérifie la validité du token CSRF
        if ($this->isCsrfTokenValid('delete'.$reservation->getId(), $request->request->get('_token'))) {
            // Supprime la réservation directement sans restriction
            $entityManager->remove($reservation);
            $entityManager->flush();

            $this->addFlash('success', 'La réservation a été supprimée avec succès.');
        } else {
            $this->addFlash('error', 'Token CSRF invalide, impossible de supprimer la réservation.');
        }

        // Redirection en fonction du contexte (admin ou client)
        return $this->redirectToRoute(
            'admin_reservations_delete' === $request->get('_route') ? 'dashboard_reservations_admin' : 'dashboard_reservations'
        );
    }

    #[Route('/reservations/{id}', name: 'dashboard_reservations_show')]
    #[Route('/reservations/admin/{id}', name: 'admin_reservations_show')]
    public function show(Booking $reservation, Request $request): Response
    {
        $adminMode = 'admin_reservations_show' === $request->get('_route');

        if (!$adminMode && $reservation->getCustomer() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous ne pouvez pas voir cette réservation.');
        }

        return $this->render('dashboard/reservations/show.html.twig', [
            'booking' => $reservation,
            'admin_mode' => $adminMode,
        ]);
    }
}
