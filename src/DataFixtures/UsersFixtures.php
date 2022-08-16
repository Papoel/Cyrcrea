<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker;

class UsersFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');
        $users = [];

        $userAdmin = new User();

        $userAdmin->setFirstname('Pascal');
        $userAdmin->setLastname('Briffard');
        $userAdmin->setEmail('superadmin@cyrcrea.fr');
        $userAdmin->setRoles(['ROLE_ADMIN']);
        $userAdmin->setPassword($this->passwordHasher->hashPassword($userAdmin, ('password')));
        $userAdmin->setCreatedAt(new \DateTimeImmutable());
        $userAdmin->setIsVerified(true);

        $date = $faker->dateTimeBetween('-1 years', 'now');
        $immutable = \DateTimeImmutable::createFromMutable( $date );
        $userAdmin->setCreatedAt($immutable);

        $userAdmin->addAddress($this->getReference('super-admin'));

        $manager->persist($userAdmin);
        $this->addReference('user1', $userAdmin);
        $users[] = $userAdmin;

        $users = [];
        $userAdmin2 = new User();

        $userAdmin2->setFirstname('Cyril');
        $userAdmin2->setLastname('Lamy');
        $userAdmin2->setEmail('admin@cyrcrea.fr');
        $userAdmin2->setRoles(['ROLE_ADMIN']);
        $userAdmin2->setPassword($this->passwordHasher->hashPassword($userAdmin2, ('password')));
        $userAdmin2->setCreatedAt(new \DateTimeImmutable());
        $userAdmin2->setIsVerified(true);

        $date = $faker->dateTimeBetween('-1 years', 'now');
        $immutable = \DateTimeImmutable::createFromMutable( $date );
        $userAdmin2->setCreatedAt($immutable);

        $userAdmin2->addAddress($this->getReference('admin-adresse'));

        $manager->persist($userAdmin2);
        $this->addReference('user2', $userAdmin2);
        $users[] = $userAdmin2;

        for ($i = 1; $i <= 3; ++$i) {
            $users = [];
            $user = new User();

            $user->setFirstname($faker->firstname());
            $user->setLastname($faker->lastname());
            $user->setEmail(sprintf('user%d@email.fr', $i));
            $user->setRoles(['ROLE_USER']);
            $user->setPassword($this->passwordHasher->hashPassword($user, 'password'));
            $user->setIsVerified($faker->boolean());

            $date = $faker->dateTimeBetween('-10 months', '+ 30 days');
            $immutable = \DateTimeImmutable::createFromMutable( $date );
            $user->setCreatedAt($immutable);

            $manager->persist($user);
            $this->addReference(sprintf('user%d', $i + 2), $user);
            $users[] = $user;
        }


        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['user'];
    }

    public function getDependencies(): array
    {
        return [AddressFixtures::class];
    }
}
