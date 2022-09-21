<?php

namespace App\Controller;

use App\Services\CartServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    #[Route('/mon-panier', name: 'app_cart', methods: ['GET', 'POST'])]
    public function index(CartServices $cartServices): Response
    {
        // Ajouter le produit au panier
        dump($cartServices->getFullCart());
        return $this->render('cart/index.html.twig', [
            'controller_name' => 'CartController',
            'cart' => $cartServices->getFullCart(),
        ]);
    }

    #[Route('/cart/add/{id}', name: 'add_to_cart', methods: ['GET', 'POST'])]
    public function add(CartServices $cartServices, $id): Response
    {
        // Ajouter le produit au panier
       $cartServices->addToCart($id);

       // Récupère le nom du produit ajouté
       $productName = $cartServices->getFullCart()[$id]['product']->getName();

       $this->addFlash('success', sprintf("Le produit \" %s \" a été ajouté au panier", $productName));

       return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/remove/{id}', name: 'decrease_from_cart', methods: ['GET', 'POST'])]
    public function remove(CartServices $cartServices, Request $request): Response
    {
        $id = $request->get('id');

        // Récupère le nom du produit ajouté
        $productName = $cartServices->getFullCart()[$id]['product']->getName();

        $cartServices->decreaseFromCart($id);

        $this->addFlash('warning', sprintf("Une unité du produit \"%s\" a été supprimée du panier", $productName));


        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/delete', name: 'delete_all_from_cart', methods: ['GET', 'POST'])]
    public function delete(CartServices $cartServices, Request $request): Response
    {
        $id = $request->get('id');

        $cartServices->deleteAllToCart($id);

        $this->addFlash('danger', "Produit supprimé du panier");

        return $this->redirectToRoute('app_cart');
    }

}
