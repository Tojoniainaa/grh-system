<?php

namespace App\Controller;

use App\Entity\Agents;
use App\Form\AgentsType;
use App\Repository\AgentsRepository;
use App\Repository\Indiceefa4Repository;
use App\Repository\IndiceefaRepository;
use App\Repository\ServicesRepository;
use App\Repository\SituationAdmRepository;
use App\Repository\StatusfonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/agents')]
class AgentsController extends AbstractController
{
    #[Route('/', name: 'agents_index', methods: ['GET'])]
    public function index(Request $request, AgentsRepository $agentsRepository): Response
    {
        $q     = $request->query->get('q');
        $page  = $request->query->getInt('page', 1);
        $limit = 10; // agents par page

        $paginator = $agentsRepository->findPaginated($page, $limit, $q);

        return $this->render('agents/index.html.twig', [
            'agents'      => $paginator,
            'q'           => $q,
            'currentPage' => $page,
            'totalPages'  => (int) ceil($paginator->count() / $limit),
            'totalAgents' => $paginator->count(),
        ]);
    }

    #[Route('/tableau_borde', name: 'tableau_borde', methods: ['GET'])]
    public function tableauBord(
        SituationAdmRepository $situationAdmRepository,
        ServicesRepository $servicesRepository
    ): Response {

        $totalPersonnes = $situationAdmRepository->countDistinctMatricules();

        $parStatus = $situationAdmRepository->countByStatus();

        $parCategorie = $situationAdmRepository->countByCategorie();

        $totalDirections = $servicesRepository->countDirections();

        $totalServices = $servicesRepository->countServices();

        return $this->render('home/index.html.twig', [
            'totalPersonnes'  => $totalPersonnes,
            'parStatus'       => $parStatus,
            'parCategorie'    => $parCategorie,
            'totalDirections' => $totalDirections,
            'totalServices'   => $totalServices,
            'anneeExercice'   => (int) date('Y'),
        ]);
    }

    #[Route('/ajout', name: 'agents_ajout', methods: ['GET', 'POST'])]
    public function ajout(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $agent = new Agents();
        $form = $this->createForm(AgentsType::class, $agent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Vérification matricule unique
            $existing = $entityManager->getRepository(Agents::class)
                ->findOneBy(['matricule' => $agent->getMatricule()]);

            if ($existing) {
                $this->addFlash('danger', 'Ce matricule existe déjà.');
                return $this->render('agents/new.html.twig', [
                    'form' => $form->createView(),
                ]);
            }

            $projectDir = $this->getParameter('kernel.project_dir');

            // ========== PHOTO ==========
            $photoFile = $form->get('nomPhotos')->getData();
            if ($photoFile) {
                $newPhotoName = uniqid('photo_') . '.' . $photoFile->guessExtension();
                $photoFile->move($projectDir . '/public/uploads/photos', $newPhotoName);

                $agent->setNomPhotos($newPhotoName);
                $agent->setFichesUrlPhotos('/uploads/photos/' . $newPhotoName);
            }

            // ========== PDF ==========
            $pdfFile = $form->get('nomPdf')->getData();
            if ($pdfFile) {
                $newPdfName = uniqid('doc_') . '.' . $pdfFile->guessExtension();
                $pdfFile->move($projectDir . '/public/uploads/pdfs', $newPdfName);

                $agent->setNomPdf($newPdfName);
                $agent->setFichesUrlPdf('/uploads/pdfs/' . $newPdfName);
            }

            $entityManager->persist($agent);
            $entityManager->flush();

            $this->addFlash('success', 'Agent ajouté avec succès.');
            return $this->redirectToRoute('agents_index');
        }

        return $this->render('agents/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/get/indice', name: 'get_indice_by_corps_grade', methods: ['POST'])]
    public function getIndiceByCorpsGrade(
        Request $request,
        StatusfonRepository $statusfonRepo
    ): JsonResponse {
        $data = json_decode($request->getContent(), true) ?? [];

        $codeCorps = trim($data['codeCorps'] ?? '');
        $codeGrade = trim($data['codeGrade'] ?? '');

        if (empty($codeCorps) || empty($codeGrade)) {
            return $this->json([
                'success' => false,
                'message' => 'Code Corps et Code Grade requis'
            ], 400);
        }

        $status = $statusfonRepo->findOneBy(['codeCorps' => $codeCorps, 'grade'     => $codeGrade]);

        if (!$status || $status->getIndice() === null) {
            return $this->json([
                'success' => false,
                'message' => 'Aucun indice trouvé pour cette combinaison'
            ]);
        }

        return $this->json([
            'success' => true,
            'indice'  => $status->getIndice()
        ]);
    }

