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
                $this->getReference(sprintf('product%d', $faker->numberBetween(1, 1500)
                ))
            );

            $product_qty = $faker->numberBetween(1, 5);
            $product_price = $detail->getProducts()->getPrice();

            //dd($product_qty, $product_price, 'RESULTAT :', $product_qty * $product_price);
            $detail->setQuantity($product_qty);
            $detail->setPrice($product_price * $detail->getQuantity());

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
