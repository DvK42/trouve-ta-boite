<?php

namespace App\Controller;

use App\Repository\CompanyRepository;
use App\Repository\OfferRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(OfferRepository $offerRepository, CompanyRepository $companyRepository): Response
    {
        $countStages = $offerRepository->countByType('stage');
        $countAlternances = $offerRepository->countByType('alternance');
        $offers = $offerRepository->findLatestOffers();
        $topCompanies = $companyRepository->findTopCompaniesWithActiveOffers();

        return $this->render('home/landing.html.twig', [
            'countStages' => $countStages,
            'countAlternances' => $countAlternances,
            'lastestOffers' => $offers,
            'topCompanies' => $topCompanies,
            'user' => $this->getUser(),
        ]);
    }
}
