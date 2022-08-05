<?php

namespace App\Controller\Admin;

use App\Entity\Carrier;
use App\Entity\Categories;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\ReviewsProduct;
use App\Entity\TagsProduct;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    public function __construct(AdminUrlGenerator $adminUrlGenerator)
    {
    }

    #[Route('/admin', name: 'admin')]
    #[isGranted('ROLE_ADMIN')]
    public function index(): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException("Votre rôle ne vous permet pas d'accéder a l'administration");
        }

        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('La boutique CyrCrea')
            ->renderContentMaximized()
            ->renderSidebarMinimized()
        ;
    }

    public function configureMenuItems(): iterable
    {

        yield MenuItem::linkToRoute('Visiter le site', 'fas fa-store', 'app_home');
        ;

        yield MenuItem::section('Gestion des utilisateurs');
        yield MenuItem::subMenu('Actions', 'fas fa-users')->setSubItems([
            MenuItem::linkToCrud('Créer un Utilisateur', 'fas fa-plus', User::class)
                ->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Voir les Utilisateurs', 'fas fa-eye', User::class),
        ]);

        yield MenuItem::section('Gestion des produits');
        yield MenuItem::subMenu('Actions', 'fa-brands fa-product-hunt')->setSubItems([
            MenuItem::linkToCrud('Insérer un Produit', 'fas fa-plus', Product::class)
                ->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Voir les Produits', 'fas fa-eye', Product::class),
        ]);

        yield MenuItem::section('Gestion des transporteurs');
        yield MenuItem::subMenu('Actions', 'fas fa-truck')->setSubItems([
            MenuItem::linkToCrud('Ajouter un Transporteur', 'fas fa-plus', Carrier::class)
                ->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Voir les Transporteurs', 'fas fa-eye', Carrier::class),
        ]);

        yield MenuItem::section('Gestion des catégories');
        yield MenuItem::subMenu('Actions', 'fas fa-boxes-stacked')->setSubItems([
            MenuItem::linkToCrud('Ajouter une Catégorie', 'fas fa-plus', Categories::class)
                ->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Voir les Catégorie', 'fas fa-eye', Categories::class),
        ]);

        yield MenuItem::section('Gestion des commandes');
        yield MenuItem::subMenu('Actions', 'fa-brands fa-jedi-order')->setSubItems([
            MenuItem::linkToCrud('Voir les Commandes', 'fas fa-eye', Order::class),
        ]);

        yield MenuItem::section('Critiques des Produits');
        yield MenuItem::subMenu('Actions', 'fas fa-comment-dots')->setSubItems([
            MenuItem::linkToCrud('Voir les Critiques', 'fas fa-eye', ReviewsProduct::class),
        ]);

        yield MenuItem::section('Gestion des Tags');
        yield MenuItem::subMenu('Actions', 'fas fa-hashtag')->setSubItems([
            MenuItem::linkToCrud('Ajouter une Tag', 'fas fa-plus', TagsProduct::class)
                ->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Voir les Tags', 'fas fa-eye', TagsProduct::class),
        ]);


        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
