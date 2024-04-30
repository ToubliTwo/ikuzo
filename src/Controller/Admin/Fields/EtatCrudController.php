<?php

namespace App\Controller\Admin\Fields;

use App\Entity\Etat;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;

class EtatCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Etat::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new(propertyName: 'id')->hideOnForm(),
            TextField::new(propertyName: 'libelle'),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return Crud::new()
            ->setSearchFields(['libelle'])
            ->setDefaultSort(['libelle' => 'ASC'])
            ->renderContentMaximized()
            ->setDateTimeFormat(dateFormatOrPattern: 'dd/MM/yyyy HH:mm:ss')
            ->showEntityActionsInlined();
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(TextFilter::new(propertyName: 'libelle'));
    }
}
