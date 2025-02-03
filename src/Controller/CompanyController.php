<?php

namespace App\Controller;

use App\Entity\Company;
use App\Repository\CompanyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CompanyController extends AbstractController
{
    #[Route('/entreprise/{id}', name: 'app_company_detail')]
    public function detail(int $id, CompanyRepository $companyRepository): Response
    {
        $company = $companyRepository->find($id);

        $stageOffers = $company->getOffers()->filter(function ($offer) {
            return $offer->getType() === 'stage';
        });

        $alternanceOffers = $company->getOffers()->filter(function ($offer) {
            return $offer->getType() === 'alternance';
        });

        return $this->render('company/detail.html.twig', [
            'company' => $company,
            'stageOffers' => $stageOffers,
            'alternanceOffers' => $alternanceOffers,
        ]);
    }
}
