<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Company;
use App\Entity\Student;
use App\Form\RegisterChoiceFormType;
use App\Form\RegisterCompanyFormType;
use App\Form\RegisterStudentFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
    
    #[Route(path: '/register', name: 'app_register')]
    public function register(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }
        $step = $request->get('step', 1); 
        $type = $request->get('type'); 
        
        if ($step == 1) {
            $form = $this->createForm(RegisterChoiceFormType::class);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $type = $form->get('type')->getData();
                return $this->redirectToRoute('app_register', ['step' => 2, 'type' => $type]);
            }

            return $this->render('security/register/choice.html.twig', [
                'form' => $form->createView(),
            ]);
        }

        if ($step == 2) {
            if ($type === 'student') {
                $student = new Student();
                $form = $this->createForm(RegisterStudentFormType::class, $student);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    $hashedPassword = $passwordHasher->hashPassword($student, $student->getPassword());
                    $student->setPassword($hashedPassword);
                    $entityManager->persist($student);
                    $entityManager->flush();

                    $this->addFlash('success', 'Étudiant créé avec succès.');
                    return $this->redirectToRoute('app_home');
                }

                return $this->render('security/register/student.html.twig', [
                    'form' => $form->createView(),
                ]);
            }

            if ($type === 'company') {
                $company = new Company();
                $form = $this->createForm(RegisterCompanyFormType::class, $company);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {

                    $company = $form->getData();

                    $company->setEmail($form->get('email')->getData());
                    $hashedPassword = $passwordHasher->hashPassword($company, $form->get('password')->getData());
                    $company->setPassword($hashedPassword);
                    $company->setRoles(['ROLE_COMPANY']);

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
                    $entityManager->persist($company);
                    $entityManager->flush();

                    $this->addFlash('success', 'Entreprise créée avec succès.');
                    return $this->redirectToRoute('app_home');
                }

                return $this->render('security/register/company.html.twig', [
                    'form' => $form->createView(),
                ]);
            }
        }

        return $this->render('security/register.html.twig');
    }
}
