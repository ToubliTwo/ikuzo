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

        // Créer une nouvelle instance de DateTime pour la date de 30j auparavant
//      $delaiMax = $today->modify('-30 days');
        $delaiMax = (clone $today)->sub(new \DateInterval('P30D'));

        // Calculer la différence en jours entre la date de la sortie et aujourd'hui
        $differenceEnJours = $today->diff($dateDeLaSortie)->days;

        // Utiliser un switch pour traiter chaque cas
        switch (true) {
            case $today > $dateDeLaSortie && $delaiMax < $dateDeLaSortie: //ARCHIVE
                $etatId = $this->entityManager->getReference(Etat::class, 7);
                break;

            case $today > $dateDeLaSortie: //PASSÉE
                $etatId = $this->entityManager->getReference(Etat::class, 5);
                break;

            case $today > $dateLimiteInscription && $today < $dateDeLaSortie: //CLOTURÉE
                $etatId = $this->entityManager->getReference(Etat::class, 3);
                break;

            case $differenceEnJours === 0: //ACTIVITÉ EN COURS
                $etatId = $this->entityManager->getReference(Etat::class, 4);
                break;

            default:
                // Aucune condition n'est satisfaite
                // Vous pouvez ajouter un comportement par défaut ici si nécessaire
                break;
        }











//        // ARCHIVE : si la date est supérieure à 1 mois, on archive :
//        if ($dateDeLaSortie > $delaiMax)
//        {
//            $etatId = $this -> entityManager -> getReference(Etat::class, 7);
//            $sorties -> setEtat($etatId);
//            $this -> entityManager -> flush();
//        }
//
//
//        /*/!\/!\/!\/!\/!\/!\/!\/!\/!\/!\/!\/!\/!\/!\/!\/!\/!\/!\/!\/!\/!\/!\/!\/!\/!\/!\/!\/!\/!\*/
//        // /!\ PASSÉE : Si la date actuelle est supérieure à la date de l'activité, on passe :
//        if ($today > $dateDeLaSortie)
//        {
//            $etatId = $this -> entityManager -> getReference(Etat::class, 5);
//            $sorties -> setEtat($etatId);
//            $this -> entityManager -> flush();
//        }
//        /*/!\/!\/!\/!\/!\/!\/!\/!\/!\/!\/!\/!\/!\/!\/!\/!\/!\/!\/!\/!\/!\/!\/!\/!\/!\/!\/!\/!\/!\*/
//
//
//
//       // CLOTURE : Si la date actuelle est supérieure à la date limite d'inscription, on clôture :
//       if ($today>$dateLimiteInscription && $today<$dateDeLaSortie)
//        {
//            $etatId = $this -> entityManager -> getReference(Etat::class, 3);
//            $sorties -> setEtat($etatId);
//            $this -> entityManager -> flush();
//        }
//
//        //4 - EN COURS Si la date de l'activité correspond à celle du jour
//        if ($today->format('d-m-Y') === $dateDeLaSortie->format('d-m-Y'))
//        {
//            $etatId = $this -> entityManager -> getReference(Etat::class, 4);
//            $sorties -> setEtat($etatId);
//            $this -> entityManager -> flush();
//        }
//        ACTION DE L'UTILISATEUR'
        //2 - OUVERTE : Si la date actuelle est inférieure à la date limite  :
//        if ($today < $dateLimiteInscription)
//        {
//            $etatId = $this -> entityManager -> getReference(Etat::class, 2);
//            $sorties -> setEtat($etatId);
//            $this -> entityManager -> flush();
//        }







    }
}