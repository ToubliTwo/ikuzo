<?php

namespace App\Controller\Admin;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Sorties;
use App\Entity\User;
use App\Entity\Ville;
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
            ->setTitle('Ikouzo')
            ->setLocales(['fr', 'en'])
            ->renderContentMaximized();
    }

    public function configureCrud(): Crud
    {
        return parent::configureCrud()
            ->renderContentMaximized()
            ->setDateTimeFormat('dd/MM/yyyy HH:mm:ss');

    }
    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToLogout('Logout', 'fa-solid fa-arrow-right-from-bracket');
        yield MenuItem::section('Users');
        yield MenuItem::linkToCrud('Users', 'fa fa-user', User::class);
        yield MenuItem::section('Villes');
        yield MenuItem::linkToCrud('Villes', 'fa fa-city', Ville::class);
        yield MenuItem::section('Campus');
        yield MenuItem::linkToCrud('Campus', 'fa fa-university', Campus::class);
        yield MenuItem::section('Sorties');
        yield MenuItem::linkToCrud('Sorties', 'fa fa-calendar', Sorties::class);
        yield MenuItem::section('Etats');
        yield MenuItem::linkToCrud('Etats', 'fa fa-check', Etat::class);
        yield MenuItem::section('Lieux');
        yield MenuItem::linkToCrud('Lieux', 'fa fa-map-marker', Lieu::class);


    }

}
