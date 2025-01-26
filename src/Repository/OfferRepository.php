<?php

namespace App\Repository;

use App\Entity\Offer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Offer>
 */
class OfferRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Offer::class);
    }

    public function countByType(string $type): int
    {
        return $this->createQueryBuilder('o')
            ->select('COUNT(o.id)')
            ->where('o.type = :type')
            ->setParameter('type', $type)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findLatestOffers(int $limit = 8): array
    {
        return $this->createQueryBuilder('o')
            ->orderBy('o.startDate', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findByType($type)
    {
        return $this->createQueryBuilder('o')
            ->where('o.type = :type')
            ->setParameter('type', $type)
            ->getQuery()
            ->setCacheable(false);
    }

    public function findSimilarOffers(string $type, int $excludeId, int $limit)
    {
        return $this->createQueryBuilder('o')
            ->where('o.type = :type')
            ->andWhere('o.id != :excludeId')
            ->setParameter('type', $type)
            ->setParameter('excludeId', $excludeId)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
