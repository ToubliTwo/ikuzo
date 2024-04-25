<?php

namespace App\DataFixtures;

use App\Entity\Sorties;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SortiesFixtures extends Fixture implements  DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');
        for ($i = 0; $i < 10; $i++) {
            $sortie = new Sorties();
            $sortie->setTitre($faker->jobTitle);
            $sortie->setDate($faker->dateTimeBetween('-30 days', '+30 days'));
            $sortie->setDateLimiteInscription($faker->dateTimeBetween('-30 days', '-1 days'));
            $sortie->setNombreDePlaces($faker->numberBetween(1, 10));
            $sortie->setDuree($faker->dateTime);
            $sortie->setDescription($faker->text);
            $sortie->setEtat($this->getReference('etat_'.$faker->numberBetween(1, 6)));
            $sortie->setCampus($this->getReference('campus_'.$faker->numberBetween(1, 3)));
            $sortie->setLieu($this->getReference('lieu_'.$faker->numberBetween(1, 5)));
            $sortie->setOrganisateur($this->getReference('user_'.$faker->numberBetween(1, 10)));

            $manager->persist($sortie);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CampusFixtures::class,
            EtatFixtures::class,
            LieuFixtures::class,
            VilleFixtures::class,
            UserFixtures::class
        ];
    }
}
