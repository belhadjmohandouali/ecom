<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ResetPasswordType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AccountPasswordController extends AbstractController
{
    private $entityManager;
    public function __construct(ManagerRegistry $entityManager){
        $this->entityManager = $entityManager;
    }

    #[Route('/reset-password', name: 'reset_password')]
    public function index(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $success = null;
        $fail=null;
        $user = $this->getUser();
        //instancier formulaire
        $form = $this->createForm(ResetPasswordType::class,$user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() ){
            $old_password = $form->get('old_password')->getData();

            if ($passwordHasher->isPasswordValid($user,$old_password)){
                $new_pwd = $form->get('new_password')->getData();
                $pwd = $passwordHasher->hashPassword($user, $new_pwd);

                $doctrine = $this->entityManager->getManager();
                $user->setPassword($pwd);
                //$doctrine->persist($user);
                $doctrine->flush($user);
                $success = "Votre mot de passe à bien été modifié";

            }else{
                $fail = "Veuillez saisir le bon mot de passe actuel";
            }
        }

        return $this->render('account/password.html.twig', [
            'form' => $form->createView(),
            'success' => $success,
            'fail' =>$fail
        ]);
    }
}
