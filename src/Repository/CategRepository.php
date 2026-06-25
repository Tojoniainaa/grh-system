<?php

namespace App\Repository;

use App\Entity\Categ;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Categ>
 */
class CategRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Categ::class);
    }

    /**
     * Retourne toutes les catégories triées par code
     */
    public function findAllOrdered(): array
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.codeCategorie', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
