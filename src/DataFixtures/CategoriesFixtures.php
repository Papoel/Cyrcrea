<?php

namespace App\DataFixtures;

use App\Entity\Categories;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoriesFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $categories = [
            'bijou',
            'bonnet',
            'drone',
            'gadget',
            'montre',
            'phone'
        ];

        for ($i = 1, $iMax = count($categories); $i <= $iMax; $i++) {
            $category = new Categories();
            $category->setName($categories[$i - 1]);
            $category->setImage(
                ('./public/images/'
                . $categories[$i - 1] .
                '/' .
                $categories[$i - 1] .
                '_1.png'
                )
            );


            $manager->persist($category);
        }
        $manager->flush();
    }
}
