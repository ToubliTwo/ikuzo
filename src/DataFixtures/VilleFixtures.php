<?php

namespace App\DataFixtures;

use App\Entity\Ville;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class VilleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        //on initialise et on détermine la locale du faker (pour avoir des noms en français)
        $faker = \Faker\Factory::create(locale: 'fr_FR');

        //on détermine ici le nombre de villes et leurs attributs à renseigner avec le faker
        for ($curseur=0; $curseur < 10; $curseur++) {
            //création d'un nouvel objet
            $ville = new Ville();
            //on met du fake dans le nom et dans le code postal
            $ville->setNom($faker->city);
            $ville->setCodePostal($faker->postcode());

          /*
            ⬇ la méthode addReference() ⬇ permet de faire "un lien/une relation" avec LieuFixtures
            (en effet, côté LieuFixtures, on a un getReference qui permet de retrouver la ville associée)
            on passe en paramètre de la méthode le curseur (qui se déplace dans la boucle for)
            pour déclarer un id, et la ville correspondante (ville_id deviendra ville_38, GrosNoble - faker oblige ^^ cf. ligne21)
          */
            $this->addReference(name: "ville_" . ($curseur+1), object: $ville);
            $manager->persist($ville); //on permet l'enregistrement en base de données
        }
        $manager->flush(); //on envoie le tout vers la base de données
    }
}