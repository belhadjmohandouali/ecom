<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,[
                'label'=> 'Le nom de votre addresse',
                'attr'=> [
                    'placeholder'=>"Nommez votre addresse"
                ]
            ])
            ->add('firstname',TextType::class,[
                'label'=> 'Votre prénom',
                'attr'=> [
                    'placeholder'=>"Saisissez votre prénom"
                ]
            ])
            ->add('lastname',TextType::class,[
                'label'=> 'Votre nom',
                'attr'=> [
                    'placeholder'=>"Saisissez votre prénom"
                ]
            ])
            ->add('company',TextType::class,[
                'label'=> 'Votre société',
                'attr'=> [
                    'placeholder'=>"(facultatif) Saisissez le nom de votre société)"
                ]
            ])
            ->add('adresse',TextType::class,[
                'label'=> 'Votre addresse',
                'attr'=> [
                    'placeholder'=>"Saisissez Votre addresse",
                    'required' => false
                ]
            ])
            ->add('postal',TextType::class,[
                'label'=> 'Votre code postal',
                'attr'=> [
                    'placeholder'=>"Entrez votre code postal"
                ]
            ])
            ->add('city',TextType::class,[
                'label'=> 'Ville',
                'attr'=> [
                    'placeholder'=>"Entreez votre ville"
                ]
            ])
            ->add('country',CountryType::class,[
                'label'=> 'Pays',
                'attr'=> [
                    'placeholder'=>"Entrez votre pays"
                ]
            ])
            ->add('phone',TelType::class,[
                'label'=> 'Votre téléphone',
                'attr'=> [
                    'placeholder'=>"Votre numéro de téléphone"
                ]
            ])
            ->add('submit', SubmitType::class,[
                'label'=>'Valider',
                'attr'=> [
                    'class'=>"btn-block btn-info"
                ]
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
