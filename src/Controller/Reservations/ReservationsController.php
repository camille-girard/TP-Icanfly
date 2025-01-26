<?php

namespace App\Controller\Reservations;

use App\Entity\Booking;
use App\Entity\Mission;
use App\Enum\BookingStatus;
use App\Form\ReservationFormType;
use App\Repository\BookingRepository;
use App\Service\StripeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Service\NotificationService;

#[IsGranted('ROLE_CLIENT')]
class ReservationsController extends AbstractController
{
    #[Route('/dashboard/reservations', name: 'dashboard_reservations')]
    #[Route('/dashboard/reservations/admin', name: 'dashboard_reservations_admin')]
    public function index(BookingRepository $reservationRepository, Request $request): Response
    {
        $adminMode = $request->get('_route') === 'dashboard_reservations_admin';

        if ($adminMode) {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
        }

        $bookings = $adminMode
            ? $reservationRepository->findAll()
            : $reservationRepository->findBy(['Customer' => $this->getUser()]);

        return $this->render('dashboard/reservations/index.html.twig', [
            'bookings' => $bookings,
            'admin_mode' => $adminMode,
        ]);
    }

    #[Route('/dashboard/reservations/new', name: 'dashboard_reservations_new')]
    #[Route('/dashboard/reservations/admin/new', name: 'admin_reservations_new')]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        StripeService $stripeService,
        SessionInterface $session,
        NotificationService $notificationService
    ): Response {
        $adminMode = $request->get('_route') === 'admin_reservations_new';

        if ($adminMode) {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
        }

        $missionId = $request->query->get('id');

        $mission = null;

        if ($missionId) {
            $mission = $entityManager->getRepository(Mission::class)->find($missionId);
            if (!$mission) {
                throw $this->createNotFoundException('Mission non trouvée.');
            }
        }

        $reservation = new Booking();
        if ($mission) {
            $reservation->setMission($mission);
        }

        $missions = $entityManager->getRepository(Mission::class)->findAll();
        $missionPrices = [];
        foreach ($missions as $mission) {
            $missionPrices[$mission->getId()] = $mission->getSeatPrice();
        }

        $form = $this->createForm(ReservationFormType::class, $reservation, [
            'email' => $this->getUser()?->getEmail(),
            'is_admin' => $adminMode,
            'is_new' => !$adminMode, // Spécifie si nous sommes dans une création côté client
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Définir les champs nécessaires en fonction du mode
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

            // Gérer le paiement pour les clients
            if (!$adminMode && $form->has('save_and_pay') && $form->get('save_and_pay')->isClicked()) {
                // Créez une session Stripe
                $checkoutSession = $stripeService->createCheckoutSession(
                    'eur',
                    $reservation->getMission()->getDestination(),
                    $reservation->getTotalPrice() * 100,
                    $this->generateUrl('dashboard_reservations', [], 0), // Success URL
                    $this->generateUrl('dashboard_reservations', [], 0)  // Cancel URL
                );

                // Enregistrer l'ID de session Stripe dans la session Symfony
                $session->set('stripe_session_id_' . $reservation->getId(), $checkoutSession->id);

                // Rediriger directement vers Stripe
                return $this->redirect($checkoutSession->url);
            }

            $notificationService->sendNotification(
                $reservation->getCustomer(), // The User entity
                'Votre réservation pour la mission ' .  $reservation->getMission()->getDestination() . ' a été enregistrée avec succès.',
                'mail/notification_email.html.twig',
                [
                    'missionName' => $reservation->getMission()->getDestination(),
                    'reservationDate' => (new \DateTime())->format('d/m/Y H:i'),
                ]
            );

            // Rediriger après enregistrement
            return $this->redirectToRoute($adminMode ? 'dashboard_reservations_admin' : 'dashboard_reservations');
        }

        return $this->render('dashboard/reservations/new.html.twig', [
            'form' => $form->createView(),
            'admin_mode' => $adminMode,
            'missions' => $missionPrices,
            'selected_mission' => $mission,
        ]);
    }

    #[Route('/dashboard/reservations/{id}/edit', name: 'dashboard_reservations_edit')]
    #[Route('/dashboard/reservations/admin/{id}/edit', name: 'admin_reservations_edit')]
    public function edit(Booking $reservation, Request $request, EntityManagerInterface $entityManager, NotificationService $notificationService): Response
    {
        $adminMode = $request->get('_route') === 'admin_reservations_edit';

        if ($adminMode) {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
        }

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

            $notificationService->sendNotification(
                $reservation->getCustomer(), // The User entity
                'Votre réservation pour la mission ' .  $reservation->getMission()->getDestination() . ' a été modifiée.',
                'mail/notification_email.html.twig',
                [
                    'missionName' => $reservation->getMission()->getDestination(),
                ]
            );

            return $this->redirectToRoute(
                $adminMode ? 'dashboard_reservations_admin' : 'dashboard_reservations'
            );
        }

        return $this->render('dashboard/reservations/edit.html.twig', [
            'form' => $form->createView(),
            'booking' => $reservation,
            'status' => $reservation->getStatus()?->value, // Passez la valeur brute pour Twig
            'admin_mode' => $adminMode,
            'missions' => $missionPrices,
        ]);
    }

    #[Route('/dashboard/reservations/{id}/delete', name: 'dashboard_reservations_delete', methods: ['POST'])]
    #[Route('/dashboard/reservations/admin/{id}/delete', name: 'admin_reservations_delete', methods: ['POST'])]
    public function delete(Booking $reservation, Request $request, EntityManagerInterface $entityManager, NotificationService $notificationService): Response
    {
        if ($this->isCsrfTokenValid('delete' . $reservation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reservation);
            $entityManager->flush();

            $this->addFlash('success', 'La réservation a été supprimée avec succès.');
            $notificationService->sendNotification(
                $reservation->getCustomer(), // The User entity
                'Votre réservation pour la mission ' .  $reservation->getMission()->getDestination() . ' a été annulé.',
                'mail/notification_email.html.twig',
                [
                    'missionName' => $reservation->getMission()->getDestination(),
                ]
            );
        } else {
            $this->addFlash('error', 'Token CSRF invalide, impossible de supprimer la réservation.');
        }

        return $this->redirectToRoute(
            $request->get('_route') === 'admin_reservations_delete' ? 'dashboard_reservations_admin' : 'dashboard_reservations'
        );
    }

    #[Route('/dashboard/reservations/{id}', name: 'dashboard_reservations_show')]
    #[Route('/dashboard/reservations/admin/{id}', name: 'admin_reservations_show')]
    public function show(Booking $reservation, Request $request): Response
    {
        $adminMode = $request->get('_route') === 'admin_reservations_show';

        if ($adminMode) {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
        }

        if (!$adminMode && $reservation->getCustomer() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous ne pouvez pas voir cette réservation.');
        }

        return $this->render('dashboard/reservations/show.html.twig', [
            'booking' => $reservation,
            'admin_mode' => $adminMode,
        ]);
    }

    #[Route('/dashboard/reservations/{id}/payment', name: 'reservation_payment')]
    public function payment(Booking $booking, StripeService $stripeService, SessionInterface $session): Response
    {
        if (!$this->getUser() || $booking->getCustomer() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à ce paiement.');
        }

        if ($booking->getStatus() !== BookingStatus::PENDING) {
            throw $this->createAccessDeniedException('Le paiement est déjà effectué ou annulé.');
        }

        $checkoutSession = $stripeService->createCheckoutSession(
            'eur',
            $booking->getMission()->getDestination(),
            $booking->getTotalPrice() * 100,
            $this->generateUrl('dashboard_reservations', [], 0),
            $this->generateUrl('dashboard_reservations', [], 0)
        );

        $session->set('stripe_session_id_' . $booking->getId(), $checkoutSession->id);

        return $this->redirect($checkoutSession->url);
    }

    #[Route('/stripe/webhook', name: 'stripe_webhook', methods: ['POST'])]
    public function stripeWebhook(Request $request, BookingRepository $bookingRepo, SessionInterface $session, EntityManagerInterface $entityManager): Response
    {
        $payload = $request->getContent();
        $sigHeader = $request->headers->get('Stripe-Signature');
        $secret = $_ENV['STRIPE_WEBHOOK_SECRET'];

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $secret);

            if ($event->type === 'checkout.session.completed') {
                $sessionStripeId = $event->data->object->id;

                foreach ($session->all() as $key => $value) {
                    if (str_contains($key, 'stripe_session_id_') && $value === $sessionStripeId) {
                        $bookingId = str_replace('stripe_session_id_', '', $key);
                        $booking = $bookingRepo->find($bookingId);

                        if ($booking) {
                            $booking->setStatus(BookingStatus::CONFIRMED);
                            $entityManager->flush();
                        }
                    }
                }
            }
        } catch (\UnexpectedValueException | \Stripe\Exception\SignatureVerificationException $e) {
            return new Response('Webhook Error: ' . $e->getMessage(), 400);
        }

        return new Response('Success', 200);
    }
}
