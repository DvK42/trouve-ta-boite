<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Offer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $categories = [];
        foreach (Category::CATEGORIES as $categoryName) {
            $category = new Category();
            $category->setName($categoryName);

            $manager->persist($category);
            $categories[] = $category;
        }

        $offers = $manager->getRepository(Offer::class)->findAll();

        foreach ($offers as $offer) {
            $randomCategories = $faker->randomElements($categories, mt_rand(1, 3)); // 1 à 3 catégories aléatoires
            foreach ($randomCategories as $category) {
                $offer->addCategory($category);
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