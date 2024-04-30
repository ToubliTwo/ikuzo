<?php

namespace App\Controller\Admin\Fields;

use App\Entity\Sorties;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
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
            IdField::new(propertyName: 'id')->hideOnForm(),
            TextField::new(propertyName: 'titre'),
            DateTimeField::new(propertyName: 'date'),
            TimeField::new(propertyName: 'duree'),
            DateTimeField::new(propertyName: 'dateLimiteInscription'),
            NumberField::new(propertyName: 'nombreDePlaces'),
            TextEditorField::new(propertyName: 'description'),
            AssociationField::new(propertyName: 'etat'),
            AssociationField::new(propertyName: 'campus'),
            AssociationField::new(propertyName: 'lieu'),
            AssociationField::new(propertyName: 'organisateur'),
        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('titre')
            ->add('date')
            ->add('duree')
            ->add('dateLimiteInscription')
            ->add('etat')
            ->add('campus')
            ->add('lieu')
            ->add('organisateur')
            ->add('nombreDePlaces')
            ->add('description')
            ->add('users')
            ;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return Crud::new()
            ->setSearchFields(['titre', 'date', 'etat', 'campus', 'lieu', 'organisateur'])
            ->setDefaultSort(['date' => 'ASC'])
            ->renderContentMaximized()
            ->setDateTimeFormat(dateFormatOrPattern: 'dd/MM/yyyy HH:mm:ss')
            ->showEntityActionsInlined();
    }

}
