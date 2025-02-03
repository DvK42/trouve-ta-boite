<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Offer;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class CategoryFixtures extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // Créer des catégories
        $categories = [];
        foreach (Category::CATEGORIES as $categoryName) {
            $category = new Category();
            $category->setName($categoryName);
            $category->setColor($faker->hexColor);

            $manager->persist($category);
            $categories[] = $category;
        }

        // Associer des catégories aux offres
        $offers = $manager->getRepository(Offer::class)->findAll();

        foreach ($offers as $offer) {
            $randomCategories = $faker->randomElements($categories, mt_rand(1, 3)); // Associe 1 à 3 catégories
            foreach ($randomCategories as $category) {
                $offer->addCategory($category); // Associe la catégorie à l'offre
            }

            $manager->persist($offer); // Persist l'offre avec ses catégories
        }

        $manager->flush(); // Sauvegarde tout dans la base
    }

    public function getOrder(): int
    {
        return 6;
    }
}

