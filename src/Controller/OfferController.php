<?php

namespace App\Controller;

use App\Repository\OfferRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OfferController extends AbstractController
{
    #[Route('/{type}/{id}', name: 'app_offer_detail', requirements: ['type' => 'stage|alternance'])]
    public function detail(string $type, int $id, OfferRepository $offerRepository): Response
    {
        $offer = $offerRepository->find($id);

        $startDate = $offer->getStartDate();
        $endDate = $offer->getEndDate();
        $duration = $startDate->diff($endDate)->days;
        $offer->duration = $duration;

        if (!$offer) {
            throw $this->createNotFoundException('Offre non trouvée');
        }

        if ($type !== $offer->getType()) {
            throw $this->createNotFoundException('Type d\'offre non valide');
        }

        $similarOffers = $offerRepository->findSimilarOffers($type, $id, 8);
        $routeListName = $type === 'stage' ? 'app_stage_list' : 'app_alternance_list';
        $applicationsCount = $offer->getApplicationsCount();

        return $this->render('offer/detail.html.twig', [
            'offer' => $offer,
            'similarOffers' => $similarOffers,
            'routeListName' => $routeListName,
            'applicationsCount' => $applicationsCount,
        ]);
    }

    #[Route('/{type}/{id}/postuler', name: 'app_offer_apply', requirements: ['type' => 'stage|alternance'])]
    public function apply(string $type, int $id, OfferRepository $offerRepository): Response
    {
        $offer = $offerRepository->find($id);

        $startDate = $offer->getStartDate();
        $endDate = $offer->getEndDate();
        $duration = $startDate->diff($endDate)->days;
        $offer->duration = $duration;

        if (!$offer) {
            throw $this->createNotFoundException('Offre non trouvée');
        }

        if ($type !== $offer->getType()) {
            throw $this->createNotFoundException('Type d\'offre non valide');
        }

        return $this->render('offer/detail.html.twig', [
            'offer' => $offer,
        ]);
    }
}
