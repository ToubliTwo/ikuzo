<?php

namespace App\DTO;

class UserAvecPseudoNomPrenomDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $pseudo,
        public readonly string $nom,
        public readonly string $prenom
    ) {

    }

}