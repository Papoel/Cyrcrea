<?php

namespace App\DataFixtures;

use App\Entity\ReviewsProduct;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class ReviewsProductFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 1; $i <= 200; $i++) {
            $review = new ReviewsProduct();

            // Corrected call to getReference
            $userReferenceName = sprintf('user%d', $faker->numberBetween(1, 5));
            $user = $this->getReference($userReferenceName);

            $productReferenceName = sprintf('product%d', $faker->numberBetween(1, 15));
            $product = $this->getReference($productReferenceName);

            $review->setUser($user);
            $review->setProduct($product);

            $review->setNote($faker->numberBetween(0, 5));
            $review->setComment($faker->sentence($faker->numberBetween(10, 200)));

            $manager->persist($review);
            $this->addReference(sprintf('review%d', $i), $review);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['review'];
    }

    public function getDependencies(): array
    {
        return [
            UsersFixtures::class,
            ProductFixtures::class
        ];
    }
}
