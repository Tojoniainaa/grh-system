<?php

namespace App\Repository;

use App\Entity\Agents;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Agents>
 */
class AgentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Agents::class);
    }

    //    /**
    //     * @return Agents[] Returns an array of Agents objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Agents
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function findAgentsSansSituationAdministrative(): array
    {
        // Version simple avec sous-requête
        $qb = $this->createQueryBuilder('a');

        $qb->where(
            $qb->expr()->notIn(
                'a.matricule',
                $this->getEntityManager()
                    ->createQueryBuilder()
                    ->select('s.matricule')
                    ->from('App\Entity\SituationAdm', 's')
                    ->getDQL()
            )
        )
            ->orderBy('a.nom', 'ASC')
            ->addOrderBy('a.prenom', 'ASC');

        return $qb->getQuery()->getResult();
    }

    public function search(string $q): array
    {
        return $this->createQueryBuilder('a')
            ->where('a.nom LIKE :q OR a.prenom LIKE :q OR a.matricule LIKE :q OR a.contact LIKE :q OR a.cin LIKE :q')
            ->setParameter('q', '%'.$q.'%')
            ->orderBy('a.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findPaginated(int $page = 1, int $limit = 10, ?string $q = null): Paginator
    {
        $qb = $this->createQueryBuilder('a')
            ->orderBy('a.nom', 'ASC')
            ->addOrderBy('a.prenom', 'ASC');

        if ($q) {
            $qb->andWhere('a.nom LIKE :q OR a.prenom LIKE :q OR a.matricule LIKE :q OR a.cin LIKE :q')
                ->setParameter('q', '%' . $q . '%');
        }

        $qb->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        return new Paginator($qb);
    }
}
