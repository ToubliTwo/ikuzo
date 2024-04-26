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
        for ($i = 0; $i < 100; $i++) {
            $sortie = new Sorties();
            $sortie->setTitre($faker->jobTitle);
            $sortie->setDate($faker->dateTimeBetween('-30 days', '+90 days'));
            $sortie->setDateLimiteInscription($faker->dateTimeBetween('-30 days', '+50 days'));
            $sortie->setNombreDePlaces($faker->numberBetween(1, 10));
            $sortie->setDuree($faker->dateTime);
            $sortie->setDescription($faker->text);
            $sortie->setEtat($this->getReference('etat_'.$faker->numberBetween(1, 7)));
            $sortie->setCampus($this->getReference('campus_'.$faker->numberBetween(1, 3)));
            $sortie->setLieu($this->getReference('lieu_'.$faker->numberBetween(1, 5)));
            $sortie->setOrganisateur($this->getReference('user_'.$faker->numberBetween(1, 10)));
//ajouter des inscrits à la sortie (entre 0 et le nombre de places)
/*            for ($j = 0; $j < $faker->numberBetween(0, $sortie->getNombreDePlaces()); $j++) {
                $sortie->addInscrit($this->getReference('user_'.$faker->numberBetween(1, 10)));
            }*/ //TODO à faire
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
