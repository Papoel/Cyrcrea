<?php

namespace App\DataFixtures;

use App\Entity\OrderDetails;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class OrderDetailsFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 1; $i <= 250; $i++) {
            $orderDetail = new OrderDetails();
            $orderDetail->setProductName($faker->word(1));
            $orderDetail->setProductPrice($faker->randomFloat(2, 1.50, 49.90));
            $orderDetail->setQuantity($faker->numberBetween(1, 35));
            $orderDetail->setSubTotalHt($faker->randomFloat(1.49, 49.90));
            $orderDetail->setTaxe(20.00);
            $orderDetail->setSubTotalTtc($orderDetail->getSubTotalHt() * $orderDetail->getTaxe());

            $orderDetail->setOrder($this->getReference(sprintf('order%d', $faker->numberBetween(1, 250))));
            $manager->persist($orderDetail);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            OrderFixtures::class
        ];
    }
}
