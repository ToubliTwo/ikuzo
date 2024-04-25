<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setNom($faker->lastName);
            $user->setPrenom($faker->firstName);
            $user->setPseudo($faker->userName);
            $user->setTelephone($faker->phoneNumber);
            $user->setEmail($faker->email);
            $user->setPassword($this->passwordHasher->hashPassword(
                $user,
                'password'
            ));
            $user->setAdministrateur($faker->boolean);
            $user->setActif($faker->boolean);
            $user->setCampus($this->getReference("campus_" . $faker->numberBetween(1, 3)));
            $this->addReference("user_" . ($i + 1), $user);
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
