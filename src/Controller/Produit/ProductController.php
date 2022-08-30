<?php

namespace App\Controller\Produit;

use App\Entity\Products;
use App\Repository\CategoriesRepository;
use App\Repository\ProductsRepository;
use App\Repository\ReviewRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/', name: 'app_product_index')]
    public function index(
        ProductsRepository $productsRepository,
        CategoriesRepository $categoriesRepository,
        ReviewRepository $reviewsProduct): Response
    {
        // Obtenir tous les produits à vendre
        $products = $productsRepository->findAll();
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
            'products'             => $products,
            'productBestSellers'   => $productsBestSeller,
            'productNewArrival'    => $productsIsNewArrival,
            'productSpecialOffer'  => $productsIsSpecialOffer,
            'productFeatured'      => $productsIsFeatured,
        ]);
    }

    #[Route('/produit/{slug}', name: 'app_product_show')]
    public function show(Products $product, ReviewRepository $reviewRepository): Response
    {
        // Calcul de la note moyenne d'un produit
        $note = $reviewRepository->countAverageProduct($product);


        return $this->render('product/view.html.twig', [
            'produit' => $product,
            'note' => $note,
        ]);
    }
}
