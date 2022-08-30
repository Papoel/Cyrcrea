<?php

namespace App\Controller\Admin;

use App\Entity\Carriers;
use App\Entity\Categories;
use App\Entity\Order;
use App\Entity\Products;
use App\Entity\ReviewsProduct;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
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
        yield MenuItem::linkToRoute('Visiter le site', 'fas fa-store', 'app_product_index');

        yield MenuItem::section('Gestion utilisateurs')->setBadge(count($this->entityManager->getRepository(User::class)->findAll()), 'dark');
        yield MenuItem::subMenu('Actions Utilisateurs', 'fas fa-users')
            ->setCssClass('fst-italic text-muted')
            ->setSubItems([
                MenuItem::linkToCrud('Créer', 'fas fa-plus', User::class)
                    ->setAction(Crud::PAGE_NEW),
                MenuItem::linkToCrud('Voir', 'fas fa-eye', User::class),
                ]
            )
        ;

        yield MenuItem::section('Gestion produits')
            ->setBadge(count($this->entityManager->getRepository(Products::class)->findAll()), 'dark');
        yield MenuItem::subMenu('Actions Produits', 'fa-brands fa-product-hunt')
            ->setCssClass('fst-italic text-muted')
            ->setSubItems([
                MenuItem::linkToCrud('Ajouter', 'fas fa-plus', Products::class)
                    ->setAction(Crud::PAGE_NEW),
                MenuItem::linkToCrud('Voir', 'fas fa-eye', Products::class),
                ]
            )
        ;

        yield MenuItem::section('Gestion livreurs')
            ->setBadge(count($this->entityManager->getRepository(Categories::class)->findAll()), 'dark');
        yield MenuItem::subMenu('Actions Livreurs', 'fas fa-truck')
            ->setCssClass('fst-italic text-muted')
            ->setSubItems([
                MenuItem::linkToCrud('Ajouter', 'fas fa-plus', Carriers::class)
                    ->setAction(Crud::PAGE_NEW),
                MenuItem::linkToCrud('Voir', 'fas fa-eye', Carriers::class),
                ]
            )
        ;

        yield MenuItem::section('Gestion catégories')
            ->setBadge(count($this->entityManager->getRepository(Categories::class)->findAll()), 'dark');
        yield MenuItem::subMenu('Actions Catégories', 'fas fa-boxes-stacked')
            ->setCssClass('fst-italic text-muted')
            ->setSubItems([
                MenuItem::linkToCrud('Ajouter', 'fas fa-plus', Categories::class)
                    ->setAction(Crud::PAGE_NEW),
                MenuItem::linkToCrud('Voir', 'fas fa-eye', Categories::class),
                ]
            )
        ;

        yield MenuItem::section('Gestion commandes')
            ->setBadge(count($this->entityManager->getRepository(Order::class)->findAll()), 'dark');
        yield MenuItem::subMenu('Actions Commandes', 'fa-brands fa-jedi-order')
            ->setCssClass('fst-italic text-muted')
            ->setSubItems([
                MenuItem::linkToCrud('Voir', 'fas fa-eye', Order::class),
                ]
            )
        ;

        yield MenuItem::section('Critiques Produits')
            ->setBadge(count($this->entityManager->getRepository(ReviewsProduct::class)->findAll()), 'danger');
        yield MenuItem::subMenu('Actions Critiques', 'fas fa-comment-dots')
            ->setCssClass('fst-italic text-muted')
            ->setSubItems([
                MenuItem::linkToCrud('Voir', 'fas fa-eye', ReviewsProduct::class),
                ]
            )
        ;


        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
