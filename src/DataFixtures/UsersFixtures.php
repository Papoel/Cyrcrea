<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UsersFixtures extends Fixture implements DependentFixtureInterface
{

    public function __construct(private UserPasswordHasherInterface $passwordHasher) {}

    public function load(ObjectManager $manager): void
    {
        $userAdmin = new User();

        $userAdmin->setFirstname('Cyril');
        $userAdmin->setLastname('Lamy');
        $userAdmin->setEmail('admin@cyrcrea.fr');
        $userAdmin->setRoles(['ROLE_ADMIN']);
        $hash = $this->passwordHasher->hashPassword($userAdmin, ('password'));
        $userAdmin->setPassword($hash);
        $userAdmin->setCreatedAt(new \DateTimeImmutable());
        $userAdmin->setIsVerify(true);

        $userAdmin->addAddress($this->getReference('admin-address'));

        $manager->persist($userAdmin);
        $manager->flush();

    }

    public function getDependencies()
    {
        return [
            AddressFixtures::class,
        ];
    }
}
