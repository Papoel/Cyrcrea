<?php

namespace App\DataFixtures;

use App\Entity\Products;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        $images = ['bijou', 'bonnet', 'drone', 'gadget', 'montre', 'portable'];

        for ($i = 1; $i <= 1500; $i++) {
            $product = new Products();

            $product->setName($faker->word($faker->numberBetween(1, 5)));
            $product->setSlug($faker->slug(3));
            $product->setDescription($faker->text($faker->numberBetween(30, 400)));
            $product->setMoreInformations($faker->word($faker->numberBetween(10, 95)));
            $product->setPrice($faker->randomFloat());
            $product->setIsBest($faker->boolean());
            $product->setIsNewArrival($faker->boolean());
            $product->setIsFeatured($faker->boolean());
            $product->setIsSpecialOffer($faker->boolean());

            $date = $faker->dateTimeBetween('-11 months', '+ 3 days');
            $immutable = \DateTimeImmutable::createFromMutable( $date );
            $product->setCreatedAt($immutable);

            $product->setStock($faker->numberBetween(15, 850));
            // $product->setTags($faker->word($faker->numberBetween(1, 10)));

            $product->addCategory($this->getReference(sprintf('category%d', $faker->numberBetween(1, 6))));
            //$product->addReview($this->getReference(sprintf('review%d', $faker->numberBetween(1, 200))));

            $product->setImage(
                $images[$faker->numberBetween(0, 5)]
                . '_' .
                $faker->numberBetween(1, 4)
                . '.png'
            );

            $manager->persist($product);
            $this->addReference(sprintf('product%d', $i), $product);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['product'];
    }

    public function getDependencies(): array
    {
        return [
            CategoriesFixtures::class
        ];
    }
}
