<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Address;
use App\Form\AddressType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountAddressController extends AbstractController
{
    private $entitymanager;

    public function __construct(ManagerRegistry $entitymanager, RequestStack $requestStack)
    {
        $this->entitymanager = $entitymanager;
        $this->session = $requestStack->getSession();
    }

    #[Route('/account/address', name: 'app_account_address')]
    public function index(): Response
    {
        //dd($this->getUser()->getAddresses());
        return $this->render('account/address.html.twig', [
            'controller_name' => 'AccountAddressController',
        ]);
    }

    #[Route('/account/address_add', name: 'app_account_address_add')]
    public function add(Cart $cart, Request $request): Response
    {


        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $address->setUser($this->getUser());

            $doctrine = $this->entitymanager->getManager();
            $doctrine->persist($address);
            $doctrine->flush($address);

            if ($cart->get()) {
                return $this->redirectToRoute('app_order');
            } else {
                return $this->redirectToRoute('app_account_address');
            }
        }

        return $this->render('account/address_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/account/address_edit/{id}', name: 'app_account_address_edit')]
    public function edit(Request $request, $id): Response
    {
        $address = $this->entitymanager->getRepository(Address::class)->findOneById($id);

        if (!$address || $address->getUser() != $this->getUser()) {
            return $this->redirectToRoute('app_account_address');
        }

        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $doctrine = $this->entitymanager->getManager();
            $doctrine->flush($address);
            $this->addFlash('success', "l'adresse à été bien modifiée");
            return $this->redirectToRoute('app_account_address');

        }

        return $this->render('account/address_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/account/address_delete/{id}', name: 'app_account_address_delete')]
    public function delete(Request $request, $id): Response
    {
        $address = $this->entitymanager->getRepository(Address::class)->findOneById($id);

        if ($address || $address->getUser() == $this->getUser()) {
            $doctrine = $this->entitymanager->getManager();
            $doctrine->remove($address);
            $doctrine->flush($address);

        }
        $this->addFlash('success', "l'adresse à été bien supprimée");
        return $this->redirectToRoute('app_account_address');


        /*return $this->redirectToRoute('app_account_address');*/
    }

    #[Route('/account/address_duplicate/{id}', name: 'app_account_address_duplicate')]
    public function duplicate(Request $request, $id): Response
    {
        $address = $this->entitymanager->getRepository(Address::class)->findOneById($id);

        if ($address || $address->getUser() == $this->getUser()) {
            $new_address = new Address();
            $new_address->setUser($address->getUser());
            $new_address->setName($address->getName());
            $new_address->setFirstname($address->getFirstname());
            $new_address->setLastname($address->getLastname());
            $new_address->setCompany($address->getCompany());
            $new_address->setAdresse($address->getAdresse());
            $new_address->setPostal($address->getPostal());
            $new_address->setCity($address->getCity());
            $new_address->setCountry($address->getCountry());
            $new_address->setPhone($address->getPhone());
            //dd($new_address);
            $doctrine = $this->entitymanager->getManager();
            $doctrine->persist($new_address);
            $doctrine->flush($new_address);

        }
        $this->addFlash('success', "l'adresse à été bien dupliquée");
        return $this->redirectToRoute('app_account_address');

    }
}
