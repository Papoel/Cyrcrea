<?php

namespace App\DataFixtures;

use App\Entity\Address;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AddressFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $address = new Address();

        $address->setName('Maison');
        $address->setCompany('Cyr-Créa');
        $address->setAddress('1 rue de la paix');
        $address->setComplement('Apt. 1');
        $address->setCity('Brie Comte Robert');
        $address->setPostal('77170');
        $address->setCountry('France');
        $address->setPhone('0762665642');

        $manager->persist($address);
        $this->addReference('admin-address', $address);

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['address'];
    }
}
