<?php

namespace App\Controller;

use App\Repository\OfferRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AlternanceController extends AbstractController
{
    #[Route('/alternance', name: 'app_alternance_list')]
    public function index(OfferRepository $offerRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $offerRepository->findByType('alternance');

        $pagination = $paginator->paginate(
            $query, 
            $request->query->getInt('p', 1),
            9
        );

        foreach ($pagination->getItems() as $alternance) {
            $startDate = $alternance->getStartDate();
            $endDate = $alternance->getEndDate();
            $duration = $startDate->diff($endDate)->days;
            $alternance->duration = $duration;
        }

        $totalAlternances = $pagination->getTotalItemCount();

        return $this->render('alternance/index.html.twig', [
            'pagination' => $pagination,
            'totalAlternances' => $totalAlternances,
        ]);
    }

    #[Route('/alternance/{id}', name: 'app_alternance_detail')]
    public function detail($id, OfferRepository $offerRepository): Response
    {
        $alternance = $offerRepository->find($id);

        if (!$alternance) {
            throw $this->createNotFoundException('Alternance non trouvé');
        }

        return $this->render('alternance/detail.html.twig', [
            'alternance' => $alternance,
        ]);
    }
}
