<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
            //nom du champ de formulaire correspondant
            //au nom de l'attribut dans l'entité category
                'name',
                //type de champ de formulaire : input type text
                TextType::class,
                //tableau d'option pour les champs de formulaire
                [

                    //contenu de la balise <label> du champ de formulaire
                    'label' => 'Nom',
                    //pour ajouter des attributs a la balise input
                    'attr' => [
                        'placeholder' => 'Nom de la categorie',

                    ]
                ]
            )
            ->add('description',
                TextareaType::class,

                [
                    'label' => 'Description',

                    // par déefaut, les champs ont l'attribut required
                    // on ajoute cette option pour l'enlever
                     'required' => false
                ]




            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
