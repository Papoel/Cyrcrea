<?php

namespace App\Controller\Account;

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
    public function detail(EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $userDetails = $entityManager->getRepository(User::class)->find($user);
        $userAddresses = $entityManager->getRepository(Address::class)->findUserAddresses($user);


        return $this->render('account/account_detail.html.twig', [
            'details' => $userDetails,
            'addresses' => $userAddresses
        ]);
    }

    #[Route('/account-detail/edit', name: 'app_account_edit', methods: ['GET', 'POST'])]
    public function editAccount(Request $request, EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $form = $this->createForm(AccountDetailsFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Vos informations ont été modifié avec succès.');

            return $this->redirectToRoute('app_account', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('account/account_edit.html.twig', [
            'formAccount' => $form
        ]);
    }

    #[Route('/address/show', name: 'app_address_show', methods: ['GET'])]
    public function showAddress(EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $addresses = $entityManager->getRepository(Address::class)->findUserAddresses($user->getId());

        return $this->render('account/address/show.html.twig', [
            'addresses' => $addresses
        ]);
    }

    #[Route('/address/add', name: 'app_address_add', methods: ['GET', 'POST'])]
    public function addAddress(Request $request, EntityManagerInterface $entityManager): Response
    {
        $address = new Address();
        $user = $this->getUser();

        $form = $this->createForm(AddressFormType::class, $address);
        $form->handleRequest($request);
        $data = $form->getData();

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $address->setUser($user);

            $entityManager->persist($address);
            $entityManager->flush();

            $this->addFlash('success', 'Votre adresse à bien été ajoutée.');

            return $this->redirectToRoute('app_address_show');
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

            return $this->redirectToRoute('app_address_show', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('account/address/edit.html.twig', [
            'formAddress' => $form
        ]);
    }

    #[Route('/address/delete/{id}', name: 'app_address_delete', methods: ['POST'])]
    public function deleteAddress(Request $request, EntityManagerInterface $entityManager, Address $address): Response
    {
        /** @var User $user */
        $user = $this->security->getUser();

        if (
            $this->isCsrfTokenValid(
                'address_deletion_' .
                $address->getId(),
                $request->request->get('csrf_token')
            )
        ) {
            $entityManager->remove($address);
            $entityManager->flush();

            $this->addFlash(
                'danger',
                sprintf('L\'adresse %s a bien été effacée !', $address->getName())
            );

            return $this->redirectToRoute('app_address_show');
        }

        return $this->render('account/index.html.twig');
    }
}
