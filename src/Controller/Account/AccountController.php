<?php

namespace App\Controller\Account;

use App\Entity\Address;
use App\Entity\User;
use App\Form\AddressFormType;
use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/account')]
class AccountController extends AbstractController
{
    public function __construct(private Security $security)
    {
    }

    #[Route('/', name: 'app_account')]
    public function index(): Response
    {
        return $this->render('account/index.html.twig');
    }

    #[Route('/account-detail', name: 'app_account-detail')]
    public function detail(): Response
    {
        return $this->render('account/account_detail.html.twig');
    }

    #[Route('/address/show', name: 'app_address_show', methods: ['GET'])]
    public function showAddress(EntityManagerInterface $em): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $addresses = $em->getRepository(Address::class)->findUserAddresses($user->getId());

        return $this->render('account/address/show.html.twig', [
            'addresses' => $addresses
        ]);
    }

    #[Route('/address/add', name: 'app_address_add', methods: ['GET', 'POST'])]
    public function addAddress(Request $request, EntityManagerInterface $manager): Response
    {
        $address = new Address();
        $user = $this->getUser();

        $form = $this->createForm(AddressFormType::class, $address);
        $form->handleRequest($request);
        $data = $form->getData();

        if ($form->isSubmitted() && $form->isValid()) {
            $address->setUser($user);

            $manager->persist($address);

            $manager->flush();

            return $this->redirectToRoute('app_account');
        }

        return $this->renderForm('account/address/add.html.twig', [
            'formAddress' => $form
        ]);
    }

    #[Route('/address/edit/{id}', name: 'app_address_edit', methods: ['GET', 'POST'])]
    public function editAddress(Request $request, Address $address, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AddressFormType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($address);
            $entityManager->flush();

            $this->addFlash('success', 'Votre adresse à bien été mis à jour.');

            return $this->redirectToRoute('app_account', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('account/address/edit.html.twig', [
            'formAddress' => $form
        ]);
    }

}
