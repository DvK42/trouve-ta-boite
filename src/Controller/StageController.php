<?php

namespace App\Controller;

use App\Repository\OfferRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StageController extends AbstractController
{
    #[Route('/stage', name: 'app_stage_list')]
    public function index(OfferRepository $offerRepository, PaginatorInterface $paginator, Request $request): Response
    {
        // Récupérer tous les stages depuis la base de données
        $query = $offerRepository->findStages();

        // Ajouter la pagination
        $pagination = $paginator->paginate(
            $query, // La requête
            $request->query->getInt('p', 1), // Numéro de la page actuelle
            9 // Nombre d'éléments par page
        );

        foreach ($pagination->getItems() as $stage) {
            $startDate = $stage->getStartDate();
            $endDate = $stage->getEndDate();
            $duration = $startDate->diff($endDate)->days; // Calcul de la durée en jours
            $stage->duration = $duration; // Stocker temporairement la durée
        }

        $totalStages = $pagination->getTotalItemCount();

        return $this->render('stage/index.html.twig', [
            'pagination' => $pagination,
            'totalStages' => $totalStages,
        ]);
    }

    #[Route('/stage/{id}', name: 'app_stage_detail')]
    public function detail($id, OfferRepository $offerRepository): Response
    {
        $stage = $offerRepository->find($id);

        if (!$stage) {
            throw $this->createNotFoundException('Stage non trouvé');
        }

        return $this->render('stage/detail.html.twig', [
            'stage' => $stage,
        ]);
    }
}
