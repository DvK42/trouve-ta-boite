<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Student;
use App\Enum\EducationLevel;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class StudentFixtures extends Fixture implements OrderedFixtureInterface
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 50; $i++) {
            $student = new Student();

            $student->setEmail($faker->unique()->email);
            $student->setRoles(['ROLE_STUDENT']);
            $student->setPassword(
                $this->passwordHasher->hashPassword($student, 'password123')
            );
            $student->setFirstName($faker->firstName);
            $student->setLastName($faker->lastName);
            $student->setEducation(array_rand(EducationLevel::LEVELS));
            $student->setStudyPlace($faker->catchPhrase);

            $student->setGender($faker->randomElement(['homme', 'femme', 'autre']));

            $student->setDateOfBirth($faker->dateTimeBetween('-30 years', '-20 years'));

            $student->setPhone($faker->phoneNumber);
            $student->setAddress($faker->streetAddress);
            $student->setAddressComplement($faker->optional()->secondaryAddress);
            $student->setCity($faker->city);
            $student->setPostalCode($faker->postcode);

            $student->setPortfolioUrl($faker->optional()->url);

            $student->setIsDriver($faker->boolean(50));

            $student->setIsHandicap($faker->boolean(10));

            $manager->persist($student);
        }

        $manager->flush();
    }

    public function getOrder(): int
    {
        return 2; 
    }
}
