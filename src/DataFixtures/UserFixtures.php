<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    const NB_USERS = 10;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    public function load(ObjectManager $manager): void
    {

        $faker = \Faker\Factory::create(locale: 'fr_FR');

        for ($i = 0; $i < self::NB_USERS; $i++) {
            $user = new User();
            $user->setNom($faker->lastName);
            $user->setPrenom($faker->firstName);
            $user->setPseudo($faker->userName);
            $user->setTelephone($faker->phoneNumber);
            $user->setEmail($faker->email);
            $user->setPassword($this->passwordHasher->hashPassword(
                $user,
                plainPassword: 'password'
            ));
            $user->setAdministrateur($faker->boolean);
            if ($user->isAdministrateur()) {
                $user->setRoles(["ROLE_ADMIN"]);
            } else
            $user->setRoles(["ROLE_USER"]);
            $user->setActif($faker->boolean);
            $user->setCampus($this->getReference(name: "campus_" . $faker->numberBetween(int1: 1, int2: 3)));
            $this->addReference(name: "user_" . ($i + 1), object: $user);
            $manager->persist($user);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CampusFixtures::class
        ];
    }
}
