<?php

namespace App\DataFixtures;

use App\Entity\Order;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class OrderFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        $orders = [];

        for ($i = 1; $i <= 250; $i++) {
            $order = new Order();
            $order->setReference($faker->text($faker->numberBetween(30, 250)));
            $order->setFullName($faker->firstName . ' ' . $faker->lastName);
            $order->setCarrierName($faker->company());
            $order->setCarrierPrice($faker->randomFloat(2, 0, 9.90));
            $order->setDeliveryAddress(
                $faker->address()
                . ' ' .
                $faker->postcode()
                . ' ' .
                $faker->city()
            );
            $order->setIsPaid($faker->boolean);
            $order->setMoreInformations($faker->text($faker->numberBetween(50, 500)));
            $order->setCreatedAt(new \DateTimeImmutable());

            $order->setUser($this->getReference(sprintf('user%s', $faker->numberBetween(1, 3))));

            $manager->persist($order);
            $this->addReference(sprintf('order%d', $i), $order);
        }
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['order'];
    }

    public function getDependencies(): array
    {
        return [
            UsersFixtures::class,
            CarrierFixtures::class
        ];
    }
}
