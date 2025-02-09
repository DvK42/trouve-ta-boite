<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Company;
use App\Entity\Student;
use App\Service\MailService;
use App\Security\UserAuthenticator;
use App\Form\RegisterChoiceFormType;
use App\Form\RegisterStudentFormType;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\RegisterCompanyStep1FormType;
use App\Form\RegisterCompanyStep2FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class SecurityController extends AbstractController
{
    public function __construct(
        private UserAuthenticatorInterface $userAuthenticator,
        private UserAuthenticator $authenticator
    ) {}

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }
        $error = $authenticationUtils->getLastAuthenticationError();

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
    public function registerChoice(Request $request): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }
        $form = $this->createForm(RegisterChoiceFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $type = $form->get('type')->getData();
            if($type == "student"){
                return $this->redirectToRoute('app_register_student');
            }
            if($type == "company"){
                return $this->redirectToRoute('app_register_company_1');
            }
        }

        return $this->render('security/register/choice.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/register/student', name: 'app_register_student')]
    public function registerStudent(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, MailService $mailService): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        $student = new Student();
        $form = $this->createForm(RegisterStudentFormType::class, $student);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $passwordHasher->hashPassword($student, $student->getPassword());
            $student->setPassword($hashedPassword);
            $entityManager->persist($student);
            $entityManager->flush();

            $mailService->sendWelcomeEmail($student->getEmail(), $student->getFirstName(), 'student');

            $this->userAuthenticator->authenticateUser(
                $student,
                $this->authenticator,
                $request
            );
            
            $this->addFlash('success', 'Étudiant créé avec succès.');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('security/register/student.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    #[Route(path: '/register/company/step1', name: 'app_register_company_1')]
    public function registerCompanyStep1(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, MailService $mailService): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        $company = new Company();
        $form = $this->createForm(RegisterCompanyStep1FormType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $company->setName($form->get('name')->getData());
            $company->setEmail($form->get('email')->getData());
            $hashedPassword = $passwordHasher->hashPassword($company, $form->get('password')->getData());
            $company->setPassword($hashedPassword);
            $company->setLocation($form->get('location')->getData());
            $company->setAddress($form->get('address')->getData());
            $company->setAddressComplement($form->get('addressComplement')->getData());
            $company->setPostalCode($form->get('postalCode')->getData());
            $company->setEmployeeCount($form->get('employeeCount')->getData());
            $company->setYearFounded($form->get('yearFounded')->getData());
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

            $mailService->sendWelcomeEmail($company->getEmail(), $company->getName(), 'student');

            $this->userAuthenticator->authenticateUser(
                $company,
                $this->authenticator,
                $request
            );

            $this->addFlash('success', 'Entreprise créée avec succès.');
            return $this->redirectToRoute('app_register_company_2', ['id' => $company->getId()]);
        }

        return $this->render('security/register/company-step-1.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/register/company/step2/{id}', name: 'app_register_company_2')]
    public function registerCompanyStep2(Request $request, EntityManagerInterface $entityManager, Company $company): Response
    {
        $form = $this->createForm(RegisterCompanyStep2FormType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Les informations on été ajoutées a l\'entreprise.');
            return $this->redirectToRoute('app_home');
        } 

        return $this->render('security/register/company-step-2.html.twig', [
            'form' => $form->createView(),
        ]);    
    }
}
