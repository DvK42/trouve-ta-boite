<?php

namespace App\DataFixtures;

use App\Entity\Offer;
use App\Entity\Company;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class OfferFixtures extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $companies = $manager->getRepository(Company::class)->findAll();

        for ($i = 0; $i < 50; $i++) {
            $offer = new Offer();
            
            $type = $faker->randomElement(['stage', 'alternance']);
            $offer->setType($type);

            $createdAt = $faker->dateTimeBetween('-1 month', 'now');
            $maxApplyDate = (clone $createdAt)->modify('+2 months');

            $offer->setCreatedAt($createdAt);
            $offer->setMaxApplyDate($maxApplyDate);

            $startDate = $faker->dateTimeBetween('-1 month', '+1 month');
            $endDate = $faker->dateTimeBetween($startDate, '+8 months');
            $offer->setStartDate($startDate);
            $offer->setEndDate($endDate);

            $interval = $startDate->diff($endDate);
            $totalDays = $interval->days; 

            if ($type === 'stage' && $totalDays < 44) {
            $offer->setSalary(null); 
            } else {
            $offer->setSalary($faker->randomFloat(2, 500, 2000));
            }

            $offer->setName($faker->jobTitle);
            $offer->setDescription($faker->paragraph);
            
            if (!empty($companies)) {
                $company = $faker->randomElement($companies);
                $offer->setCompany($company);
            }

            $manager->persist($offer);
        }

        $manager->flush();
    }

    public function getOrder(): int
    {
        return 4;
    }
}

