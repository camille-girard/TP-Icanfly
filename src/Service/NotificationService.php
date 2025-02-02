<?php

namespace App\Service;

use App\Entity\Notification;
use App\Repository\NotificationRepository;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

class NotificationService
{
    private MailerInterface $mailer;
    private EntityManagerInterface $entityManager;
    private NotificationRepository $notificationRepository;

    public function __construct(MailerInterface $mailer, EntityManagerInterface $entityManager, NotificationRepository $notificationRepository)
    {
        $this->mailer = $mailer;
        $this->entityManager = $entityManager;
        $this->notificationRepository = $notificationRepository;
    }

    /**
     * Sends a notification email and logs it in the database.
     *
     * @param array<string, mixed> $templateData
     *
     * @throws TransportExceptionInterface
     */
    public function sendNotification(User $user, string $content, string $template = 'mail/notification_email.html.twig', array $templateData = []): void
    {
        // Build the email using TemplatedEmail
        $email = (new TemplatedEmail())
        ->from('contact@icanfly.com')
        ->to($user->getEmail())
        ->subject('Nouvelle notification')
        ->htmlTemplate($template)
        ->context(array_merge($templateData, [
            'content' => $content,
            'user' => $user,
        ]));

        // Send the email
        $this->mailer->send($email);

        $this->notificationRepository->saveNotification($user, $content);
    }
}
