<?php

namespace App\Controller;

use App\Entity\Application;
use App\Entity\Student;
use App\Form\ApplicationFormType;
use App\Repository\OfferRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
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
            return $application->getOfferId()->getId() === $offer->getId();
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
            $application->setStudentId($student);
            $application->setOfferId($offer);
            $application->setCoverLetter($data['coverLetter']);
            $application->setDate(new DateTime('now'));
            $entityManager->persist($application);

            $entityManager->flush();
            
            $this->addFlash('success', 'Votre candidature a été envoyée avec succès.');
            return $this->redirectToRoute('app_offer_detail', ['type' => $offer->getType(), 'id' => $offer->getId()]);
        }else{
            $this->addFlash('error', 'Une erreur est survenue.');
            return $this->redirectToRoute('app_offer_apply', ['type' => $offer->getType(), 'id' => $offer->getId()]);
        }
    }
}
