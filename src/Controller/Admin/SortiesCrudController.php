<?php

namespace App\Controller\Admin;

use App\Entity\Sorties;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TimeField;

class SortiesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Sorties::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('titre'),
            DateTimeField::new('date'),
            TimeField::new('duree'),
            DateTimeField::new('dateLimiteInscription'),
            NumberField::new('nombreDePlaces'),
            TextEditorField::new('description'),
            AssociationField::new('etat'),
            AssociationField::new('campus'),
            AssociationField::new('lieu'),
            AssociationField::new('organisateur'),
        ];
    }
}
