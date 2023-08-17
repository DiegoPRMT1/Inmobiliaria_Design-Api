<?php

namespace App\Controller\Admin;

use App\Entity\Property;
use App\Entity\RealState;
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

    /**
     * @Route("/{_locale}/admin", name="admin")
     */
    public function index(): Response
    {
        //$routeBuilder = $this->get(AdminUrlGenerator::class);
        //return $this->redirect($routeBuilder->setController(RealStateCrudController::class)->generateUrl());
        $url = $this->adminUrlGenerator
            ->setController(RealStateCrudController::class)
            //->setAction('edit')
            //->setEntityId(1)
            ->generateUrl();


        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Your Best Host');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Inicio', 'fa fa-home');

        yield MenuItem::subMenu('Tipos de Propiedades', 'fa fa-wpforms')
            ->setSubItems([
                MenuItem::linkToCrud('Crear Propiedades', 'fas fa-list', RealState::class)->setAction('new'),
                MenuItem::linkToCrud('Ver Propiedades', 'fas fa-list', RealState::class)
            ]);

        yield MenuItem::subMenu('Inmuebles', 'fa fa-wpforms')
            ->setSubItems([
                MenuItem::linkToCrud('Crear Inmuebles', 'fas fa-list', Property::class)->setAction('new'),
                MenuItem::linkToCrud('Ver Inmuebles', 'fas fa-list', Property::class)
            ]);
    }


}
