<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',TextareaType::class)
            ->add('content', TextareaType::class)
            /**
             * EntityType
             * type de champ pour sélectionner une entité.
             * (liste d'ojets Category dans un <select>)
             */

            ->add('category', EntityType::class,[
                'class' => Category::class,     //nom de l'entité
                'choice_label' => 'name'        // nom de la propriété aà afficher
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
