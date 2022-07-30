<?php

namespace App\Controller\Address;

use App\Entity\Address;
use App\Entity\User;
use App\Form\AddressType;
use App\Repository\AddressRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/adresse')]
class AddressController extends AbstractController
{
    #[Route('/ajouter', name: 'app_address_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AddressRepository $addressRepository): Response
    {
        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $this->getUser();
            $address->setUser($user);

            $addressRepository->add($address, true);

            $this->addFlash(
                'success',
                sprintf("L'adresse %s a bien été crée !", $address->getName())
            );

            return $this->redirectToRoute('app_address_all', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('adresse/new.html.twig', [
            'adresse' => $address,
            'form' => $form,
        ]);
    }

    #[Route('/tout-voir', name: 'app_address_all', methods: ['GET'])]
    public function all(AddressRepository $addressRepository): Response
    {
        $addresses = $addressRepository->findUserAddresses($this->getUser());

        return $this->render('adresse/show.html.twig', [
            'addresses' => $addresses,
        ]);
    }

    #[Route('/{id}', name: 'app_address_show', methods: ['GET'])]
    public function show(Address $address): Response
    {
        return $this->render('adresse/show.html.twig', [
            'adresse' => $address,
        ]);
    }

    #[Route('/{id}/modifier', name: 'app_address_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Address $address, AddressRepository $addressRepository): Response
    {
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $addressRepository->add($address, true);

            $this->addFlash('success', 'Votre adresse a bien été mise à jour.');

            return $this->redirectToRoute('app_address_all', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('adresse/edit.html.twig', [
            'adresse' => $address,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_address_delete', methods: ['POST'])]
    public function delete(Request $request, Address $address, AddressRepository $addressRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $address->getId(), $request->request->get('_token'))) {
            $addressRepository->remove($address, true);

            $this->addFlash(
                'danger',
                sprintf("L'adresse nommée %s a bien été effacée !", $address->getName())
            );
        }

        return $this->redirectToRoute('app_address_all', [], Response::HTTP_SEE_OTHER);
    }
}
