<?php

namespace App\DataFixtures;

use App\Entity\Lieu;
use App\Entity\Ville;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class LieuFixtures extends Fixture
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
    public function getDependencies()
    {
        return [
           VilleFixtures::class
        ];
    }
}
