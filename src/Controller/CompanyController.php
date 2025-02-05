<?php

namespace App\Controller;

use App\Entity\Offer;
use App\Entity\Company;
use App\Form\OfferFormType;
use App\Form\CompanyUpdateFormType;
use App\Repository\CompanyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
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

    #[Route('/mon-entreprise/mes-offres', name: 'app_company_offer_list')]
    public function offerList(PaginatorInterface $paginator, Request $request): Response
    {
        /** @var Company $company */
        $company = $this->getUser();

        $offers = $company->getOffers();

        $pagination = $paginator->paginate(
            $offers,
            $request->query->getInt('page', 1),
            20
        );

        return $this->render('company/offer-list.html.twig', [
            'company' => $company,
            'offers' => $offers,
            'pagination' => $pagination,
        ]); 
    }

    #[Route('/mon-entreprise/nouvelle-offre', name: 'app_company_offer_new')]
    public function newOffer(Request $request, EntityManagerInterface $entityManager): Response
    {
        $offer = new Offer();

        $form = $this->createForm(OfferFormType::class, $offer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Company $company */
            $company = $this->getUser();
            
            $offer->setCompany($company);
            
            $offer->setCreatedAt(new \DateTimeImmutable());

            $missions = json_decode($form->get('missionList')->getData(), true);
            $profiles = json_decode($form->get('profileSearchedList')->getData(), true);

            $offer->setMissionList($missions);
            $offer->setProfileSearchedList($profiles);

            $entityManager->persist($offer);

            foreach ($form->get('skills')->getData() as $skill) {
                $offer->addSkill($skill);
            }

            $entityManager->flush();

            $this->addFlash('success', 'Offre créée avec succès.');
            return $this->redirectToRoute('app_company_offer_list');
        }

        return $this->render('company/new_offer.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
