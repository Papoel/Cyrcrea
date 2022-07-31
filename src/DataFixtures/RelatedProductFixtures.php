<?php

namespace App\DataFixtures;

use App\Entity\RelatedProduct;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class RelatedProductFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 10; $i++) {
            $relatedProduct = new RelatedProduct();
            $relatedProduct->setProduct($this->getReference(sprintf('product%d', $i)));
            $manager->persist($relatedProduct);
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
