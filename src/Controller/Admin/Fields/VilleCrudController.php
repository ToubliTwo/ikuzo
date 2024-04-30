<?php

namespace App\Controller\Admin\Fields;

use App\Entity\Ville;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;

class VilleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Ville::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new(propertyName: 'id')->hideOnForm(),
            TextField::new(propertyName: 'nom'),
            TextField::new(propertyName: 'codePostal'),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return Crud::new()
            ->setSearchFields(['nom', 'codePostal'])
            ->setDefaultSort(['nom' => 'ASC'])
            ->renderContentMaximized()
            ->setDateTimeFormat(dateFormatOrPattern: 'dd/MM/yyyy HH:mm:ss')
            ->showEntityActionsInlined();
    }
    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(TextFilter::new(propertyName: 'nom'))
            ->add(TextFilter::new(propertyName: 'codePostal'));
    }

}
