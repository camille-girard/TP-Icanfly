<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\NotificationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use App\Security\EmailVerifier;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


#[IsGranted('ROLE_CLIENT')]
#[Route('/dashboard/user')]
final class UserController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route(name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('dashboard/user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, EmailVerifier $emailVerifier, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setRoles(['ROLE_USER']);

            /** @var string $password */
            $password = $form->get('password')->getData();

            // encode the plain password
            $user->setPassword($userPasswordHasher->hashPassword($user, $password));

            $entityManager->persist($user);
            $entityManager->flush();

            // Envoyer un e-mail de confirmation
            $emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('mailer@icanfly.com', 'ICanFly'))
                    ->to((string) $user->getEmail())
                    ->subject('Veuillez confirmer votre e-mail')
                    ->htmlTemplate('mail/confirmation_email.html.twig')
            );

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        if (!$this->isGranted('ROLE_ADMIN') && $this->getUser() !== $user) {
            throw $this->createAccessDeniedException('Vous ne pouvez pas voir les informations d\'autres utilisateurs.');
        }

        return $this->render('dashboard/user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager, NotificationService $notificationService): Response
    {
        if (!$this->isGranted('ROLE_ADMIN') && $this->getUser() !== $user) {
            throw $this->createAccessDeniedException('Vous ne pouvez pas modifier les informations d\'autres utilisateurs.');
        }

        $originalRoles = $user->getRoles();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$user->isVerified()) {
                $this->addFlash('error', 'Vous ne pouvez pas modifier les rôles d\'un utilisateur non vérifié.');
                $user->setRoles($originalRoles);
            }

            $entityManager->flush();

            $notificationService->sendNotification(
                $user,
                'Votre profil a été modifié avec succès.',
                'mail/notification_email.html.twig',
                []
            );

            return $this->redirectToRoute('app_user_edit', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_ADMIN') && $this->getUser() !== $user) {
            throw $this->createAccessDeniedException('Vous ne pouvez pas supprimer d\'autres utilisateurs.');
        }

        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
