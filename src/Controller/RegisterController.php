<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;




class RegisterController extends AbstractController
{
    private $entityManager;

    public function __construct(ManagerRegistry $entityManager){
        $this->entityManager = $entityManager;

    }

    #[Route('/inscription', name: 'app_register')]
    public function index(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $success = null;
        $fail=null;
        $user = new User();
       //instancier formulaire
       $form = $this->createForm(RegisterType::class,$user);
       //ecouter la requete post
       $form->handleRequest($request);
       //verifier si mon formulaire est soumis et si mon formulaire est valide
        if ($form->isSubmitted() && $form->isValid()){
            //recupérer les infos du formulaire dans la variable $user
            $user = $form->getData();
            $password = $passwordHasher->hashPassword($user,$user->getPassword());$password = $passwordHasher->hashPassword($user,$user->getPassword());$password = $passwordHasher->hashPassword($user,$user->getPassword());
            $user->setPassword($password);
            //créer un objet doctrine
            $doctrine =  $this->entityManager->getManager();
            //figer les données du form
            $doctrine->persist($user);
            //enregistrer les données du form
            $doctrine->flush();
           // dd($user);
            $success = "Votre compte a bien été crée";

        }

       return $this->render('register/index.html.twig',[
           'form' => $form->createView(),
           'success' => $success

       ]);
    }
}
