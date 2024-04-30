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
        $faker = \Faker\Factory::create(locale: 'fr_FR');
        for ($i = 0; $i < 100; $i++) {
            $sortie = new Sorties();
            $sortie->setTitre($faker->jobTitle);
            $sortie->setDateLimiteInscription($faker->dateTimeBetween(startDate: '-30 days', endDate: '+90 days'));
            $dateInscription = $sortie->getDateLimiteInscription();
            $sortie->setDate($faker->dateTimeBetween($dateInscription, endDate: '+90 days'));
            $sortie->setNombreDePlaces($faker->numberBetween(int1: 1, int2: 20));
            $nbPlaces = $sortie->getNombreDePlaces();
            $sortie->setDuree($faker->dateTime);
            $sortie->setDescription($faker->text);
            $sortie->setEtat($this->getReference(name: 'etat_1'));
            $sortie->setCampus($this->getReference(name: 'campus_'.$faker->numberBetween(int1: 1, int2: count(value: CampusFixtures::CAMPUS_NOM))));
            $sortie->setLieu($this->getReference(name: 'lieu_'.$faker->numberBetween(int1: 1, int2: LieuFixtures::NB_LIEUX)));
            $sortie->setOrganisateur($this->getReference(name: 'user_'.$faker->numberBetween(int1: 1, int2: UserFixtures::NB_USERS)));
            //ajouter des inscrits à la sortie (entre 0 et nbPlaces)
            for ($j = 0; $j < $nbPlaces; $j++) {
                $sortie->addUser($this->getReference(name: 'user_'.$faker->numberBetween(int1: 1, int2: userFixtures::NB_USERS)));
            }
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
