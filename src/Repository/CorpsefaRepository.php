<?php

namespace App\Repository;

use App\Entity\Corpsefa;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Corpsefa>
 */
class CorpsefaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Corpsefa::class);
    }

    //    /**
    //     * @return Corpsefa[] Returns an array of Corpsefa objects
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

    //    public function findOneBySomeField($value): ?Corpsefa
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    /**
     * Ancien liste_corpsEFA() pour catégories <= 3
     */
    public function findByCategorie(int $categorie): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.categorie = :categorie')
            ->setParameter('categorie', $categorie)
            ->orderBy('c.libelleCorps', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findAllOrdered(): array
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.libelleCorps', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
