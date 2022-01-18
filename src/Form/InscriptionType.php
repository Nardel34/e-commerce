<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => "Adresse email",
                'attr' => [
                    'placeholder' => "Entrez votre adresse email"
                ],
                'required' => false
            ])
            ->add('password', PasswordType::class, [
                'label' => "Mot de passe",
                'attr' => [
                    'placeholder' => "Entrez votre mot de passe"
                ],
                'required' => false
            ])
            ->add('fullName', TextType::class, [
                'label' => "Nom complet",
                'attr' => [
                    'placeholder' => "Entrez votre nom et prénom"
                ],
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
