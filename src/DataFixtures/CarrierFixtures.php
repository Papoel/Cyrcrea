<?php

namespace App\DataFixtures;

use App\Entity\Carrier;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class CarrierFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 1; $i <= 10; $i++) {
            $carriers = [];
            $carrier = new Carrier();

            $carrier->setName($faker->company());
            $carrier->setDescription($faker->text($faker->numberBetween(50, 250)));
            $carrier->setPrice($faker->randomFloat(2, 0.99, 49.99));
            $carrier->setCarriercol($faker->word(2));
            $carrier->setCreatedAt(new \DateTimeImmutable());

            $manager->persist($carrier);
            $carriers[] = $carrier;
        }

        $manager->flush();
    }
}
