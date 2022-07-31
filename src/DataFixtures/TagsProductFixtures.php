<?php

namespace App\DataFixtures;

use App\Entity\TagsProduct;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TagsProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $tags = [
            'homme',
            'femme',
            'maison',
            'santé',
            'sport',
            'voyage',
            'beauté'
        ];

        foreach ($tags as $tag) {
            $tagEntity = new TagsProduct();
            $tagEntity->setName($tag);
            $manager->persist($tagEntity);
        }

        $manager->flush();
    }
}
