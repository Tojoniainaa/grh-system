<?php

namespace App\Controller;

use App\Entity\Agents;
use App\Form\AgentsType;
use App\Repository\AgentsRepository;
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
    #[Route('/', name: 'agents_index')]
    public function index(): Response
    {
        return $this->render('agents/index.html.twig');
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

            // ======================
            // PHOTO
            // ======================
            $photoFile = $form->get('nomPhotos')->getData();

            if ($photoFile) {
                $newPhotoName = uniqid() . '.' . $photoFile->guessExtension();

                $photoFile->move(
                    $this->getParameter('kernel.project_dir') . '/public/uploads/photos',
                    $newPhotoName
                );

                $agent->setNomPhotos($newPhotoName); // champ dans l'entité
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
}
