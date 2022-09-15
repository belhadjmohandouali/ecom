<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ResetPasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountPasswordController extends AbstractController
{
    #[Route('/reset-password', name: 'reset_password')]
    public function index(): Response
    {
        $user = $this->getUser();
        //instancier formulaire
        $form = $this->createForm(ResetPasswordType::class,$user);

        return $this->render('account/password.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
