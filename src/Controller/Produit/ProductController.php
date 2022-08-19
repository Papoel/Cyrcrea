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
//        $product = $productsRepository->findOneBy(['id' => 3]);
//
//        $categoriesProduct = [];
//
//        foreach ($product->getCategories() as $category) {
//            $categoriesProduct[] = $category->getName();
//        }
//        dd('Liste des catégories du Produit n°' .$product->getId(),
//            $categoriesProduct
//        );

        return $this->render('product/index.html.twig', [
            'produits' => $productsRepository->findAll(),
            'categories' => $categoriesRepository->findAll(),
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
