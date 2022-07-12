<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/cgu')]
class CGUController extends AbstractController
{
    #[Route('/conditions-generales-utilisation', name: 'app_cgu')]
    public function index(): Response
    {
        return $this->render('cgu/index.html.twig');
    }
}
