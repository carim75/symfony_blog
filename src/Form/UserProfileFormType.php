<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class)
            ->add('plainPassword', RepeatedType::class, [
                //o a crée un champs qui n'est pas lié a l'entité User
                'mapped' => false,
                'required' => false,
                'type' => PasswordType::class,
                'invalid_message' => 'les mots de passe ne correspondent pas.',
                'constraints' => [
                    new Length([
                        'min' => 8,
                        'minMessage' => 'le mot de passe doit contenir 8 carractere',
                        'max' => 4096
                    ])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
           'data_class' =>User::class
        ]);
    }
}
