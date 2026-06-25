<?php

namespace App\Repository;

use App\Entity\Corpsfon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Corpsfon>
 */
class CorpsfonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Corpsfon::class);
    }

    /**
     * Retourne les corps par catégorie (logique importante pour votre ancien code)
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

    /**
     * Tous les corps (fallback)
     */
    public function findAllOrdered(): array
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.libelleCorps', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
