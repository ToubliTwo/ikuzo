<?php

namespace App\DataFixtures;

use App\Entity\Lieu;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LieuFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 5; $i++) {
            $lieu = new Lieu();
            $lieu ->setNom($faker->company); //génération d'un nom de compagnie aléatoirement
            $lieu ->setRue($faker->streetName);
            $lieu ->setLatitude($faker->latitude);
            $lieu ->setLongitude($faker->longitude);
            $lieu ->setVille($this->getReference("ville_" . $faker->numberBetween(1, 10)));

            $manager->persist($lieu);
        }
        $manager->flush();
    }
    public function getDependencies(): array
    {
        return [
           VilleFixtures::class
        ];
    }
}
