<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CampusFixtures extends Fixture
{

    public const CAMPUS_NOM = ['Rennes', 'Nantes', 'Niort'];

    public function load(ObjectManager $manager): void
    {


        foreach (self::CAMPUS_NOM as $index => $campusNom) {
            $campus = new Campus();
            $campus->setNom($campusNom);
            $manager->persist($campus);

            $this->addReference(name: 'campus_' . ($index + 1), object: $campus);
        }
        $manager->flush();
    }
}
