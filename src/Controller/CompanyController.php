<?php

namespace App\Controller;

use App\Entity\Company;
use App\Form\CompanyUpdateFormType;
use App\Repository\CompanyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

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

    #[Route('/mon-entreprise', name: 'app_company_edit')]
    public function edit(Request $request, EntityManagerInterface $entityManager): Response
    {
        /** @var Company $company */
        $company = $this->getUser();

        $form = $this->createForm(CompanyUpdateFormType::class, $company);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $logoFile */
            $logoFile = $form->get('logo')->getData();

            if ($logoFile) {
                $newFilename = uniqid() . '.' . $logoFile->guessExtension();

                try {
                    $logoFile->move(
                        $this->getParameter('logos_directory'),
                        $newFilename
                    );
                    
                    $company->setLogo($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('danger', 'Erreur lors de l\'upload du logo.');
                }
            }
            
            $entityManager->persist($company);
            $entityManager->flush();

            $this->addFlash('success', 'Vos informations ont été mises à jour avec succès.');
            return $this->redirectToRoute('app_company_edit');
            
        } 
        return $this->render('company/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
