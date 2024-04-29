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
    }
}