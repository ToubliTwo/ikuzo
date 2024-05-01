<?php

namespace App\EventListener;

use App\Entity\User;
use Doctrine\ORM\Event\PreUpdateEventArgs;

class UserEventListener
{
    public function preUpdate(User $user, PreUpdateEventArgs $args): void
    {
        // Check if 'actif' column has changed
        if ($args->hasChangedField('actif')) {
            $newActifValue = $args->getNewValue('actif');

            // Update roles based on 'actif' column value
            if ($newActifValue) {
                $user->setRoles(["ROLE_USER"]);
            } else {
                $user->setRoles(["ROLE_OFF"]);
            }

            // If both 'actif' and 'administrateur' are true, grant 'ROLE_ADMIN'
            if ($newActifValue && $user->isAdministrateur()) {
                $user->setRoles(["ROLE_USER", "ROLE_ADMIN"]);
            }
        }
    }
}