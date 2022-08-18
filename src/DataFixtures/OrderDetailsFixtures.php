<?php

namespace App\DataFixtures;

use App\Entity\OrdersDetails;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class OrderDetailsFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($order_details = 1; $order_details <= 20; $order_details++) {

            $detail = new OrdersDetails();

            $detail->setOrders(
                $this->getReference(sprintf('commande%d', $faker->numberBetween(1, 250)
                ))
            );

            $detail->setProducts(
                $this->getReference(sprintf('product%d', $faker->numberBetween(1, 15)
                ))
            );

            $product_qty = $faker->numberBetween(1, 5);
            $product_price = $detail->getProducts()->getPrice();

            $detail->setQuantity($product_qty);
            $detail->setPriceHt($product_price * $detail->getQuantity());
            $detail->setPriceTtc($detail->getPriceHt() + ($detail->getPriceHt() * 0.2));
            $manager->persist($detail);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ProductFixtures::class,
            OrderFixtures::class
        ];
    }
}
