<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class, $this->getConfiguration("Titre", "Titre de l'annonce"))
            ->add('description', TextType::class, $this->getConfiguration("Description", "Description de l'article"))
            ->add('lien_video', UrlType::class, $this->getConfiguration("Lien HTTP de la vidéo", "URL de la vidéo"));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
