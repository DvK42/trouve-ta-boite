<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use App\Entity\User;
use App\Entity\Student;
use App\Entity\Company;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Créer un administrateur
        $admin = new Admin();
        $admin->setEmail('admin@example.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin123'));
        $manager->persist($admin);


        // Créer un étudiant
        $student = new Student();
        $student->setEmail('student@example.com');
        $student->setRoles(['ROLE_STUDENT']);
        $student->setPassword($this->passwordHasher->hashPassword($student, 'student123'));
        $student->setFirstName('John');
        $student->setLastName('Doe');
        $student->setEducation('Computer Science');
        $manager->persist($student);

        // Créer une entreprise
        $company = new Company();
        $company->setEmail('company@example.com');
        $company->setRoles(['ROLE_COMPANY']);
        $company->setPassword($this->passwordHasher->hashPassword($company, 'company123'));
        $company->setName('TechCorp');
        $company->setSector('Technology');
        $company->setDescription('A leading tech company.');
        $manager->persist($company);

        // Sauvegarder les données en base
        $manager->flush();
    }
}

