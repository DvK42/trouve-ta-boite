<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Sector;
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

        $sectors = $manager->getRepository(Sector::class)->findAll();

        $targetDirectory = 'assets/images/uploads/company';

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
            $company->setAddress($faker->address);
            $company->setAddressComplement($faker->secondaryAddress);
            $company->setPostalCode($faker->postcode);
            $company->setLocation($faker->city);
            $company->setPhone($faker->phoneNumber);
            $company->setContactEmail($faker->companyEmail);
            $company->setEmployeeCount($faker->numberBetween(10, 500));
            $company->setYearFounded($faker->year);
            $company->setCatchPhrase($faker->catchPhrase);
            $company->setDescription($faker->paragraph(1));
            $imageUrl = 'https://loremflickr.com/200/200/business';
            $imageFilename = sprintf('%s/logo_%s.jpg', $targetDirectory, uniqid());

            if (!empty($sectors)) {
                $randomSector = $faker->randomElement($sectors);
                $company->setSector($randomSector);
            }

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
        return 4;
    }
}
