<?php

namespace App\Controller\Reservations;

use App\Entity\Booking;
use App\Form\ReservationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ReservationsController extends AbstractController
{
    #[Route('/dashboard/reservations', name: 'dashboard_reservations')]
    public function index(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        $bookings = $em->getRepository(Booking::class)->findBy(['Customer' => $user], ['createdAt' => 'DESC']);

        return $this->render('dashboard/reservations/index.html.twig', [
            'bookings' => $bookings,
        ]);
    }

    #[Route('/dashboard/reservations/admin', name: 'dashboard_reservations_admin')]
    #[IsGranted('ROLE_SUPER_ADMIN')]
    public function adminIndex(EntityManagerInterface $em): Response
    {
        $bookings = $em->getRepository(Booking::class)->findBy([], ['createdAt' => 'DESC']);

        return $this->render('dashboard/reservations/index_admin.html.twig', [
            'bookings' => $bookings,
        ]);
    }

    #[Route('/dashboard/reservations/admin/new', name: 'admin_reservations_new')]
    #[IsGranted('ROLE_SUPER_ADMIN')]
    public function adminNew(Request $request, EntityManagerInterface $em): Response
    {
        $booking = new Booking();
        $form = $this->createForm(ReservationFormType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($booking);
            $em->flush();

            return $this->redirectToRoute('dashboard_reservations_admin');
        }

        return $this->render('dashboard/reservations/new_admin.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/dashboard/reservations/admin/{id}/edit', name: 'admin_reservations_edit')]
    #[IsGranted('ROLE_SUPER_ADMIN')]
    public function adminEdit(Booking $booking, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ReservationFormType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('dashboard_reservations_admin');
        }

        return $this->render('dashboard/reservations/edit_admin.html.twig', [
            'form' => $form->createView(),
            'booking' => $booking,
        ]);
    }

    #[Route('/dashboard/reservations/admin/{id}', name: 'admin_reservations_show', methods: ['GET'])]
    #[IsGranted('ROLE_SUPER_ADMIN')]
    public function adminShow(Booking $booking): Response
    {
        return $this->render('dashboard/reservations/show_admin.html.twig', [
            'booking' => $booking,
        ]);
    }

    #[Route('/dashboard/reservations/admin/{id}/delete', name: 'admin_reservations_delete', methods: ['POST'])]
    #[IsGranted('ROLE_SUPER_ADMIN')]
    public function adminDelete(Request $request, Booking $booking, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $booking->getId(), $request->request->get('_token'))) {
            $em->remove($booking);
            $em->flush();
        }

        return $this->redirectToRoute('dashboard_reservations_admin', [], Response::HTTP_SEE_OTHER);
    }
}
