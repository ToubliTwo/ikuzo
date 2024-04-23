<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CampusFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $campusNoms = ['Rennes', 'Nantes', 'Niort'];

        foreach ($campusNoms as $index => $campusNom) {
            $campus = new Campus();
            $campus->setNom($campusNom);
            $manager->persist($campus);

            $this->addReference('campus_' . ($index + 1), $campus);
        }
        $manager->flush();
    }
}
