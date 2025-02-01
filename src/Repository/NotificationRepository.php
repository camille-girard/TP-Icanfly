<?php

namespace App\Repository;

use App\Entity\Notification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;

/**
 * @extends ServiceEntityRepository<Notification>
 */
class NotificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Notification::class);
    }

    public function saveNotification(User $user, string $content): void
    {
        $notification = new Notification();
        $notification->setContent($content);
        $notification->setSentDate(new \DateTime());
        $notification->setCustomer($user);

        $em = $this->getEntityManager();
        $em->persist($notification);
        $em->flush();
    }
}
