<?php

namespace App\DataFixtures;

use App\Entity\Skill;
use App\Entity\Student;
use App\Entity\Offer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class SkillFixtures extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $skills = [];
        for ($i = 0; $i < 20; $i++) {
            $skill = new Skill();
            $skill->setName($faker->word);
            $manager->persist($skill);
            $skills[] = $skill;
        }

        $manager->flush();

        $students = $manager->getRepository(Student::class)->findAll();
        foreach ($students as $student) {
            $student->getSkills()->add(...$faker->randomElements($skills, mt_rand(2, 5)));
        }

        $offers = $manager->getRepository(Offer::class)->findAll();
        foreach ($offers as $offer) {
            $offer->getSkills()->add(...$faker->randomElements($skills, mt_rand(3, 7)));
        }

        $manager->flush();
    }

    public function getOrder(): int
    {
        return 7; 
    }

}
