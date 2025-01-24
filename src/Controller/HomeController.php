<?php

namespace App\Controller;

use App\Repository\OfferRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(OfferRepository $offerRepository): Response
    {
        $countStages = $offerRepository->countByType('stage');
        $countAlternances = $offerRepository->countByType('alternance');

        if ($this->getUser()) {
            return $this->render('home/connected.html.twig', [
                'user' => $this->getUser(),
            ]);
        }

        return $this->render('home/landing.html.twig', [
            'countStages' => $countStages,
            'countAlternances' => $countAlternances,
        ]);
    }
}
