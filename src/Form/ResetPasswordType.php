<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'disabled' => true
            ])

            ->add('firstname', TextType::class, [
                'disabled' => true
            ])
            ->add('lastname', TextType::class, [
                'disabled' => true
            ])
            ->add('old_password', PasswordType::class, [
                'label' => "Mon mot de passe actuel",
                'mapped' =>false,
                'attr' => [
                    'placeholder' => "Saisissez votre mot de passe actuel"
                ]
            ])
            ->add('new_password', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' =>false,
                'invalid_message' => 'La confirmation du mot de passe doit être identique au mot de passe saisi',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options' => [
                    'label' => 'Mon nouveau mot de passe ',
                    'attr' => [
                        'placeholder' => "Saisissez votre nouveau mot de passe"
                    ]
                ],
                'second_options' => [
                    'label' => 'Confirmation du mot de passe',
                    'attr' => [
                        'placeholder' => "La confirmation de votre nouveau mot de passe"
                    ]
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Mettre à jour"
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
