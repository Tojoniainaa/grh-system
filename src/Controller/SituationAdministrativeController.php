<?php

namespace App\Controller;

use App\Entity\SituationAdm;
use App\Repository\AgentsRepository;
use App\Repository\CategRepository;
use App\Repository\CorpsfonRepository;
use App\Repository\SituationAdmRepository;
use App\Repository\AgentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class SituationAdministrativeController extends AbstractController
{
    #[Route('/situation-administrative', name: 'situation_administrative_index', methods: ['GET', 'POST'])]
    public function index(
        Request $request,
        EntityManagerInterface $entityManager,
        SituationAdmRepository $situationAdmRepository,
        AgentsRepository $agentRepository,
        CategRepository $categRepository,
        CorpsfonRepository $corpsfonRepository
    ): Response {
        $status = $request->get('Status1erForm');           // Récupère le statut sélectionné
        $categorie = $request->get('categorie');
        // Chargement dynamique des données
        $categories = $categRepository->findAllOrdered();

        $corps = [];
        if ($categorie) {
            $corps = $corpsfonRepository->findByCategorie((int)$categorie);
        } else {
            $corps = $corpsfonRepository->findAllOrdered();
        }
        $situation = new SituationAdm();

        $form = $this->createFormBuilder($situation)
            ->add('matricule', TextType::class, ['label' => 'Matricule'])
            ->add('statu', HiddenType::class)           // Rempli dynamiquement
            ->add('codeCorps', TextType::class, ['label' => 'Code Corps', 'required' => false])
            ->add('codeGrade', TextType::class, ['label' => 'Code Grade', 'required' => false])
            ->add('categorie', TextType::class, ['label' => 'Catégorie', 'required' => false])
            ->add('indice', TextType::class, ['label' => 'Indice', 'required' => false])
            ->add('dateEntre', DateType::class, ['widget' => 'single_text', 'label' => 'Date Entrée'])
            ->add('dateEffet', DateType::class, ['widget' => 'single_text', 'label' => 'Date Effet'])
            ->add('dateDebContrat', DateType::class, ['widget' => 'single_text', 'required' => false])
            ->add('dateFinContrat', DateType::class, ['widget' => 'single_text', 'required' => false])
            ->add('dateNotif', DateType::class, ['widget' => 'single_text', 'required' => false])
            ->add('dateTexte', DateType::class, ['widget' => 'single_text', 'required' => false])
            ->add('refNotification', TextType::class, ['label' => 'Réf. Notification', 'required' => false])
            ->add('imputationBudgetaire', TextType::class, ['required' => false])
            ->add('typeContrat', TextType::class, ['required' => false])
            ->add('modePaie', ChoiceType::class, [
                'choices' => [
                    'VIREMENT BANCAIRE' => 'VIREMENT BANCAIRE',
                    'VIREMENT POSTAL' => 'VIREMENT POSTAL',
                    'BILLETAGE' => 'BILLETAGE',
                    'BON DE CAISSE' => 'BON DE CAISSE',
                    'NON RENSEIGNE' => 'NON RENSEIGNE',
                ]
            ])
            ->add('codePaie', TextType::class, ['required' => false])
            ->add('codeBanque', TextType::class, ['required' => false])
            ->add('numeroCompte', TextType::class, ['required' => false])
            ->add('echelle', TextType::class, ['required' => false])
            ->add('echelon', TextType::class, ['required' => false])
            ->add('indiceCt', TextType::class, ['required' => false])
            ->add('majoration', TextType::class, ['required' => false])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $request->request->all(); // Pour garder la logique POST brute comme avant

            // ====================== VÉRIFICATIONS IDENTIQUES À L'ANCIEN CODE ======================
            if (empty($data['matricule'])) {
                $this->addFlash('error', 'Veuillez vérifier le Matricule');
                return $this->redirectToRoute('situation_administrative_index');
            }

            // Vérification doublon
            $existing = $situationAdmRepository->findOneBy(['matricule' => $data['matricule']]);
            if ($existing) {
                $this->addFlash('error', 'Cette IM est déjà intégrée dans la base');
                return $this->redirectToRoute('situation_administrative_index');
            }

            $status = $data['statu'] ?? $data['StatusAgent'] ?? '';

            // ====================== LOGIQUE D'INSERTION PAR STATUT (conservée) ======================
            try {
                $situation->setMatricule($data['matricule']);
                $situation->setStatu($status);
                $situation->setCodeCorps($data['codeCorps'] ?? '');
                $situation->setCodeGrade($data['codeGrade'] ?? null);
                $situation->setCategorie((int)($data['categorie'] ?? 0));
                $situation->setIndice((int)($data['indice'] ?? 0));
                $situation->setDateEntre($this->formatDate($data['dateEntre'] ?? ''));
                $situation->setDateEffet($this->formatDate($data['dateEffet'] ?? ''));
                $situation->setDateNotif($this->formatDate($data['dateNotif'] ?? ''));
                $situation->setDateTexte($this->formatDate($data['dateTexte'] ?? ''));
                $situation->setRefNotification($data['refNotification'] ?? '');
                $situation->setImputationBudgetaire($data['imputationBudgetaire'] ?? '');
                $situation->setTypeContrat($data['typeContrat'] ?? '');
                $situation->setModePaie($data['modePaie'] ?? '');
                $situation->setCodePaie($data['codePaie'] ?? '');
                $situation->setCodeBanque($data['codeBanque'] ?? '');
                $situation->setNumeroCompte($data['numeroCompte'] ?? '');

                // Logique conditionnelle par statut
                if ($status === 'EFA') {
                    $categorie = (int)($data['categorie'] ?? 0);
                    if ($categorie <= 3) {
                        $situation->setEchelle($data['echelle'] ?? '');
                        $situation->setEchelon($data['echelon'] ?? '');
                        $situation->setDateDebContrat($this->formatDate($data['dateDebContrat'] ?? ''));
                        $situation->setDateFinContrat($this->formatDate($data['dateFinContrat'] ?? ''));
                    } else {
                        $situation->setCodeGrade($data['codeGrade'] ?? '');
                    }
                }
                elseif ($status === 'FONC') {
                    $situation->setCodeGrade($data['codeGrade'] ?? '');
                }
                elseif ($status === 'ELD') {
                    $situation->setMajoration((int)($data['majoration'] ?? 0));
                    $situation->setIndiceCt((int)($data['indiceCt'] ?? 0));
                    $situation->setDateDebContrat($this->formatDate($data['dateDebContrat'] ?? ''));
                    $situation->setDateFinContrat($this->formatDate($data['dateFinContrat'] ?? ''));
                }
                elseif (in_array($status, ['ECD', 'HEE'])) {
                    $situation->setDateDebContrat(null);
                    $situation->setDateFinContrat(null);
                }

                $entityManager->persist($situation);
                $entityManager->flush();

                $this->addFlash('success', 'Situation administrative enregistrée avec succès !');
                return $this->redirectToRoute('situation_administrative_index');

            } catch (\Exception $e) {
                $this->addFlash('error', 'Erreur lors de l\'enregistrement : ' . $e->getMessage());
            }
        }

        return $this->render('situation_administrative/index.html.twig', [
            'form' => $form->createView(),
            'status'   => $status,           // ← Correction ici
            'categories'=> $categories,
            'corpsList'  => $corps,
        ]);
    }

    /**
     * Fonction helper pour convertir dd/mm/yyyy → DateTime (comme dans l'ancien code)
     */
    private function formatDate(?string $dateStr): ?\DateTimeInterface
    {
        if (empty($dateStr)) {
            return null;
        }

        if (strpos($dateStr, '/') !== false) {
            return \DateTime::createFromFormat('d/m/Y', $dateStr) ?: null;
        }

        return new \DateTime($dateStr);
    }
}
