<?php

namespace App\Controller;

use App\Entity\SituationAdm;
use App\Repository\AgentsRepository;
use App\Repository\CategRepository;
use App\Repository\CorpsefaRepository;
use App\Repository\CorpseldRepository;
use App\Repository\CorpsfonRepository;
use App\Repository\CorpsheeRepository;
use App\Repository\EchelleRepository;
use App\Repository\GradefonRepository;
use App\Repository\IndiceefaRepository;
use App\Repository\IndiceeldRepository;
use App\Repository\IndiceRepository;
use App\Repository\SituationAdmRepository;
use App\Repository\StatusfonRepository;
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
        CorpsfonRepository $corpsfonRepository,
        CorpsEfaRepository $corpsEfaRepository,
        CorpsEldRepository $corpsEldRepository,
        CorpsHeeRepository $corpsHeeRepository,
        GradeFonRepository $gradeFonRepository,
        IndiceRepository $indiceRepository,
        EchelleRepository $echelleRepository
    ): Response {

        $status    = $request->get('Status1erForm');
        $categorie = (int) $request->get('categorie', 0);

        // Variables pour Twig
        $corpsFonc = [];
        $corpsEfa  = [];
        $corpsEld  = [];
        $corpsHee  = [];
        $grades    = $gradeFonRepository->findBy([], ['libelleGrade' => 'ASC']); // solution temporaire
        $echelles  = [];
        $indices   = [];

        // Chargement conditionnel
        if ($status === 'FONC') {
            $corpsFonc = $corpsfonRepository->findByCategorie($categorie);
        } elseif ($status === 'EFA') {
            $corpsEfa = $corpsEfaRepository->findByCategorie($categorie, $categorie >= 4);
            $echelles = $echelleRepository->findByCategorie($categorie);
        } elseif ($status === 'ELD') {
            $corpsEld = $corpsEldRepository->findByCategorie($categorie);
        } elseif ($status === 'HEE') {
            $corpsHee = $corpsHeeRepository->findBy([], ['libelleCorps' => 'ASC']);
        }

        // Récupération des indices (logique ancienne)
        $indices = $indiceRepository->listeIndice($request->request->all());

        $situation = new SituationAdm();

        // ==================== Formulaire Symfony ====================
        $form = $this->createFormBuilder($situation)
            ->add('matricule', TextType::class, ['label' => 'Matricule'])
            ->add('statu', HiddenType::class)
            ->add('codeCorps', TextType::class, ['required' => false])
            ->add('codeGrade', TextType::class, ['required' => false])
            ->add('categorie', TextType::class, ['required' => false])
            ->add('indice', TextType::class, ['required' => false])
            ->add('dateEntre', DateType::class, ['widget' => 'single_text'])
            ->add('dateEffet', DateType::class, ['widget' => 'single_text'])
            ->add('dateDebContrat', DateType::class, ['widget' => 'single_text', 'required' => false])
            ->add('dateFinContrat', DateType::class, ['widget' => 'single_text', 'required' => false])
            ->add('dateNotif', DateType::class, ['widget' => 'single_text', 'required' => false])
            ->add('dateTexte', DateType::class, ['widget' => 'single_text', 'required' => false])
            ->add('refNotification', TextType::class, ['required' => false])
            ->add('imputationBudgetaire', TextType::class, ['required' => false])
            ->add('typeContrat', TextType::class, ['required' => false])
            ->add('modePaie', ChoiceType::class, [ /* tes choix */ ])
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
            // Ta logique d'insertion ici (reste identique)
        }

        return $this->render('situation_administrative/index.html.twig', [
            'form'       => $form->createView(),
            'status'     => $status,
            'categorie'  => $categorie,
            'corpsFonc'  => $corpsFonc,
            'corpsEfa'   => $corpsEfa,
            'corpsEld'   => $corpsEld,
            'corpsHee'   => $corpsHee,
            'gradesFonc' => $grades,
            'echelles'   => $echelles,
            'indices'    => $indices,
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

    #[Route('/situation-administrative/ajout', name: 'situation_adm_ajout', methods: ['GET', 'POST'])]
    public function ajout(
        Request $request,
        EntityManagerInterface $em,
        SituationAdmRepository $situationRepo
    ): Response {
        if ($request->isMethod('POST')) {
            $data = $request->request->all();

            // === Vérifications identiques à l'ancien code ===
            if (empty($data['Matricule'])) {
                $this->addFlash('error', 'Veuillez vérifier le Matricule');
                return $this->redirectToRoute('situation_adm_ajout');
            }

            // Vérification doublon
            if ($situationRepo->findOneBy(['matricule' => $data['Matricule']])) {
                $this->addFlash('error', 'Cette IM est déjà intégrée dans la base');
                return $this->redirectToRoute('situation_adm_ajout');
            }

            $status = $data['StatusAgent'] ?? '';

            $situation = new SituationAdm();
            $situation->setMatricule($data['Matricule']);
            $situation->setStatu($status);
            $situation->setCodeCorps($data['Code_Corps'] ?? null);
            $situation->setCategorie((int)($data['categorie'] ?? 0));
            $situation->setIndice((int)($data['Indice'] ?? 0));
            $situation->setRefNotification($data['Ref_Notification'] ?? null);
            $situation->setImputationBudgetaire($data['Imputation_Budgetaire'] ?? null);
            $situation->setTypeContrat($data['Type_Contrat'] ?? null);
            $situation->setModePaie($data['Mode_Paie'] ?? 'NON RENSEIGNE');
            $situation->setCodePaie($data['Code_Paie'] ?? null);
            $situation->setCodeBanque($data['Code_Banque'] ?? null);
            $situation->setNumeroCompte($data['Numero_Compte'] ?? null);

            // Conversion des dates (comme dans ton ancien code)
            $situation->setDateEffet($this->convertDate($data['Date_Effet'] ?? null));
            $situation->setDateEntre($this->convertDate($data['Date_Entre'] ?? null));
            $situation->setDateNotif($this->convertDate($data['Date_Notif'] ?? null));
            $situation->setDateTexte($this->convertDate($data['Date_Texte'] ?? null));

            // ====================== LOGIQUE PAR STATUT (100% fidèle) ======================
            if ($status === 'EFA') {
                $cat = (int)($data['categorie'] ?? 0);
                if ($cat <= 3) {
                    $situation->setEchelle($data['Echelle'] ?? null);
                    $situation->setEchelon($data['Echelon'] ?? null);
                    $situation->setDateDebContrat($this->convertDate($data['Date_DebContrat'] ?? null));
                    $situation->setDateFinContrat($this->convertDate($data['Date_FinContrat'] ?? null));
                    $situation->setCodeGrade(null);
                } else {
                    $situation->setCodeGrade($data['Code_Gradefonc'] ?? null);
                }
            }
            elseif ($status === 'FONC') {
                $situation->setCodeGrade($data['Code_Gradefonc'] ?? null);
            }
            elseif ($status === 'ELD') {
                $situation->setMajoration($data['Majoration'] ?? null);
                $situation->setIndiceCt((int)($data['Indice_ct'] ?? 0));
                $situation->setDateDebContrat($this->convertDate($data['Date_DebContrat'] ?? null));
                $situation->setDateFinContrat($this->convertDate($data['Date_FinContrat'] ?? null));
            }
            elseif (in_array($status, ['ECD', 'HEE'])) {
                $situation->setCodeGrade(null);
                $situation->setDateDebContrat(null);
                $situation->setDateFinContrat(null);
            }

            $em->persist($situation);
            $em->flush();

            $this->addFlash('success', 'Situation administrative ajoutée avec succès !');
            return $this->redirectToRoute('situation_adm_ajout');
        }

        // Affichage du formulaire (tu peux garder ton ancien HTML ou le migrer en Twig)
        return $this->render('situation_administrative/ajout.html.twig', [
            // Tu peux passer ici les listes de corps, indices, etc.
        ]);
    }

    private function convertDate(?string $date): ?\DateTimeInterface
    {
        if (empty($date)) return null;
        return \DateTime::createFromFormat('d/m/Y', $date) ?: null;
    }
}
