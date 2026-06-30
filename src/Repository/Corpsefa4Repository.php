<?php

namespace App\Repository;

use App\Entity\Corpsefa4;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Corpsefa4>
 */
class Corpsefa4Repository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Corpsefa4::class);
    }

    //    /**
    //     * @return Corpsefa4[] Returns an array of Corpsefa4 objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Corpsefa4
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    // Exemple : trouver par code
    public function findByCode(string $code)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.codeCorps = :code')
            ->setParameter('code', $code)
            ->getQuery()
            ->getResult();
    }

    // Exemple : par catégorie
    public function findByCategorie(int $categorie)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.categorie = :cat')
            ->setParameter('cat', $categorie)
            ->getQuery()
            ->getResult();
    }
}
