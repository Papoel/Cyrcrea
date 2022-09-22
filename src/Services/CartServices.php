<?php

namespace App\Services;

use App\Repository\ProductsRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class CartServices
{
    public function __construct(
        protected RequestStack     $requestStack,
        private ProductsRepository $productsRepository,
    ) { }

    // Définir le taux de la taxe à 20%
    private const TAXE = 0.2;


    // Récupérer le panier dans la session => OK
    public function getCart(): array
    {
        return $this->requestStack->getSession()->get('cart', []);
    }

    // Mettre à jour le panier dans la session => OK
    public function updateCart($cart): void
    {
        // Mettre à jour le panier dans la session
        $this->requestStack->getSession()->set('cart', $cart);
        $this->requestStack->getSession()->set('cartData', $this->getFullCart());;
    }

    // Ajouter un produit au panier et mettre a jour le stock produit dans la base de données
    public function addToCart($id): void
    {
        // Récupérer le panier dans la session
        $cart = $this->getCart();
        // Si le produit existe déjà dans le panier
        if (!empty($cart[$id])) {
            // On incrémente la quantité
            $cart[$id]++;
            // je mets à jour le stock de la base de données
        } else {
            // Sinon on ajoute le produit au panier
            $cart[$id] = 1;
        }
        // Mettre à jour le panier dans la session
        $this->updateCart($cart);
    }

    // Supprimer une unité de produit au panier => OK
    public function decreaseFromCart($id): void
    {
        $cart = $this->getCart();

        // Vérifier si le produit est déjà dans le panier
        if (isset($cart[$id])) {
            if ($cart[$id] > 1) {
                // Produit dans le panier
                    // 1. Je décrémente la quantité
                $cart[$id]--;
            } else {
                // Produit dans le panier avec une quantité de 1
                unset($cart[$id]);
            }
        }
        // Mise à jour du panier
        $this->updateCart($cart);
    }

    // Supprimer totalement le produit du panier => Ok
    public function deleteAllToCart($id): void
    {
        $cart = $this->getCart();

        // Vérifier si le produit est déjà dans le panier
        if (isset($cart[$id])) {
            // Produit dans le panier
                // 1. Je supprime le produit
            unset($cart[$id]);
        }
        // Mise à jour du panier
        $this->updateCart($cart);
    }

    // Récupérer le panier complet => OK
    public function getFullCart(): array
    {
        $fullCart = [];
        $cart = $this->getCart();
        $products = $this->productsRepository->findAll();

        foreach ($products as $product) {
            if (isset($cart[$product->getId()])) {
                $fullCart[$product->getId()] = [
                    'productId' => $product->getId(),
                    'product'   => $product,
                    'quantity'  => $cart[$product->getId()],
                    'price'     => $product->getPrice(),
                    'total'     => $product->getPrice() * $cart[$product->getId()],
                    'discount'  => $product->getDiscount(),
                    'prixTotal' => $product->getPrice() * $cart[$product->getId()] - $product->getPrice() * $cart[$product->getId()] * $product->getDiscount()/100,
                    'stock'     => $product->getStock(),
                ];
            }
        }
        return $fullCart;
    }
}
