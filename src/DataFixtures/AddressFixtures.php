<?php

namespace App\DataFixtures;

use App\Entity\Address;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class AddressFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        $addresses = [];
        $address = new Address();

        $address->setName('Aude & Papoel');
        $address->setCompany('ZELIS LABRI');
        $address->setAddress('15 rue de la Liberté');
        $address->setCity('Maubeuge');
        $address->setPostal('59600');
        $address->setCountry('France');
        $address->setPhone('0669399414');

        $manager->persist($address);
        $this->addReference('super-admin', $address);
        $addresses[] = $address;

        $addresses = [];
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
        $addresses[] = $address;

        for ($i = 1; $i <= 20; ++$i) {
            $addresses = [];
            $address = new Address();

            $address->setName($faker->word(1));
            $address->setCompany($faker->company());
            $address->setAddress($faker->address());
            $address->setComplement(sprintf('Apt. %d', $i));
            $address->setCity($faker->city());
            $address->setPostal($faker->postcode());
            $address->setCountry('France');
            $address->setPhone($faker->phoneNumber());

            $manager->persist($address);
            $this->addReference(sprintf('address%d', $i), $address);
            $addresses[] = $address;
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['address'];
    }
}
