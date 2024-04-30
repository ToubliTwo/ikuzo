<?php

namespace App\Controller\Admin\Fields;

use App\Entity\Campus;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CampusCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Campus::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new(propertyName: 'id')->hideOnForm(),
            TextField::new(propertyName: 'nom'),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return Crud::new()
            ->setSearchFields(['nom'])
            ->setDefaultSort(['nom' => 'ASC'])
            ->renderContentMaximized()
            ->setDateTimeFormat(dateFormatOrPattern: 'dd/MM/yyyy HH:mm:ss')
            ->showEntityActionsInlined();
    }
}
