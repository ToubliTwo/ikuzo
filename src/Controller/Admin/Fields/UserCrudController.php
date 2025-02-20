<?php

namespace App\Controller\Admin\Fields;

use App\Entity\User;
use App\Form\ImportCsvFormType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $rolesChoices = ['User' => 'ROLE_USER', 'Admin' => 'ROLE_ADMIN', 'Off' => 'ROLE_OFF'];
        return [
            IdField::new(propertyName: 'id')->hideOnForm(),
            TextField::new(propertyName: 'nom'),
            TextField::new(propertyName: 'prenom'),
            TextField::new(propertyName: 'telephone'),
            EmailField::new(propertyName: 'email'),
            TextField::new(propertyName: 'plainPassword')->onlyOnForms(),
            TextField::new(propertyName: 'pseudo'),
            VichImageField::new(propertyName: 'imageName')->hideOnForm(),
            VichImageField::new(propertyName: 'imageFile')->hideOnIndex(),
            BooleanField::new(propertyName: 'administrateur'),
            BooleanField::new(propertyName: 'actif')->onlyOnForms(),
            ChoiceField::new(propertyName: 'roles')
                ->onlyOnForms()
                ->setChoices($rolesChoices)
                ->allowMultipleChoices(),
            AssociationField::new(propertyName: 'campus'),
            AssociationField::new(propertyName: 'sortie')->onlyOnDetail(),
            AssociationField::new(propertyName: 'sortieOrganise')->onlyOnDetail(),
        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(TextFilter::new(propertyName: 'nom'))
            ->add(TextFilter::new(propertyName: 'prenom'))
            ->add(TextFilter::new(propertyName: 'email'))
            ->add(TextFilter::new(propertyName: 'pseudo'))
            ->add(EntityFilter::new(propertyName: 'campus'))
            ->add(EntityFilter::new(propertyName: 'sortie'))
            ->add(EntityFilter::new(propertyName: 'sortieOrganise'));
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['nom', 'prenom', 'email', 'pseudo', 'campus.nom', 'sortie.nom', 'sortieOrganise.nom'])
            ->setDefaultSort(['nom' => 'ASC'])
            ->renderContentMaximized()
            ->setDateTimeFormat(dateFormatOrPattern: 'dd/MM/yyyy HH:mm:ss')
            ->showEntityActionsInlined();
    }

    #[Route('/admin/import-csv', name: 'user_crud_import_csv')]
    public function importCsvAction(Request $request) : Response
    {
        $form = $this->createForm(ImportCsvFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('file')->getData();
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($this->getParameter('csv_directory'), $fileName);
            $this->addFlash('success', 'Fichier importé avec succès !');
            return $this->redirectToRoute('admin');
        }
        return $this->render('admin/fields/importCsv.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}