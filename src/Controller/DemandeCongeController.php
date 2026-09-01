<?php

namespace App\Controller;

use App\Entity\DemandeConge;
use App\Form\DemandeCongeType;
use App\Repository\DemandeCongeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/conge')]
class DemandeCongeController extends AbstractController
{
    #[Route('/', name: 'conge_index', methods: ['GET'])]
    public function index(Request $request, DemandeCongeRepository $repo): Response
    {
        $q     = $request->query->get('q');
        $page  = $request->query->getInt('page', 1);
        $limit = 15;

        $paginator = $repo->findPaginated($page, $limit, $q);

        return $this->render('conge/index.html.twig', [
            'demandes'    => $paginator,
            'q'           => $q,
            'currentPage' => $page,
            'totalPages'  => (int) ceil($paginator->count() / $limit),
            'total'       => $paginator->count(),
        ]);
    }

    #[Route('/ajout', name: 'conge_ajout', methods: ['GET', 'POST'])]
    public function ajout(Request $request, EntityManagerInterface $em): Response
    {
        if ($request->isMethod('POST')) {
            $demande = new DemandeConge();

            $demande->setMatricule((int) $request->request->get('matricule'));
            $demande->setDateDemande(new \DateTime($request->request->get('date_demande')));
            $demande->setDuree((int) $request->request->get('duree'));
            $demande->setDateDepart(new \DateTime($request->request->get('date_depart')));
            $demande->setDateRetour(new \DateTime($request->request->get('date_retour')));
            $demande->setLieuJouissance($request->request->get('lieu_jouissance'));
            $demande->setMotifs($request->request->get('motifs'));
            $demande->setNbJoursReste((int) ($request->request->get('nb_jours_reste') ?? 0));

            $em->persist($demande);
            $em->flush();

            $this->addFlash('success', 'Demande de congé enregistrée avec succès.');
            return $this->redirectToRoute('conge_index');
        }

        return $this->render('conge/new.html.twig');
    }
//    #[Route('/{id}', name: 'conge_show', methods: ['GET'])]
//    public function show(DemandeConge $demande): Response
//    {
//        return $this->render('conge/show.html.twig', [
//            'demande' => $demande,
//        ]);
//    }

    #[Route('/{id}/edit', name: 'conge_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DemandeConge $demande, EntityManagerInterface $em): Response
    {
        if ($request->isMethod('POST')) {
            $demande->setMatricule((int) $request->request->get('matricule'));
            $demande->setDateDemande(new \DateTime($request->request->get('date_demande')));
            $demande->setDuree((int) $request->request->get('duree'));
            $demande->setDateDepart(new \DateTime($request->request->get('date_depart')));
            $demande->setDateRetour(new \DateTime($request->request->get('date_retour')));
            $demande->setLieuJouissance($request->request->get('lieu_jouissance'));
            $demande->setMotifs($request->request->get('motifs'));
            $demande->setNbJoursReste((int) ($request->request->get('nb_jours_reste') ?? 0));

            $em->flush();

            $this->addFlash('success', 'Demande modifiée avec succès.');
            return $this->redirectToRoute('conge_index');
        }

        return $this->render('conge/edit.html.twig', [
            'demande' => $demande,
        ]);
    }

    #[Route('/{id}', name: 'conge_delete', methods: ['POST'])]
    public function delete(Request $request, DemandeConge $demande, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$demande->getId(), $request->request->get('_token'))) {
            $em->remove($demande);
            $em->flush();
            $this->addFlash('success', 'Demande supprimée.');
        }

        return $this->redirectToRoute('conge_index');
    }
}
