<?php

namespace App\Controller\Produit;

use App\Entity\Products;
use App\Repository\CategoriesRepository;
use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/', name: 'app_product_index')]
    public function index(ProductsRepository $productsRepository, CategoriesRepository $categoriesRepository): Response
    {
        // Obtenir tous les produits à vendre
        $products = $productsRepository->findAll();
        // Obtenir toutes les catégories TODO: à re-factoriser (usage dans le Header !)
        $categories = $categoriesRepository->findAll();

        // Obtenir tous les produits 'Meilleurs Ventes' = true
        $productsBestSeller = $productsRepository->findBy(['isBest' => true]);
        // Obtenir tous les produits 'Nouvel arrivage'
        $productsIsNewArrival = $productsRepository->findBy(['isNewArrival' => true]);
        // Obtenir tous les produits 'Promotion'
        $productsIsSpecialOffer = $productsRepository->findBy(['isSpecialOffer' => true]);
        // Obtenir tous les produits 'En vedette'
        $productsIsFeatured = $productsRepository->findBy(['isFeatured' => true]);

        // Dumper dans un tableau
         // dd([$productsBestSeller, $productsIsNewArrival, $productsIsSpecialOffer, $productsIsFeatured]);

        return $this->render('product/index.html.twig', [
            'produits'      => $products,
            'categories'    => $categories,
            'bestSellers'   => $productsBestSeller,
            'news'          => $productsIsNewArrival,
            'offerSpecials' => $productsIsSpecialOffer,
            'featured'      => $productsIsFeatured,
        ]);
    }

    #[Route('/produit/{slug}', name: 'app_product_show')]
    public function show(Products $product, CategoriesRepository $categoriesRepository): Response
    {
        // TODO: Re-Factorisation général, ne passer les catégories que dans le Header...
        return $this->render('product/show.html.twig', [
            'produits' => $product,
            'categories' => $categoriesRepository->findAll(),
        ]);
    }
}
