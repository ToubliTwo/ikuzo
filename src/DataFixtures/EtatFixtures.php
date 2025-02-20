<?php

namespace App\DataFixtures;

use App\Entity\Etat;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EtatFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $etatNoms = ['Créée', 'Ouverte', 'Clôturée', 'Activité en cours', 'Passée', 'Annulée', 'Archivée'];

        foreach ($etatNoms as $index => $etatNom) {
            $etat = new Etat();
            $etat->setLibelle($etatNom);
            $manager->persist($etat);

            $this->addReference(name: 'etat_' . ($index + 1), object : $etat);
        }
        $manager->flush();
    }
}
