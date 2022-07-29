<?php

namespace App\Controller\Compte;

use App\Entity\Address;
use App\Entity\User;
use App\Form\AccountDetailsFormType;
use App\Form\AddressFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/compte')]
class AccountController extends AbstractController
{
    public function __construct(private readonly Security $security)
    {
    }

    #[Route('/', name: 'app_compte')]
    public function index(): Response
    {
        return $this->render('compte/index.html.twig');
    }

    #[Route('/compte-detail', name: 'app_compte-detail')]
    public function detail(EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $userDetails = $entityManager->getRepository(User::class)->find($user);
        $userAddresses = $entityManager->getRepository(Address::class)->findUserAddresses($user);


        return $this->render('compte/account_detail.html.twig', [
            'details' => $userDetails,
            'addresses' => $userAddresses
        ]);
    }

    #[Route('/compte-detail/edit', name: 'app_compte_edit', methods: ['GET', 'POST'])]
    public function editCompte(Request $request, EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();


        $form = $this->createForm(AccountDetailsFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Vos informations ont été modifiées avec succès.');

            return $this->redirectToRoute('app_compte', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('compte/account_edit.html.twig', [
            'formAccount' => $form
        ]);
    }
}
