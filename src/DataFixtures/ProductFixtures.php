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

        for ($i = 1; $i <= 15; $i++) {
            $product = new Products();

            $product->setName($faker->word());
            $product->setSlug($faker->slug(3));
            $product->setDescription($faker->text($faker->numberBetween(30, 400)));
            $product->setMoreInformations($faker->sentence($faker->numberBetween(10, 95)));
            $product->setPrice($faker->numberBetween(200, 15000));
            $product->setIsBest($faker->boolean());
            $product->setIsNewArrival($faker->boolean());
            $product->setIsFeatured($faker->boolean());
            $product->setIsSpecialOffer($faker->boolean());

            $date = $faker->dateTimeBetween('-11 months', '+3 days');
            $immutable = \DateTimeImmutable::createFromMutable($date);
            $product->setCreatedAt($immutable);

            $list = [
                'tag#1', 'tag#2', 'tag#3', 'tag#4', 'tag#5',
                'tag#6', 'tag#7', 'tag#8', 'tag#9', 'tag#10'
            ];
            $nbTag = $faker->numberBetween(2, 9);
            $tags = implode(', ', $faker->randomElements($list, $nbTag));

            $product->setTags($tags);
            $product->setStock($faker->numberBetween(15, 850));

            $categoryReference = sprintf('category%d', $faker->numberBetween(1, 6));
            if ($this->hasReference($categoryReference)) {
                $product->addCategory($this->getReference($categoryReference));
            }

            $manager->persist($product);
            $this->addReference('product' . $i, $product);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['product'];
    }

    public function getDependencies(): array
    {
        return [CategoriesFixtures::class];
    }
}
