<?php

namespace App\Repository;

use App\Entity\Mission;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Mission>
 */
class MissionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mission::class);
    }

    public function baseQuery(): QueryBuilder
    {
        return $this->createQueryBuilder('m')
            ->select('m')
            ->orderBy('m.date', 'ASC');
    }

    /**
     * @return Mission[]
     */
    public function findAllMissions(?int $page = null): array
    {
        return $this->baseQuery()
            ->setMaxResults($page)
            ->getQuery()
            ->getResult();
    }

    /**
     * Récupère les missions filtrées par destination, date et prix
     */
    public function findFilteredMissions(?string $destination, ?string $date, ?string $price): array
    {
        $qb = $this->createQueryBuilder('m');

        if ($destination) {
            $qb->andWhere('m.destination = :destination')
                ->setParameter('destination', $destination);
        }

        if ($date) {
            $qb->andWhere('m.date = :date')
                ->setParameter('date', new \DateTime($date));
        }

        if ($price) {
            if ($price === 'cheap') {
                $qb->andWhere('m.seatPrice < 1000');
            } elseif ($price === 'medium') {
                $qb->andWhere('m.seatPrice BETWEEN 1000 AND 5000');
            } elseif ($price === 'expensive') {
                $qb->andWhere('m.seatPrice > 5000');
            }
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * Récupère toutes les destinations distinctes
     */
    public function findDistinctDestinations(): array
    {
        return $this->createQueryBuilder('m')
            ->select('DISTINCT m.destination')
            ->getQuery()
            ->getResult();
    }

    /**
     * Récupère les missions d'un opérateur spécifique
     */
    public function findByOperator(int $operatorId): array
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.operator = :operator')
            ->setParameter('operator', $operatorId)
            ->getQuery()
            ->getResult();
    }

    public function findUpcomingMissions(\DateTime $date): array
    {
        return $this->createQueryBuilder('m')
            ->where('m.date > :date')
            ->setParameter('date', $date->format('Y-m-d H:i:s'))
            ->orderBy('m.date', 'ASC')
            ->getQuery()
            ->getResult();
    }

}
