<?php

namespace App\Repository;

use App\Entity\SituationAdm;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SituationAdm>
 */
class SituationAdmRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SituationAdm::class);
    }

    //    /**
    //     * @return SituationAdm[] Returns an array of SituationAdm objects
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

    //    public function findOneBySomeField($value): ?SituationAdm
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    // Si tu veux réutiliser l'ancienne fonction liste_tout_SituationAdm

    public function findByStatutWithAgent(string $statut): array
    {
        // On garde uniquement la situation la plus récente de chaque matricule
        $subQuery = $this->createQueryBuilder('s2')
            ->select('MAX(s2.id)')
            ->where('s2.statu = :statut')
            ->groupBy('s2.matricule')
            ->getDQL();

        return $this->createQueryBuilder('s')
            ->andWhere('s.statu = :statut')
            ->andWhere('s.id IN ('.$subQuery.')')
            ->setParameter('statut', $statut)
            ->orderBy('s.matricule', 'ASC')
            ->getQuery()
            ->getResult();
    }
    public function findByStatutAndMatricule(string $statut, string $matricule): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.statu = :statut')
            ->andWhere('s.matricule = :matricule')
            ->setParameter('statut', $statut)
            ->setParameter('matricule', $matricule)
            ->getQuery()
            ->getResult();
    }

    public function countDistinctMatricules(): int
    {
        return (int) $this->createQueryBuilder('s')
            ->select('COUNT(DISTINCT s.matricule)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countByStatus(): array
    {
        return $this->createQueryBuilder('s')
            ->select('s.statu AS status, COUNT(DISTINCT s.matricule) AS total')
            ->where('s.statu IS NOT NULL')
            ->andWhere('s.statu != :empty')
            ->setParameter('empty', '')
            ->groupBy('s.statu')
            ->orderBy('total', 'DESC')
            ->getQuery()
            ->getArrayResult();
    }

    public function countByCategorie(): array
    {
        return $this->createQueryBuilder('s')
            ->select('s.categorie AS categorie, COUNT(DISTINCT s.matricule) AS total')
            ->where('s.categorie IS NOT NULL')
            ->groupBy('s.categorie')
            ->orderBy('s.categorie', 'ASC')
            ->getQuery()
            ->getArrayResult();
    }
}
