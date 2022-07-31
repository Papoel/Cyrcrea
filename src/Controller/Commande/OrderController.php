<?php

namespace App\Controller\Commande;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/commande')]
class OrderController extends AbstractController
{
    #[Route('/', name: 'app_order')]
    public function index(): Response
    {
        return $this->render('commande/index.html.twig', [
            'controller_name' => 'OrderController',
        ]);
    }
}
