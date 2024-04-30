<?php

namespace App\DataFixtures;

use App\Entity\Lieu;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LieuFixtures extends Fixture implements DependentFixtureInterface
{
    const NB_LIEUX = 5;

    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create(locale: 'fr_FR');

        for ($i = 0; $i < self::NB_LIEUX; $i++) {
            $lieu = new Lieu();
            $lieu ->setNom($faker->company); //génération d'un nom de compagnie aléatoirement
            $lieu ->setRue($faker->streetName);
            $lieu ->setLatitude($faker->latitude);
            $lieu ->setLongitude($faker->longitude);
            $lieu ->setVille($this->getReference(name: "ville_" . $faker->numberBetween(int1: 1, int2: 10)));

            $manager->persist($lieu);
            $this->addReference(name: "lieu_" . ($i+1), object: $lieu);
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
