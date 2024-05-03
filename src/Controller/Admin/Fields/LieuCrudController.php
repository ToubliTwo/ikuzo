<?php

namespace App\Controller\Admin\Fields;

use App\Entity\Lieu;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;

class LieuCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Lieu::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new(propertyName: 'id')->hideOnForm(),
            TextField::new(propertyName: 'nom'),
            TextField::new(propertyName: 'rue'),
            NumberField::new(propertyName: 'latitude'),
            NumberField::new(propertyName: 'longitude'),
            AssociationField::new(propertyName: 'ville')
                ->setFormTypeOptions([
                    'by reference' => false,
                ]),
            CollectionField::new(propertyName: 'sorties')
                ->setFormTypeOptions([
                    'by reference' => false,
                ]),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return Crud::new()
            ->setEntityLabelInSingular(label: 'Lieu')
            ->setEntityLabelInPlural(label: 'Lieux')
            ->setSearchFields(['id', 'nom', 'rue', 'latitude', 'longitude', 'ville.nom', 'sorties.nom'])
            ->setDefaultSort(['nom' => 'ASC'])
            ->renderContentMaximized()
            ->setDateTimeFormat(dateFormatOrPattern: 'dd/MM/yyyy HH:mm:ss')
            ->showEntityActionsInlined();
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(TextFilter::new(propertyName: 'nom'))
            ->add(TextFilter::new(propertyName: 'rue'))
            ->add(TextFilter::new(propertyName: 'latitude'))
            ->add(TextFilter::new(propertyName: 'longitude'))
            ->add(EntityFilter::new(propertyName: 'ville'))
            ->add(EntityFilter::new(propertyName: 'sorties'))
        ;
    }
}
