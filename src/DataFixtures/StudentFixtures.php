<?php

namespace App\DataFixtures;

use App\Entity\Student;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
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
            $student->setEducation($faker->randomElement(['Computer Science', 'Engineering', 'Business Management', 'Mathematics']));
            
            $manager->persist($student);
        }

        $manager->flush();
    }
    
    public function getOrder(): int
    {
        return 2; 
    }
}
