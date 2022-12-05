<?php

namespace App\DataFixtures;
use App\Entity\Product;
use App\Entity\Category;
use App\DataFixtures\CategoryFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;


class ProductFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 20; $i++) {
            $product = new Product();
            $product->setName($faker->word());
            $product->setCategory($this->getReference("category" . $i));
            $product->setQuantity($faker->randomDigit());
            $product->setPrice($faker->randomDigit());
            $product->setDescription("jjjejeje");
            $manager->persist($product);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CategoryFixtures::class,
        ];
    }

}
