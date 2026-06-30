<?php

namespace App\Repository;

use App\Entity\Indiceefa;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Indiceefa>
 */
class IndiceefaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Indiceefa::class);
    }

    //    /**
    //     * @return Indiceefa[] Returns an array of Indiceefa objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('i.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Indiceefa
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function findByCategorieAndStatus(int $categorie, bool $isHighCategory = false)
    {
        $table = $isHighCategory ? 'corpsefa4' : 'corpsefa'; // selon ta logique

        // Ou mieux : avoir deux entités séparées
        return $this->createQueryBuilder('c')
            ->where('c.categorie = :cat')
            ->setParameter('cat', $categorie)
            ->getQuery()
            ->getResult();
    }
}
