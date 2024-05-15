<?php

namespace App\DataFixtures;

use App\Entity\Images;
use App\Entity\Products;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class ImagesFixtures extends Fixture implements DependentFixtureInterface
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        for ($img = 1; $img <= 100; $img++) {
            $image = new Images();

            // Use a random string for the image name
            $imageName = $this->faker->lexify('image_??????.jpg');
            $image->setName($imageName);

            // Reference a random product
            $productReference = sprintf('product%d', $this->faker->numberBetween(1, 15));
            $product = $this->getReference($productReference);

            /** @var Products $product */
            $image->setProducts($product);

            $manager->persist($image);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ProductFixtures::class,
        ];
    }
}
