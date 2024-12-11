<?php

namespace App\DataFixtures;

use App\Entity\Application;
use App\Entity\Message;
use App\Entity\Offer;
use App\Entity\Student;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ApplicationFixtures extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        
        $students = $manager->getRepository(Student::class)->findAll();
        $offers = $manager->getRepository(Offer::class)->findAll();

        $appliedOffers = [];

        for ($i = 0; $i < 50; $i++) {
            if (empty($students) || empty($offers)) {
                break;
            }

            $student = $faker->randomElement($students);
            $offer = $faker->randomElement($offers);

            $key = $student->getId() . '-' . $offer->getId();
            if (isset($appliedOffers[$key])) {
                continue;
            }

            $appliedOffers[$key] = true;

            $application = new Application();
            $application->setStudentId($student);
            $application->setOfferId($offer);
            $application->setCoverLetter($faker->paragraph);
            $application->setDate($faker->dateTimeThisYear);

            $manager->persist($application);

            $message = new Message();
            $message->setSenderId($student); // Étudiant comme expéditeur
            $message->setRecipientId($offer->getCompany()); // Entreprise comme destinataire
            $message->setApplicationId($application); // Associer l'application
            $message->setContent($application->getCoverLetter()); // Utiliser la lettre de motivation comme contenu
            $message->setDate(new \DateTime()); // Date actuelle

            $manager->persist($message);
        }

        $manager->flush();
    }

    public function getOrder(): int
    {
        return 5;
    }

}

