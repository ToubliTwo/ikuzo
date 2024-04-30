<?php

namespace App\Controller\Admin;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Sorties;
use App\Entity\User;
use App\Entity\Ville;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    private AdminUrlGenerator $adminUrlGenerator;


    public function __construct(AdminUrlGenerator $adminUrlGenerator)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
    }

    #[Route('/admin', name: 'admin_dashboard_index')]
    public function index(): Response
    {
        return $this->render('admin/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle(title: 'Ikouzo')
            ->setLocales(['fr', 'en'])
            ->renderContentMaximized();
    }

    public function configureCrud(): Crud
    {
        return parent::configureCrud()
            ->renderContentMaximized()
            ->setDateTimeFormat(dateFormatOrPattern: 'dd/MM/yyyy HH:mm:ss')
            ->showEntityActionsInlined()
            ;
    }
    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard(label: 'Dashboard', icon: 'fa-solid fa-user-tie');
        yield MenuItem::linkToRoute(label: 'Retour au site',icon:  'fa fa-home', routeName: 'main_home');
        yield MenuItem::linkToLogout(label: 'Logout', icon: 'fa-solid fa-arrow-right-from-bracket');
        yield MenuItem::section(label: 'DONNEES');
        yield MenuItem::linkToCrud(label: 'Utilisateurs', icon: 'fa fa-user', entityFqcn: User::class);
        yield MenuItem::linkToCrud(label: 'Villes', icon: 'fa fa-city', entityFqcn: Ville::class);
        yield MenuItem::linkToCrud(label: 'Campus', icon: 'fa fa-university', entityFqcn:  Campus::class);
        yield MenuItem::linkToCrud(label: 'Sorties', icon: 'fa fa-calendar', entityFqcn: Sorties::class);
        yield MenuItem::linkToCrud(label: 'Etats', icon: 'fa fa-check', entityFqcn: Etat::class);
        yield MenuItem::linkToCrud(label: 'Lieux', icon: 'fa fa-map-marker', entityFqcn:  Lieu::class);
    }

    public function configureAssets(): Assets
    {
        return parent::configureAssets()
            ->addCssFile(pathOrAsset: 'css/admin.css');
    }
}
