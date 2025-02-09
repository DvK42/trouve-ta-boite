<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Application;
use App\Form\StudentUpdateFormType;
use App\Form\UserPasswordUpdateFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AccountController extends AbstractController
{
    #[Route('/mon-compte', name: 'app_account')]
    #[IsGranted('ROLE_USER')]
    public function account(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        
        $passwordForm = $this->createForm(UserPasswordUpdateFormType::class, $user);
        
        $passwordForm->handleRequest($request);
        if ($passwordForm->isSubmitted() && $passwordForm->isValid()) {
            $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);
            $entityManager->persist($user);
            $entityManager->flush();
            
            $this->addFlash('success', 'Votre mot de passe a été mit à jour avec succès.');
            return $this->redirectToRoute('app_account');
            
        }
        
        if($this->isGranted('ROLE_STUDENT')){
            /** @var Student $user */
            $form = $this->createForm(StudentUpdateFormType::class, $user);
            $form->handleRequest($request);
            
            if ($form->isSubmitted() && $form->isValid()) {
                $profilePictureFile = $form->get('profilePicture')->getData();
                if ($profilePictureFile) {
                    $newFilename = uniqid() . '.' . $profilePictureFile->guessExtension();
                    $profilePictureFile->move(
                        $this->getParameter('profile_pictures_directory'),
                        $newFilename
                    );
                    /** @var Student $user */
                    $user->setProfilePicture($newFilename);
                }
                $entityManager->persist($user);
                $entityManager->flush();
                
                $this->addFlash('success', 'Vos informations ont été mises à jour avec succès.');
                return $this->redirectToRoute('app_account');
            }
        }

        return $this->render('account/index.html.twig', [
            'form' => $this->isGranted('ROLE_STUDENT') ? $form->createView() : null,
            'passwordForm' => $passwordForm->createView(),
        ]);
    }

    #[Route('/mon-compte/candidatures', name: 'app_student_applications')]
    public function applications(EntityManagerInterface $entityManager): Response
    {
        /** @var Student $student */
        $student = $this->getUser();

        $applications = $student->getApplications();

        return $this->render('student/applications.html.twig', [
            'applications' => $applications,
        ]);
    }

    #[Route('/mon-compte/candidature/{id}/delete', name: 'app_student_application_delete')]
    public function applicationDelete(int $id, EntityManagerInterface $entityManager): Response
    {
        $application = $entityManager->getRepository(Application::class)->find($id);
        if (!$application) {
            $this->addFlash('error', 'Candidature non trouvée.');
            return $this->redirectToRoute('app_student_applications');
        }

        // Marquer comme supprimée
        $application->softDelete();

        $entityManager->flush();

        $this->addFlash('success', 'La candidature a été supprimée avec succès.');
        return $this->redirectToRoute('app_student_applications');
    }

}
