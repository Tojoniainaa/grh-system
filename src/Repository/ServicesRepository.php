<?php

namespace App\Repository;

use App\Entity\Services;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Services>
 */
class ServicesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Services::class);
    }

    //    /**
    //     * @return Services[] Returns an array of Services objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Services
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function findDirectionsGenerales(): array
    {
        return $this->createQueryBuilder('s')
            ->select('DISTINCT s.directionGenerale')
            ->where('s.directionGenerale IS NOT NULL')
            ->andWhere('s.directionGenerale != :empty')
            ->setParameter('empty', '')
            ->orderBy('s.directionGenerale', 'ASC')
            ->getQuery()
            ->getSingleColumnResult();
    }


    public function countDirections(): int
    {
        return (int) $this->createQueryBuilder('s')
            ->select('COUNT(DISTINCT s.directionGenerale)')
            ->andWhere('s.directionGenerale IS NOT NULL')
            ->andWhere('s.directionGenerale != :empty')
            ->setParameter('empty', '')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countServices(): int
    {
        return (int) $this->createQueryBuilder('s')
            ->select('COUNT(s.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

}
