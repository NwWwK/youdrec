<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends ApplicationType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'prenom',
                TextType::class,
                $this->getConfiguration("Prénom : ", "Votre prénom")
            )
            ->add(
                'pseudo',
                TextType::class,
                $this->getConfiguration("Pseudo : ", "Votre pseudo")
            )
            ->add(
                'email',
                EmailType::class,
                $this->getConfiguration("Email : ", "Votre email")
            )
            ->add(
                'picture',
                UrlType::class,
                $this->getConfiguration("Photo de profil : ", "URL de votre avatar")
            )
            ->add(
                'password',
                PasswordType::class,
                $this->getConfiguration("Mot de passe : ", "Votre mot de passe")
            )
            ->add(
                'passwordConfirm',
                PasswordType::class,
                $this->getConfiguration("Confirmation mot de passe", "Confirmation mot de passe")
            )
            ->add(
                'introduction',
                TextareaType::class,
                $this->getConfiguration("Présentation : ", "Présentez-vous")
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
