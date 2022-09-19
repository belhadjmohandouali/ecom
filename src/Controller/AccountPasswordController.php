<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ResetPasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AccountPasswordController extends AbstractController
{
    #[Route('/reset-password', name: 'reset_password')]
    public function index(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = $this->getUser();
        //instancier formulaire
        $form = $this->createForm(ResetPasswordType::class,$user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() ){
            $old_password = $form->get('old_password')->getData();
            dd($passwordHasher->isPasswordValid($user,$old_password));
            if ($passwordHasher->isPasswordValid($user,$old_password)){

            }
        }

        return $this->render('account/password.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
