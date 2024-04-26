<?php

namespace App;

use App\Entity\Etat;
use App\Entity\Sorties;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\BrowserKit\Response;

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

        //2 - OUVERTE : Si la date actuelle est supérieur à la date limite, on clôture :
        if ($today > $dateLimiteInscription)
        {
            $etatId = $this -> entityManager -> getReference(Etat::class, 3);
            $sorties -> setEtat($etatId);
            $this -> entityManager -> flush();
        }

        //3 - CLOTURE : Si la date actuelle est supérieure à la date limite d'inscription, on clôture :
        if ($today > $dateLimiteInscription)
        {
            $etatId = $this -> entityManager -> getReference(Etat::class, 3);
            $sorties -> setEtat($etatId);
            $this -> entityManager -> flush();
        }

        //4 - EN COURS







        //5 - PASSÉE : Si la date actuelle est supérieure à la date de l'activité, on passe :
        if ($today > $dateDeLaSortie)
        {
            $etatId = $this -> entityManager -> getReference(Etat::class, 5);
            $sorties -> setEtat($etatId);
            $this -> entityManager -> flush();
        }

        //7 - ARCHIVE : si la date est supérieure à 1 mois, on archive :
        if ($dateDeLaSortie < $delaiMax)
        {
            $etatId = $this -> entityManager -> getReference(Etat::class, 7);
            $sorties -> setEtat($etatId);
            $this -> entityManager -> flush();
        }

        //1 - CRÉÉE sinon, c'est qu'elle est juste créée
        else {
            $etatId = $this -> entityManager -> getReference(Etat::class, 1);
            $sorties -> setEtat($etatId);
            $this -> entityManager -> flush();
        }


//        CAS SPECIAL - ANNULÉ (si suppression par l'organisateur) - CREEE si pas encore publiée
    }
}