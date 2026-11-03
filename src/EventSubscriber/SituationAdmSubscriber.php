<?php

namespace App\EventSubscriber;

use App\Entity\SituationAdm;
use App\Entity\AuditSituationAdm;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Psr\Log\LoggerInterface;

#[AsDoctrineListener(event: Events::onFlush)]
class SituationAdmSubscriber
{
    public function __construct(
        private LoggerInterface $logger
    ) {
    }

    public function onFlush(OnFlushEventArgs $args): void
    {
        $em = $args->getObjectManager();
        $uow = $em->getUnitOfWork();

        $metadata = $em->getClassMetadata(AuditSituationAdm::class);

        // =====================================
        // 1. INSERT : nouvelle SituationAdm
        // =====================================
        foreach ($uow->getScheduledEntityInsertions() as $entity) {

            if (!$entity instanceof SituationAdm) {
                continue;
            }

            $this->logger->info('INSERT SituationAdm détecté', [
                'matricule' => $entity->getMatricule(),
            ]);

            $this->creerAudit($entity, 'INSERT', $em, $metadata);
        }

        // =====================================
        // 2. UPDATE : modification SituationAdm
        // =====================================
        foreach ($uow->getScheduledEntityUpdates() as $entity) {

            if (!$entity instanceof SituationAdm) {
                continue;
            }

            $this->logger->info('UPDATE SituationAdm détecté', [
                'id' => $entity->getId(),
                'matricule' => $entity->getMatricule(),
            ]);

            $this->creerAudit($entity, 'UPDATE', $em, $metadata);
        }
    }

    // =====================================
    // 3. Création d'une ligne d'audit
    // =====================================
    private function creerAudit(
        SituationAdm $entity,
        string $type,
                     $em,
                     $metadata
    ): void {

        $audit = new AuditSituationAdm();

        // Informations de l'agent
        $audit->setMatricule($entity->getMatricule() ?? 'INCONNU');

        // Nouvelles valeurs
        $audit->setNouvelleCodeGrade($entity->getCodeGrade());
        $audit->setNouvelleIndice($entity->getIndice());
        $audit->setNouvelleDateEffet($entity->getDateEffet());
        $audit->setNouvelleDateNotif($entity->getDateNotif());
        $audit->setNouvelleRefNotification($entity->getRefNotification());
        $audit->setNouvelleStatu($entity->getStatu());
        $audit->setNouvelleCodeCorps($entity->getCodeCorps());
        $audit->setNouvelleCategorie($entity->getCategorie());
        $audit->setNouvelleDateTexte($entity->getDateTexte());
        $audit->setNouvelleEchelle($entity->getEchelle());
        $audit->setNouvelleEchelon($entity->getEchelon());
        $audit->setNouvelleIndiceCt($entity->getIndiceCt());
        $audit->setNouvelleMajoration($entity->getMajoration());

        // Informations sur l'opération
        $audit->setDateHistorisation(new \DateTimeImmutable());
        $audit->setTypeOperation($type);

        // Enregistrement de l'audit
        $em->persist($audit);

        // Ajouter l'audit au flush actuel
        $em->getUnitOfWork()->computeChangeSet($metadata, $audit);

        $this->logger->info('Nouvelle ligne AuditSituationAdm préparée', [
            'matricule' => $entity->getMatricule(),
            'operation' => $type,
        ]);
    }
}
