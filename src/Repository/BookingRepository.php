<?php

namespace App\Repository;

use App\Entity\Booking;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Booking>
 */
class BookingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Booking::class);
    }

    /**
     * Retourne une requête paginée des réservations.
     *
     * @param bool  $adminMode indique si l'utilisateur est administrateur
     * @param mixed $user      L'utilisateur connecté (peut être null pour l'admin)
     *
     * @return Query la requête Doctrine pour la pagination
     */
    public function findAllPaginated(bool $adminMode, $user = null): Query
    {
        $qb = $this->createQueryBuilder('b')
            ->orderBy('b.createdAt', 'DESC');

        if (!$adminMode && $user) {
            $qb->where('b.Customer = :user')
                ->setParameter('user', $user);
        }

        return $qb->getQuery();
    }

    /**
     * Recherche des réservations par statut.
     *
     * @param string $status le statut de la réservation
     *
     * @return Booking[] retourne un tableau d'objets Booking
     */
    public function findByStatus(string $status): array
    {
        return $this->createQueryBuilder('b')
            ->where('b.status = :status')
            ->setParameter('status', $status)
            ->orderBy('b.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Recherche une réservation spécifique par ID et utilisateur.
     *
     * @param int   $id   L'identifiant de la réservation
     * @param mixed $user L'utilisateur connecté
     *
     * @return Booking|null retourne une réservation ou null si non trouvée
     */
    public function findOneByIdAndUser(int $id, $user): ?Booking
    {
        return $this->createQueryBuilder('b')
            ->where('b.id = :id')
            ->andWhere('b.Customer = :user')
            ->setParameter('id', $id)
            ->setParameter('user', $user)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findUpcomingMissions(\DateTime $date): array
    {
        return $this->createQueryBuilder('b')
            ->leftJoin('b.Mission', 'm') // ✅ "Mission" avec majuscule car c'est son vrai nom
            ->where('m.date BETWEEN :start AND :end')
            ->setParameter('start', $date->format('Y-m-d 00:00:00'))
            ->setParameter('end', $date->format('Y-m-d 23:59:59'))
            ->getQuery()
            ->getResult();

    }

}