    #[Route('/get/indice/efa', name: 'get_indice_efa', methods: ['POST'])]
    public function getIndiceEfa(
        Request $request,
        IndiceefaRepository $indiceEfaRepo
    ): JsonResponse {
        $data = json_decode($request->getContent(), true) ?? [];

        $echelle   = trim($data['echelle'] ?? '');
        $echelon   = trim($data['echelon'] ?? '');
        $categorie = trim($data['categorie'] ?? '');

        if (empty($echelle) || empty($echelon)) {
            return $this->json([
                'success' => false,
                'message' => 'Échelle et Échelon requis'
            ], 400);
        }

        $criteria = [
            'echelle' => $echelle,
            'echelon' => $echelon,
        ];
        // On filtre aussi par catégorie si elle est envoyée
        if ($categorie !== '' && $categorie !== '0') {
            $criteria['categorie'] = $categorie;
        }

        $indiceEfa = $indiceEfaRepo->findOneBy($criteria);

        if (!$indiceEfa || $indiceEfa->getIndice() === null) {
            return $this->json([
                'success' => false,
                'message' => 'Aucun indice trouvé pour cette combinaison'
            ]);
        }

        return $this->json([
            'success' => true,
            'indice'  => $indiceEfa->getIndice()
        ]);
    }

    #[Route('/get/indice/efa4', name: 'get_indice_efa4', methods: ['POST'])]
    public function getIndiceEfa4(
        Request $request,
        Indiceefa4Repository $indiceEfa4Repo
    ): JsonResponse {
        $data = json_decode($request->getContent(), true) ?? [];

        $codeCorps = trim($data['codeCorps'] ?? '');
        $codeGrade = trim($data['codeGrade'] ?? '');
        $categorie = trim($data['categorie'] ?? '');

        if (empty($codeCorps) || empty($codeGrade)) {
            return $this->json(['success' => false, 'message' => 'Code Corps et Code Grade requis'], 400);
        }

        $criteria = ['codeCorps' => $codeCorps, 'codeGrade' => $codeGrade,
        ];

        if ($categorie !== '' && $categorie !== '0') {
            $criteria['categorie'] = $categorie;
        }

        $indiceEfa4 = $indiceEfa4Repo->findOneBy($criteria);

        if (!$indiceEfa4 || $indiceEfa4->getIndice() === null) {
            return $this->json(['success' => false, 'message' => 'Aucun indice trouvé pour cette combinaison']);
        }

        return $this->json([
            'success' => true,
            'indice'  => $indiceEfa4->getIndice()
        ]);
    }

    #[Route('/{id}', name: 'app_agents_show', methods: ['GET'])]
    public function show(Agents $agent): Response
    {
        return $this->render('agents/show.html.twig', [
            'agent' => $agent,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_agents_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Agents $agent, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(AgentsType::class, $agent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion photo
            $photoFile = $form->get('nomPhotos')->getData();
            if ($photoFile) {
                // Supprimer l'ancienne photo si elle existe
                if ($agent->getNomPhotos()) {
                    $oldFile = $this->getParameter('agents_photos_directory').'/'.$agent->getNomPhotos();
                    if (file_exists($oldFile)) {
                        unlink($oldFile);
                    }
                }
                $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$photoFile->guessExtension();
                $photoFile->move($this->getParameter('agents_photos_directory'), $newFilename);
                $agent->setNomPhotos($newFilename);
            }

            // Gestion PDF
            $pdfFile = $form->get('nomPdf')->getData();
            if ($pdfFile) {
                if ($agent->getNomPdf()) {
                    $oldFile = $this->getParameter('agents_pdf_directory').'/'.$agent->getNomPdf();
                    if (file_exists($oldFile)) {
                        unlink($oldFile);
                    }
                }
                $originalFilename = pathinfo($pdfFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$pdfFile->guessExtension();
                $pdfFile->move($this->getParameter('agents_pdf_directory'), $newFilename);
                $agent->setNomPdf($newFilename);
            }

            $entityManager->flush();

            $this->addFlash('success', 'Agent modifié avec succès.');
            return $this->redirectToRoute('agents_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('agents/edit.html.twig', [
            'agent' => $agent,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_agents_delete', methods: ['POST'])]
    public function delete(Request $request, Agents $agent, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$agent->getId(), $request->request->get('_token'))) {
            // Supprimer les fichiers
            if ($agent->getNomPhotos()) {
                $file = $this->getParameter('agents_photos_directory').'/'.$agent->getNomPhotos();
                if (file_exists($file)) {
                    unlink($file);
                }
            }
            if ($agent->getNomPdf()) {
                $file = $this->getParameter('agents_pdf_directory').'/'.$agent->getNomPdf();
                if (file_exists($file)) {
                    unlink($file);
                }
            }

            $entityManager->remove($agent);
            $entityManager->flush();
            $this->addFlash('success', 'Agent supprimé avec succès.');
        }

        return $this->redirectToRoute('agents_index', [], Response::HTTP_SEE_OTHER);
    }
}
