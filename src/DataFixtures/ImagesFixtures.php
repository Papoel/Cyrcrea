<?php

namespace App\DataFixtures;

use App\Entity\Images;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Random\RandomException;

class ImagesFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($img = 1; $img <= 100; $img++) {
            $image = new Images();
            $image->setName($faker->image( null, 640, 380));
            try {
                $product = $this->getReference('product' . random_int(1, 15));
            } catch (RandomException $e) {
                dump('Erreur Fixtures Images: ' .$e);
            }
            $image->setProducts($product);

            //dump($image);

            $manager->persist($image);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ProductFixtures::class
        ];
    }
}
