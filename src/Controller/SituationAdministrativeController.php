<?php

namespace App\Controller;

use App\Entity\Corpsefa;
use App\Entity\Corpsfon;
use App\Entity\Gradefon;
use App\Entity\SituationAdm;
use App\Entity\Statusfon;
use App\Repository\AgentsRepository;
use App\Repository\BudgetRepository;
use App\Repository\Corpsefa4Repository;
use App\Repository\CorpsefaRepository;
use App\Repository\CorpseldRepository;
use App\Repository\CorpsfonRepository;
use App\Repository\CorpsheeRepository;
use App\Repository\EchelleRepository;
use App\Repository\GradefonRepository;
use App\Repository\IndiceRepository;
use App\Repository\ServicesRepository;
use App\Repository\SituationAdmRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class SituationAdministrativeController extends AbstractController
{
    #[Route('/situation-administrative', name: 'situation_administrative_index', methods: ['GET', 'POST'])]
    public function index(
        Request $request,
        EntityManagerInterface $entityManager,
        SituationAdmRepository $situationAdmRepository,
        CorpsfonRepository $corpsfonRepository,
        CorpsEfaRepository $corpsEfaRepository,
        Corpsefa4Repository $corpsEfa4Repository,
        CorpsEldRepository $corpsEldRepository,
        CorpsHeeRepository $corpsHeeRepository,
        GradeFonRepository $gradeFonRepository,
        IndiceRepository $indiceRepository,
        EchelleRepository $echelleRepository,
        BudgetRepository $budgetRepository,
        ServicesRepository $servicesRepository,
        SituationAdmRepository $situationRepo,
        EntityManagerInterface $em,
    ): Response {

        $status    = $request->get('Status1erForm');
        $categorie = (int) $request->get('categorie', 0);

        // Variables pour Twig
        $corpsFonc = [];
        $corpsEfa  = [];
        $corpsEfa4  = [];
        $corpsEld  = [];
        $corpsHee  = [];
        $grades    = $gradeFonRepository->findBy([], ['libelleGrade' => 'ASC']); // solution temporaire
        $echelles  = [];
        $service = null;

        // Chargement conditionnel
        if ($status === 'FONC') {
            $corpsFonc = $corpsfonRepository->findByCategorie($categorie);
        } elseif ($status==='EFA' && $categorie >= 4){
            $corpsEfa4 = $corpsEfa4Repository->findByCategorie($categorie );
        }elseif ($status === 'EFA') {
            $corpsEfa = $corpsEfaRepository->findByCategorie($categorie );
            $echelles = $echelleRepository->findByCategorie($categorie);
        } elseif ($status === 'ELD') {
            $corpsEld = $corpsEldRepository->findByCategorie($categorie);
        } elseif ($status === 'HEE') {
            $corpsHee = $corpsHeeRepository->findBy([], ['libelleCorps' => 'ASC']);
        }
        $directionsGenerales = $servicesRepository->findDirectionsGenerales();

        $services = $servicesRepository->findAll();
        // Récupération des indices (logique ancienne)
        $indices = $indiceRepository->listeIndice($request->request->all());
        $budgets = $budgetRepository->listeBudget($request->request->all());

        if ($request->isMethod('POST')) {
            $data = $request->request->all();

            // ===== Validations =====
            if (empty($data['Matricule'])) {
                $this->addFlash('error', 'Veuillez vérifier le Matricule');
                return $this->redirectToRoute('situation_administrative_index');
            }

            if ($situationRepo->findOneBy(['matricule' => $data['Matricule']])) {
                $this->addFlash('error', 'Cette IM est déjà intégrée dans la base');
                return $this->redirectToRoute('situation_administrative_index');
            }

            $status = $data['StatusAgent'] ?? '';
            $situation = new SituationAdm();

            if (!empty($data['service_id'])) {
                $service = $servicesRepository->find((int) $data['service_id']);
            }

            if (!$service) {
                $this->addFlash('error', 'Veuillez sélectionner un service.');
                return $this->redirectToRoute('situation_administrative_index');
            }

            $situation->setService($service);
            // ===== Champs communs =====
            $situation->setMatricule($data['Matricule']);
            $situation->setStatu($status);
            $situation->setCategorie((int)($data['categorie'] ?? 0));
            $situation->setIndice((int)($data['Indice'] ?? 0));

            $situation->setRefNotification($data['Ref_Notification'] ?? null);
            $situation->setImputationBudgetaire($data['Imputation_Budgetaire'] ?? null);
            $situation->setTypeContrat($data['Type_Contrat'] ?? null);
            $situation->setModePaie($data['Mode_Paie'] ?? 'NON RENSEIGNE');
            $situation->setCodePaie($data['Code_Paie'] ?? null);
            $situation->setCodeBanque($data['Code_Banque'] ?? null);
            $situation->setNumeroCompte($data['Numero_Compte'] ?? null);

            // Dates communes
            $situation->setDateEffet($this->convertDate($data['Date_Effet'] ?? null));
            $situation->setDateEntre($this->convertDate($data['Date_Entre'] ?? null));
            $situation->setDateNotif($this->convertDate($data['Date_Notif'] ?? null));
            $situation->setDateTexte($this->convertDate($data['Date_Texte'] ?? null));

            // ====================== LOGIQUE PAR STATUT ======================
            if ($status === 'FONC') {
                // Code Corps (champ caché name="Code_Corps")
                if (!empty($data['Code_Corps'])) {
                        $situation->setCodeCorps($data['Code_Corps']);
                }
                // Code Grade (champ caché name="Code_Gradefonc")
                if (!empty($data['Code_Gradefonc'])) {
                    $situation->setCodeGrade($data['Code_Gradefonc']);
                }

            } elseif ($status === 'EFA') {
                $cat = (int)($data['categorie'] ?? 0);

                if ($cat <= 3) {
                    $situation->setEchelle($data['Echelle'] ?? null);
                    $situation->setEchelon($data['Echelon'] ?? null);
                    $situation->setDateDebContrat($this->convertDate($data['Date_DebContrat'] ?? null));
                    $situation->setDateFinContrat($this->convertDate($data['Date_FinContrat'] ?? null));
                    $situation->setCodeGrade(null);

                    // Corps éventuel pour ≤ 3
                    if (!empty($data['Corps_EFA3'])) {
                        $situation->setCodeCorps($data['Corps_EFA3']);
                    }
                } else {
                    // EFA ≥ 4
                    if (!empty($data['Corps_EFA'])) {
                        $situation->setCodeCorps($data['Corps_EFA']);
                    }
                    if (!empty($data['Code_Gradefonc'])) {
                        $situation->setCodeGrade($data['Code_Gradefonc']);
                    }
                    $situation->setDateDebContrat($this->convertDate($data['Date_DebContrat'] ?? null));
                    $situation->setDateFinContrat($this->convertDate($data['Date_FinContrat'] ?? null));
                }

            } elseif ($status === 'ELD') {
                $situation->setMajoration($data['Majoration'] ?? null);
                $situation->setIndiceCt((int)($data['Indice_ct'] ?? 0));
                $situation->setDateDebContrat($this->convertDate($data['Date_DebContrat'] ?? null));
                $situation->setDateFinContrat($this->convertDate($data['Date_FinContrat'] ?? null));

            } elseif (in_array($status, ['ECD', 'HEE'])) {
                $situation->setCodeGrade(null);
                $situation->setDateDebContrat(null);
                $situation->setDateFinContrat(null);
            }

            $em->persist($situation);
            $em->flush();

            $this->addFlash('success', 'Situation administrative ajoutée avec succès !');
            return $this->redirectToRoute('situation_administrative_index');
        }


        return $this->render('situation_administrative/index.html.twig', [
            'status'     => $status,
            'categorie'  => $categorie,
            'corpsFonc'  => $corpsFonc,
            'corpsEfa'   => $corpsEfa,
            'corpsEfa4'   => $corpsEfa4,
            'corpsEld'   => $corpsEld,
            'corpsHee'   => $corpsHee,
            'gradesFonc' => $grades,
            'echelles'   => $echelles,
            'indices'    => $indices,
            'budgets' => $budgets,
            'directionsGenerales' => $directionsGenerales,
            'services' => $services,
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

    private function convertDate(?string $date): ?\DateTimeInterface
    {
        if (empty($date)) {
            return null;
        }
        $formats = ['Y-m-d', 'd/m/Y'];
        foreach ($formats as $format) {
            $dateObj = \DateTime::createFromFormat($format, $date);
            if ($dateObj !== false) {
                return $dateObj;
            }
        }
        return null;
    }

    #[Route('/agent/search/matricule', name: 'agent_search_by_matricule', methods: ['POST'])]
    public function searchByMatricule(
        Request $request,
        AgentsRepository $agentRepo,
    ): JsonResponse {
        $data = json_decode($request->getContent(), true) ?? [];

        $matricule = trim($data['matricule'] ?? '');

        if (empty($matricule)) {
            return $this->json(['success' => false, 'message' => 'Matricule requis'], 400);
        }

        $agent = $agentRepo->findOneBy(['matricule' => $matricule]);

        if (!$agent) {
            return $this->json([
                'success' => false,
                'message' => 'Matricule non trouvé'
            ], 404);
        }

        // Version simplifiée : seulement le matricule + infos de base
        return $this->json([
            'success' => true,
            'agent' => [
                'matricule' => $agent->getMatricule(),
            ]
        ]);
    }
    #[Route('/api/agents-sans-situation', name: 'api_agents_sans_situation', methods: ['GET'])]
    public function apiAgentsSansSituation(AgentsRepository $agentRepo): JsonResponse
    {
        $agents = $agentRepo->findAgentsSansSituationAdministrative();

        $data = [];
        foreach ($agents as $agent) {
            $data[] = [
                'matricule'     => $agent->getMatricule(),
                'nom'           => $agent->getNom(),
                'prenom'        => $agent->getPrenom(),
                'sexe'          => $agent->getSexe(),
                'dateNaissance' => $agent->getDateNaissance()?->format('d/m/Y'),
            ];
        }

        return $this->json([
            'success' => true,
            'agents'  => $data,
        ]);
    }

    #[Route('/avancement', name: 'avancement_index', methods: ['GET', 'POST'])]
    public function avancement(
        Request $request,
        EntityManagerInterface $em,
        AgentsRepository $agentRepo,
        SituationAdmRepository $situationRepo
    ): Response {
        $triage    = $request->request->get('triage') ?? $request->query->get('triage', '');
        $searchAdm = trim($request->request->get('searchAdm') ?? $request->query->get('searchAdm', ''));

        $agent     = null;
        $situation = null;
        $liste     = [];

        // Carte POSITION (quand on cherche un matricule précis)
        if ($searchAdm !== '') {
            $agent = $agentRepo->findOneBy(['matricule' => $searchAdm]);
            if ($agent) {
                $situation = $situationRepo->findOneBy(['matricule' => $searchAdm]);
            }
        }

        // Liste du tableau
        if ($triage !== '') {
            if ($searchAdm !== '') {
                // → On filtre par statut + matricule exact
                $liste = $situationRepo->findByStatutAndMatricule($triage, $searchAdm);
            } else {
                // → On affiche tout le statut
                $liste = $situationRepo->findByStatutWithAgent($triage);
            }
        }

        return $this->render('agents/liste.html.twig', [
            'triage'    => $triage,
            'searchAdm' => $searchAdm,
            'agent'     => $agent,
            'situation' => $situation,
            'liste'     => $liste,
        ]);
    }

    #[Route('/avancement/fonc-modal/{matricule}', name: 'avancement_fonc_modal', methods: ['GET'])]
    public function avancementFoncModal(
        string $matricule,
        AgentsRepository $agentRepo,
        SituationAdmRepository $situationRepo,
        EntityManagerInterface $em
    ): Response {
        $agent = $agentRepo->findOneBy(['matricule' => $matricule]);
        if (!$agent) {
            return new Response('<div class="alert alert-danger m-4">Agent introuvable</div>', 404);
        }

        $situation = $situationRepo->findOneBy(['matricule' => $matricule]);
        if (!$situation) {
            return new Response('<div class="alert alert-danger m-4">Situation administrative introuvable</div>', 404);
        }

        // Grades
        $grades = $em->getRepository(GradeFon::class)->findAll();

        // Indices → change Statusfon par le vrai nom de ton entité Indice
        // Exemple : Indice::class ou Statusfon si c’est vraiment cette table
        $indices = $em->getRepository(Statusfon::class)->findAll();

        return $this->render('avancement/_fonc_modal_content.html.twig', [
            'agent'     => $agent,
            'situation' => $situation,
            'grades'    => $grades,
            'indices'   => $indices,
        ]);
    }

    #[Route('/avancement/efa4-modal/{matricule}', name: 'avancement_efa4_modal', methods: ['GET'])]
    public function avancementEFA4Modal(
        string $matricule,
        AgentsRepository $agentRepo,
        SituationAdmRepository $situationRepo,
        EntityManagerInterface $em
    ): Response {
        $agent = $agentRepo->findOneBy(['matricule' => $matricule]);
        if (!$agent) {
            return new Response('<div class="alert alert-danger m-4">Agent introuvable</div>', 404);
        }

        $situation = $situationRepo->findOneBy(['matricule' => $matricule]);
        if (!$situation) {
            return new Response('<div class="alert alert-danger m-4">Situation administrative introuvable</div>', 404);
        }

        // Grades
        $grades = $em->getRepository(GradeFon::class)->findAll();

        // Indices → change Statusfon par le vrai nom de ton entité Indice
        // Exemple : Indice::class ou Statusfon si c’est vraiment cette table
        $indices = $em->getRepository(Statusfon::class)->findAll();

        return $this->render('avancement/_efa4_modal_content.html.twig', [
            'agent'     => $agent,
            'situation' => $situation,
            'grades'    => $grades,
            'indices'   => $indices,
        ]);
    }

    #[Route('/avancement/corps-modal/{matricule}', name: 'avancement_corps_modal', methods: ['GET'])]
    public function avancementCorpsModal(
        string $matricule,
        AgentsRepository $agentRepo,
        SituationAdmRepository $situationRepo,
        EntityManagerInterface $em
    ): Response {
        $agent = $agentRepo->findOneBy(['matricule' => $matricule]);
        if (!$agent) {
            return new Response('<div class="alert alert-danger m-4">Agent introuvable</div>', 404);
        }

        $situation = $situationRepo->findOneBy(['matricule' => $matricule]);
        if (!$situation) {
            return new Response('<div class="alert alert-danger m-4">Situation administrative introuvable</div>', 404);
        }

        $corpsList = $em->getRepository(Corpsfon::class)->findAll();
        $indices   = $em->getRepository(Statusfon::class)->findAll(); // garde le même que pour le grade

        return $this->render('avancement/_corps_modal_content.html.twig', [
            'agent'     => $agent,
            'situation' => $situation,
            'corpsList' => $corpsList,
            'indices'   => $indices,
        ]);
    }
    #[Route('/avancement/efa-modal/{matricule}', name: 'avancement_efa_modal', methods: ['GET'])]
    public function avancementEfaModal(
        string $matricule,
        AgentsRepository $agentRepo,
        SituationAdmRepository $situationRepo,
        EntityManagerInterface $em,
        EchelleRepository $echelleRepository,
    ): Response {
        $agent = $agentRepo->findOneBy(['matricule' => $matricule]);
        if (!$agent) {
            return new Response('<div class="alert alert-danger m-4">Agent introuvable</div>', 404);
        }

        $situation = $situationRepo->findOneBy(['matricule' => $matricule]);
        if (!$situation) {
            return new Response('<div class="alert alert-danger m-4">Situation administrative introuvable</div>', 404);
        }

        // Corps EFA + Échelles + Échelons selon la catégorie
        $categorie = $situation->getCategorie() ?? 0;
        $corpsList = $em->getRepository(Corpsefa::class)->findByCategorie($categorie, false); // ≤ 3
        $indices   = $em->getRepository(Statusfon::class)->findAll(); // ou ton repo d'indices
        $echelles = $echelleRepository->findByCategorie($categorie);

        return $this->render('avancement/_efa_modal_content.html.twig', [
            'agent'     => $agent,
            'situation' => $situation,
            'corpsList' => $corpsList,
            'indices'   => $indices,
            'echelles'   => $echelles,
        ]);
    }
}
