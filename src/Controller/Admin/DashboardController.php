<?php

namespace App\Controller\Admin;

use App\Entity\Products;
use App\Entity\Users;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets; 
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/control-panel", name="control-panel")
     */
    public function index(): Response
    {
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('PIXIE Control Panel');
    }

    public function configureAssets(): Assets
    {
        return Assets::new()->addCssFile('css/control-panel.css');
    }
    
    public function configureMenuItems(): iterable
    {
        
        yield MenuItem::linkToUrl('Home', 'fas fa-home', "/");
        yield MenuItem::linkToCrud('Users', 'fas fa-users', Users::class);
        yield MenuItem::linkToCrud('Products', 'fas fa-shopping-cart', Products::class);
        
    }
}
