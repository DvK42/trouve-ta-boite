<?php

namespace App\Controller;

use App\Entity\Student;
use App\Form\ApplicationFormType;
use App\Repository\OfferRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OfferController extends AbstractController
{
    #[Route('/{type}/{id}', name: 'app_offer_detail', requirements: ['type' => 'stage|alternance'])]
    public function detail(string $type, int $id, OfferRepository $offerRepository): Response
    {
        $offer = $offerRepository->find($id);
        $hasApplied = false;
        

        if (!$offer) {
            throw $this->createNotFoundException('Offre non trouvée');
        }

        if ($type !== $offer->getType()) {
            throw $this->createNotFoundException('Type d\'offre non valide');
        }

        if ($offer->getDeletedAt() !== null && $offer->getCompany() !== $this->getUser()) {
            $this->addFlash('error', 'Vous ne pouvez pas accéder à cette offre car elle a été supprimée.');
            return $this->redirectToRoute('app_home');
        }

        if($this->isGranted('ROLE_STUDENT')){
            /** @var Student $user */
            $user = $this->getUser();
            $hasApplied = $user->getApplications()->exists(function ($key, $application) use ($offer) {
                return $application->getOffer()->getId() === $offer->getId() && $application->getDeletedAt() === null;
            });
        }
        
        $startDate = $offer->getStartDate();
        $endDate = $offer->getEndDate();
        $duration = $startDate->diff($endDate)->days;
        $offer->duration = $duration;

        $similarOffers = $offerRepository->findSimilarOffers($type, $id, 8);
        $routeListName = $type === 'stage' ? 'app_stage_list' : 'app_alternance_list';
        $applicationsCount = $offer->getApplicationsCount();


        return $this->render('offer/detail.html.twig', [
            'offer' => $offer,
            'similarOffers' => $similarOffers,
            'routeListName' => $routeListName,
            'applicationsCount' => $applicationsCount,
            'hasApplied' => $hasApplied,
        ]);
    }

    #[Route('/{type}/{id}/postuler', name: 'app_offer_apply', requirements: ['type' => 'stage|alternance'])]
    public function apply(string $type, int $id, OfferRepository $offerRepository): Response
    {
        // Restriction -- ROLE_STUDENT
        
        $offer = $offerRepository->find($id);
        $user = $this->getUser();
        
        if($this->isGranted('ROLE_STUDENT')){
            /** @var Student $student */
            $student = $user;
            
            $hasApplied = $student->getApplications()->exists(function ($key, $application) use ($offer) {
                return $application->getOffer()->getId() === $offer->getId();
            });
        }else{
            $this->addFlash('error', 'Vous n\'avez pas les permissions pour postuler !');
            return $this->redirectToRoute('app_offer_detail', ['type' => $offer->getType(), 'id' => $offer->getId()]);
        }
        
        // Sécurité -- l'utilisateur a déjà postulé

        if($hasApplied) {
            $this->addFlash('error', 'Vous avez déjà postulé à cette offre !');
            return $this->redirectToRoute('app_offer_detail', ['type' => $offer->getType(), 'id' => $offer->getId()]);
        }

        $form = $this->createForm(ApplicationFormType::class, [
            'gender' => $student->getGender(),
            'firstName' => $student->getFirstName(),
            'lastName' => $student->getLastName(),
            'dateOfBirth' => $student->getDateOfBirth(),
            'phone' => $student->getPhone(),
            'email' => $student->getEmail(),
            'address' => $student->getAddress(),
            'addressComplement' => $student->getAddressComplement(),
            'postalCode' => $student->getPostalCode(),
            'city' => $student->getCity(),
            'portfolioUrl' => $student->getPortfolioUrl(),
            'isDriver' => $student->isDriver(),
            'isHandicap' => $student->isHandicap(),
            'skills' => $student->getSkills(),
        ]);

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

        return $this->render('offer/apply.html.twig', [
            'offer' => $offer,
            'student' => $student,
            'studentSkills' => $student->getSkills(),
            'form' => $form->createView(),
        ]);
    }

    #[Route('/mon-entreprise/mes-offres/{id}/delete', name: 'app_offer_delete', requirements: ['type' => 'stage|alternance'])]
    public function delete(int $id, Request $request, OfferRepository $offerRepository, EntityManagerInterface $entityManager): Response
    {
        $offer = $offerRepository->find($id);

        if (!$offer) {
            $this->addFlash('danger', 'L\'offre demandée n\'existe pas.');
            return $this->redirectToRoute('app_company_offer_list');
        }

        $offer->softDelete();
        $entityManager->flush();

        $this->addFlash('success', 'L\'offre a été supprimée avec succès.');

        $origin = $request->query->get('origin', 'app_company_offer_list');
        return $this->redirectToRoute($origin);
    }
}
