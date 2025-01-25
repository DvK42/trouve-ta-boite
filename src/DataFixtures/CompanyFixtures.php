<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Company;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpClient\HttpClient;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
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
        $filesystem = new Filesystem();
        $httpClient = HttpClient::create();

        $targetDirectory = 'assets/images/faker';

        if (!$filesystem->exists($targetDirectory)) {
            $filesystem->mkdir($targetDirectory);
        }
        
        for ($i = 0; $i < 20; $i++) {
            $company = new Company();
            $company->setEmail($faker->unique()->companyEmail);
            $company->setRoles(['ROLE_COMPANY']);
            $company->setPassword(
                $this->passwordHasher->hashPassword($company, 'password123')
            );
            $company->setName($faker->company);
            $company->setLocation($faker->city);
            $company->setSector($faker->catchPhrase);
            $company->setDescription($faker->paragraph);
            $imageUrl = 'https://loremflickr.com/200/200/business';
            $imageFilename = sprintf('%s/logo_%s.jpg', $targetDirectory, uniqid());

            $response = $httpClient->request('GET', $imageUrl);
            if ($response->getStatusCode() === 200) {
                $filesystem->dumpFile($imageFilename, $response->getContent());
            }

            $company->setLogo(basename($imageFilename)); 

            $manager->persist($company);
        }

        $manager->flush();
    }

    public function getOrder(): int
    {
        return 3;
    }
}
