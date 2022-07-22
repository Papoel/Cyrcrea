<?php

namespace App\DataFixtures;

use App\Entity\Review;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class ReviewFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 1; $i <= 200; $i++) {
            $review = new Review();
            $review->setUser($this->getReference(
                sprintf(
                    'user%d',
                    $faker->numberBetween(1, 3)
                ),
                $review
            ));
            $review->setProduct($this->getReference(sprintf('product%d', $faker->numberBetween(1, 1500))));

            $review->setNote($faker->numberBetween(0, 5));
            $review->setComment($faker->word($faker->numberBetween(10, 200)));

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
