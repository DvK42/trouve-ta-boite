<?php

namespace App\DataFixtures;

use App\Entity\Company;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CompanyFixtures extends Fixture implements OrderedFixtureInterface
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 20; $i++) {
            $company = new Company();
            $company->setEmail($faker->unique()->companyEmail);
            $company->setRoles(['ROLE_COMPANY']);
            $company->setPassword(
                $this->passwordHasher->hashPassword($company, 'password123')
            );
            $company->setName($faker->company);
            $company->setSector($faker->catchPhrase);
            $company->setDescription($faker->paragraph);

            $manager->persist($company);
        }

        $manager->flush();
    }

    public function getOrder(): int
    {
        return 3;
    }
}
