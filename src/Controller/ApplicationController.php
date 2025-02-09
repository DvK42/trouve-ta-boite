<?php

namespace App\Controller;

use DateTime;
use App\Entity\Student;
use App\Entity\Application;
use App\Form\ApplicationFormType;
use App\Repository\OfferRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ApplicationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApplicationController extends AbstractController
{
    #[Route('/{type}/{id}/postuler/post', name: 'app_offer_apply_application', requirements: ['type' => 'stage|alternance'])]
    public function post(Request $request, int $id, EntityManagerInterface $entityManager, OfferRepository $offerRepository): Response
    {
        $form = $this->createForm(ApplicationFormType::class);

        $user = $this->getUser();
        /** @var Student $student */
        $student = $user;
        $offer = $offerRepository->find($id);

        $hasApplied = $student->getApplications()->exists(function ($key, $application) use ($offer) {
            return $application->getOffer()->getId() === $offer->getId();
        });

        if($hasApplied){
            $this->addFlash('error', 'Vous avez déjà postulé à cette offre.');
            return $this->redirectToRoute('app_offer_detail', ['type' => $offer->getType(), 'id' => $offer->getId()]);
        }

        $form->handleRequest($request);

        $data = $form->getData();

        if ($form->isSubmitted() && $form->isValid()) {
            if ($data['updateInfo']) {
                // Mettre à jour les informations de l'étudiant si upateInfo est check
                $student->setGender($data['gender']);
                $student->setFirstName($data['firstName']);
                $student->setLastName($data['lastName']);
                $student->setDateOfBirth($data['dateOfBirth']);
                $student->setPhone($data['phone']);
                $student->setAddress($data['address']);
                $student->setAddressComplement($data['addressComplement']);
                $student->setPostalCode($data['postalCode']);
                $student->setCity($data['city']);
                $student->setPortfolioUrl($data['portfolioUrl']);
                $student->setIsDriver($data['isDriver']);
                $student->setIsHandicap($data['isHandicap']);

                $profilePictureFile = $form->get('profilePicture')->getData();
                if ($profilePictureFile) {
                    $newFilename = uniqid() . '.' . $profilePictureFile->guessExtension();
                    $profilePictureFile->move(
                        $this->getParameter('profile_pictures_directory'),
                        $newFilename
                    );
                    $student->setProfilePicture($newFilename);
                }

                $student->getSkills()->clear();
                foreach ($data['skills'] as $skill) {
                    $student->addSkill($skill);
                }

                $entityManager->persist($student);
            }

            $application = new Application();
            $application->setStudent($student);
            $application->setOffer($offer);
            $application->setCoverLetter($data['coverLetter']);
            $application->setCreatedAt(new DateTime('now'));
            $entityManager->persist($application);

            $entityManager->flush();
            
            $this->addFlash('success', 'Votre candidature a été envoyée avec succès.');
            return $this->redirectToRoute('app_offer_detail', ['type' => $offer->getType(), 'id' => $offer->getId()]);
        }else{
            $this->addFlash('error', 'Une erreur est survenue.');
            return $this->redirectToRoute('app_offer_apply', ['type' => $offer->getType(), 'id' => $offer->getId()]);
        }
    }

    #[Route('/mon-entreprise/mes-offres/{id}/candidatures', name: 'app_offer_application_list')]
    public function applicationList(Request $request, int $id, OfferRepository $offerRepository): Response
    {
        if(!$this->getUser()){
            $this->addFlash('error', 'Vous n\'êtes pas autorisé à voir ceci.');
            return $this->redirectToRoute('app_home');
        }

        $user = $this->getUser();
        /** @var Company $company */
        $company = $user;

        $offer = $offerRepository->find($id);

        if (!$offer) {
            $this->addFlash('error', 'L\'offre demandée n\'existe pas.');
            return $this->redirectToRoute('app_company_offer_list');
        }

        if($offer->getCompany()->getId() !== $company->getId() ){
            $this->addFlash('error', 'Ceci n\'est pas à vous...');
            return $this->redirectToRoute('app_company_offer_list');

        }

        $applications = $offer->getApplications();

        return $this->render('offer/application_list.html.twig', [
            'offer' => $offer,
            'applications' => $applications,
        ]);
    }

    #[Route('/mon-entreprise/mes-offres/candidature/{id}', name: 'app_application_detail')]
    public function applicationDetail(int $id, ApplicationRepository $applicationRepository): Response
    {
        if(!$this->getUser()){
            $this->addFlash('error', 'Vous n\'êtes pas autorisé à voir ceci.');
            return $this->redirectToRoute('app_home');
        }

        $user = $this->getUser();
        /** @var Company $company */
        $company = $user;

        $application = $applicationRepository->find($id);

        if (!$application) {
            $this->addFlash('error', 'Candidature non trouvée.');
            return $this->redirectToRoute('app_company_offer_list');
        }

        if($application->getOffer()->getCompany()->getId() !== $company->getId() ){
            $this->addFlash('error', 'Ceci n\'est pas à vous...');
            return $this->redirectToRoute('app_company_offer_list');

        }

        return $this->render('offer/application-detail.html.twig', [
            'application' => $application,
            'student' => $application->getStudent(),
        ]);
    }

}
