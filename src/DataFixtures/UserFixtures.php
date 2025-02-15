<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Admin;
use App\Entity\Sector;
use App\Entity\Company;
use App\Entity\Student;
use App\Enum\EducationLevel;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements OrderedFixtureInterface
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        
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
        $student->setEducation(array_rand(EducationLevel::LEVELS));
        $student->setStudyPlace('ESGI');
        $student->setGender('homme'); 
        $student->setDateOfBirth(new \DateTime('1995-05-15')); 
        $student->setPhone('0612345678'); 
        $student->setAddress('123 Rue Exemple'); 
        $student->setAddressComplement('Appartement 4B'); 
        $student->setCity('Paris'); 
        $student->setPostalCode('75001'); 
        $student->setPortfolioUrl('https://portfolio.com');
        $student->setIsDriver(true); 
        $student->setIsHandicap(false); 
        $manager->persist($student);

        $sectors = $manager->getRepository(Sector::class)->findAll();

        // Créer une entreprise
        $company = new Company();
        $company->setEmail('company@example.com');
        $company->setRoles(['ROLE_COMPANY']);
        $company->setPassword($this->passwordHasher->hashPassword($company, 'company123'));
        $company->setName('TechCorp');
        $company->setLocation('Paris');
        $company->setDescription('A leading tech company.');
        $company->setAddress('8 ' . $faker->address);
        $company->setAddressComplement($faker->secondaryAddress);
        $company->setPostalCode($faker->postcode);
        $company->setLocation($faker->city);
        $company->setPhone($faker->phoneNumber);
        $company->setContactEmail($faker->companyEmail);
        $company->setEmployeeCount($faker->numberBetween(10, 500));
        $company->setYearFounded(2025);
        $company->setCatchPhrase($faker->catchPhrase);
        if (!empty($sectors)) {
            $randomSector = $sectors[array_rand($sectors)];
            $company->setSector($randomSector);
        }
        $manager->persist($company);

        // Sauvegarder les données en base
        $manager->flush();
    }

        public function getOrder(): int
    {
        return 2;
    }

}

