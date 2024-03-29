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

            $product->setName($faker->word($faker->numberBetween(1, 5)));
            $product->setSlug($faker->slug(3));
            $product->setDescription($faker->text($faker->numberBetween(30, 400)));
            $product->setMoreInformations($faker->word($faker->numberBetween(10, 95)));
            $product->setPrice($faker->numberBetween(200, 15000));
            $product->setIsBest($faker->boolean());
            $product->setIsNewArrival($faker->boolean());
            $product->setIsFeatured($faker->boolean());
            $product->setIsSpecialOffer($faker->boolean());

            $date = $faker->dateTimeBetween('-11 months', '+ 3 days');
            $immutable = \DateTimeImmutable::createFromMutable( $date );
            $product->setCreatedAt($immutable);

            $list = [
                0 => 'tag#1',
                1 => 'tag#2',
                2 => 'tag3#',
                3 => 'tag#4',
                4 => 'tag#5',
                5 => 'tag#6',
                6 => 'tag#7',
                7 => 'tag#8',
                8 => 'tag#9',
                9 => 'tag#10'
            ];
            $nbTag = $faker->numberBetween(2, 9);
            $tag = array_rand($list, $nbTag);

            $tags = '';
            foreach ($tag as $key => $value) {
                $tags .= $list[$value] . ', ';
            }
            $product->setTags(substr($tags, 0, -2));

            $product->setStock($faker->numberBetween(15, 850));

            $product->addCategory($this->getReference(sprintf('category%d', $faker->numberBetween(1, 6))));
            //$product->addReview($this->getReference(sprintf('review%d', $faker->numberBetween(1, 200))));

            // $product->addImage($this->getReference(sprintf('image%d', $faker->numberBetween(1, 6))));

            $this->setReference('product' .$i, $product);
            $manager->persist($product);
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
