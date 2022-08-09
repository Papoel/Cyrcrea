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
            $orderDetail->setOrder($this->getReference(sprintf('commande%d', $i)));
            $orderDetail->setProducts($this->getReference(sprintf('product%d', $faker->numberBetween(1, 1500))));
            $orderDetail->setProductName($faker->word(1));
            $orderDetail->setProductPrice($faker->numberBetween(100, 99990,));
            $orderDetail->setQuantity($faker->numberBetween(1, 35));
            $orderDetail->setSubTotalHt($faker->numberBetween(149, 99990));
            $orderDetail->setTaxe(20.00);
            $orderDetail->setSubTotalTtc($orderDetail->getSubTotalHt() * $orderDetail->getTaxe());

            // $orderDetail->setOrder($this->getReference(sprintf('commande%d', $faker->numberBetween(1, 250))));
            $manager->persist($orderDetail);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            OrderFixtures::class,
            ProductFixtures::class
        ];
    }
}
