<?php

namespace App\Command;

use App\Repository\BookingRepository;
use App\Service\NotificationService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:send-mission-reminder',
    description: 'Envoie un rappel aux clients 24h avant leur mission.',
)]
class SendMissionReminderCommand extends Command
{
    private BookingRepository $bookingRepository;
    private NotificationService $notificationService;

    public function __construct(BookingRepository $bookingRepository, NotificationService $notificationService)
    {
        parent::__construct();
        $this->bookingRepository = $bookingRepository;
        $this->notificationService = $notificationService;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $now = new \DateTime();
        $tomorrow = (clone $now)->modify('+1 day');

        $bookings = $this->bookingRepository->findUpcomingMissions($tomorrow);

        if (!$bookings) {
            $output->writeln('<info>Aucune mission à venir dans 24h.</info>');
            return Command::SUCCESS;
        }

        foreach ($bookings as $booking) {
            $customer = $booking->getCustomer();
            $mission = $booking->getMission();

            if ($customer && $mission) {
                $this->notificationService->sendNotification(
                    $customer,
                    sprintf('Rappel : Votre mission "%s" aura lieu demain !', $mission->getDestination()),
                    'mail/mission_reminder.html.twig',
                    [
                        'missionName' => $mission->getDestination(),
                        'date' => $mission->getDate()->format('d/m/Y H:i'),
                    ]
                );

                $output->writeln("<info>Rappel envoyé à {$customer->getEmail()} pour la mission {$mission->getDestination()}.</info>");
            }
        }

        return Command::SUCCESS;
    }
}
