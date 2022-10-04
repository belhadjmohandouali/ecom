<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountAddressController extends AbstractController
{
    private $entitymanager;

    public function __construct(ManagerRegistry $entitymanager){
        $this->entitymanager = $entitymanager;
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
        public function add(Request $request): Response
        {


            $address = new Address();
            $form = $this->createForm(AddressType::class, $address);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()){
                $address->setUser($this->getUser());

                $doctrine = $this->entitymanager->getManager();
                $doctrine->persist($address);
                $doctrine->flush($address);
                return $this->redirectToRoute('app_account_address');


            }

            return $this->render('account/address_form.html.twig', [
                'form' => $form->createView(),
            ]);
        }

    #[Route('/account/address_edit/{id}', name: 'app_account_address_edit')]
    public function edit(Request $request, $id): Response
    {
        $address = $this->entitymanager->getRepository(Address::class)->findOneById($id);

        if(!$address || $address->getUser() != $this->getUser()){
            return $this->redirectToRoute('app_account_address');
        }

        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $doctrine = $this->entitymanager->getManager();
            $doctrine->flush($address);
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
        $delete =false;
        if($address || $address->getUser() == $this->getUser()){
            $doctrine = $this->entitymanager->getManager();
            $doctrine->remove($address);
            $doctrine->flush($address);

        }
        $delete = "l'adresse a bien été supprimée";
        return $this->render('/account/address.html.twig',[
            'delete' => $delete

        ]);

        /*return $this->redirectToRoute('app_account_address');*/
    }
}
