<?php

namespace App\Services;

use App\Entity\Etat;
use App\Entity\Sorties;
use Doctrine\ORM\EntityManagerInterface;

class ChangementEtat
{
    public function __construct (EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    public function modifierEtat(Sorties $sorties):void
    {

        //obtenir la date actuelle :
        $today = new \DateTime();

        //obtenir la date de la sortie
        $dateDeLaSortie = $sorties -> getDate();


        // obtenir la date limite d'inscription
         $dateLimiteInscription = $sorties -> getDateLimiteInscription();

        //todo : obtenir la durée de l'activité

        // Créer une nouvelle instance de DateTime pour la date de 30j auparavant
        $delaiMax = $today->modify('-30 days');

        //7 - ARCHIVE : si la date est supérieure à 1 mois, on archive :
        if ($dateDeLaSortie < $delaiMax) {
            $etatId = $this->entityManager->getReference(Etat::class, 7);
            $sorties->setEtat($etatId);
            $this->entityManager->flush();
        }
            //3 - CLOTURE : Si la date actuelle est supérieure à la date limite d'inscription, on clôture :
        if ($today > $dateLimiteInscription)
        {
            $etatId = $this -> entityManager -> getReference(Etat::class, 3);
            $sorties -> setEtat($etatId);
            $this -> entityManager -> flush();
        }
        //4 - EN COURS : Si la date actuelle correspond à la date de l'activité, on passe l'id à 4 :
        if ($today == $dateDeLaSortie)
        {
            $etatId = $this -> entityManager -> getReference(Etat::class, 4);
            $sorties -> setEtat($etatId);
            $this -> entityManager -> flush();
        }
        //5 - PASSÉE : Si la date actuelle est supérieure à la date de l'activité, on passe l'id à 5:
        if ($today > $dateDeLaSortie)
        {
            $etatId = $this -> entityManager -> getReference(Etat::class, 5);
            $sorties -> setEtat($etatId);
            $this -> entityManager -> flush();
        }
}

}