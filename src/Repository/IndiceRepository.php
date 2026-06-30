<?php
// src/Repository/IndiceRepository.php
namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class IndiceRepository extends ServiceEntityRepository
{
public function __construct(ManagerRegistry $registry)
{
parent::__construct($registry, \App\Entity\Statusfon::class);
}

// ==================== liste_indice() - LOGIQUE ORIGINALE ====================
public function listeIndice(array $postData)
{
    $status = $postData['Status'] ?? $postData['StatusFonc'] ?? '';

    if ($status === 'FONC' && !empty($postData['Code_Corpsfonc']) && !empty($postData['Code_Gradefonc']) && !empty($postData['categorie2Form'])) {
        return $this->getEntityManager()->createQuery(
        "SELECT DISTINCT i.indice, i.categorie
        FROM App\Entity\Statusfon i
        WHERE i.categorie = :cat
        AND i.codeCorps = :corps
        AND i.grade = :grade"
        )->setParameters([
        'cat'   => $postData['categorie2Form'],
        'corps' => $postData['Code_Corpsfonc'],
        'grade' => $postData['Code_Gradefonc']
        ])->getResult();
    }

    if ($status === 'EFA' && !empty($postData['Corps_EFA']) && !empty($postData['Code_Gradefonc']) && !empty($postData['categorie2Form'])) {
        $entity = $postData['categorie2Form'] >= 4 ? 'App\Entity\Indiceefa4' : 'App\Entity\Indiceefa';
        return $this->getEntityManager()->createQuery(
        "SELECT DISTINCT i.indice
        FROM $entity i
        WHERE i.categorie = :cat
        AND i.codeCorps = :corps
        AND i.codeGrade = :grade"
        )->setParameters([
        'cat'   => $postData['categorie2Form'],
        'corps' => $postData['Corps_EFA'],
        'grade' => $postData['Code_Gradefonc']
        ])->getResult();
    }

    if ($status === 'EFA' && !empty($postData['Echelle']) && !empty($postData['Echelon']) && !empty($postData['categorie2Form'])) {
        return $this->getEntityManager()->createQuery(
        "SELECT DISTINCT i.indice FROM App\Entity\Indiceefa i WHERE i.categorie = :cat AND i.echelle = :echelle AND i.echelon = :echelon")->setParameters([
        'cat'     => $postData['categorie2Form'],
        'echelle' => $postData['Echelle'],
        'echelon' => $postData['Echelon']
        ])->getResult();
    }

    if ($status === 'ELD' && !empty($postData['Indice_ct']) && !empty($postData['Majoration']) && !empty($postData['categorie2Form'])) {
        return $this->getEntityManager()->createQuery(
        "SELECT DISTINCT i.indice, i.indiceCt, i.majoration FROM App\Entity\Indiceeld i
        WHERE i.categorie = :cat AND i.indiceCt = :ct AND i.majoration = :maj"
        )->setParameters([
        'cat' => $postData['categorie2Form'],
        'ct'  => $postData['Indice_ct'],
        'maj' => $postData['Majoration']
        ])->getResult();
    }

    if ($status === 'ECD' && !empty($postData['categorie2Form']) && $postData['categorie2Form'] === 'N') {
        return $this->getEntityManager()->createQuery(
        "SELECT DISTINCT i.indice FROM App\Entity\Indiceecd i"
        )->getResult();
    }

    if ($status === 'HEE') {
        return $this->getEntityManager()->createQuery(
        "SELECT DISTINCT i.indice FROM App\Entity\Statusfon i ORDER BY i.indice"
        )->getResult();
    }

    // Par défaut
    return $this->getEntityManager()->createQuery(
    "SELECT DISTINCT i.indice FROM App\Entity\Statusfon i ORDER BY i.indice"
    )->getResult();
    }

// Autres fonctions utiles
public function listeIndiceCt(int $categorie = null)
{
    $qb = $this->getEntityManager()->createQueryBuilder()
    ->select('DISTINCT i.indiceCt')
    ->from('App\Entity\Indiceeld', 'i');

    if ($categorie) {
    $qb->where('i.categorie = :cat')->setParameter('cat', $categorie);
    }

    return $qb->orderBy('i.indiceCt', 'DESC')->getQuery()->getResult();
}

public function listeEchelle(int $categorie = null)
{
    $qb = $this->getEntityManager()->createQueryBuilder()
    ->select('e.echelle')
    ->from('App\Entity\Echelle', 'e');

    if ($categorie) {
    $qb->where('e.categorie = :cat')->setParameter('cat', $categorie);
    }

    return $qb->getQuery()->getResult();
    }
}
