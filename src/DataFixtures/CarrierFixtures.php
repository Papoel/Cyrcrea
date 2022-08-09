<?php

namespace App\DataFixtures;

use App\Entity\Carriers;
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
            $carrier = new Carriers();

            $carrier->setName($faker->company());
            $carrier->setDescription($faker->text($faker->numberBetween(50, 250)));
            $carrier->setPrice($faker->randomFloat(2, 0, 9.90));
            $carrier->setCarriercol($faker->word(2));
            $carrier->setCreatedAt(new \DateTimeImmutable());

            $manager->persist($carrier);
            $this->addReference(sprintf('carrier%d', $i), $carrier);

            $carriers[] = $carrier;
        }

        $manager->flush();
    }
    public static function getGroups(): array
    {
        return ['carrier'];
    }
}
