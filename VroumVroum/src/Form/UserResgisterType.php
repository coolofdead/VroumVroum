<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\CallbackTransformer;


class UserResgisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email',EmailType::class)
            ->add('roles',ChoiceType::class, [
                'choices' => [
                    'Utilisateur' => 'ROLE_USER',
                    'Restaurateur' => 'ROLE_RESTAURATEUR',
                ],
                'expanded' => false,
                'multiple' => false,
                'label' => 'Rôles'
            ])

            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'required' => true,
                'options' => ['attr' => ['class' => 'password-field']],
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => false],
            ])
            ->add('nom', TextType::class)
            ->add('prenom', TextType::class)
            ->add('Inscription', SubmitType::class, ['attr' => ['class' => 'subscribe btn btn-success btn-lg btn-block ']])
        ;

        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($tagsAsArray) {
                    // transform the array to a string
                    return implode(', ', $tagsAsArray);
                },
                function ($tagsAsString) {
                    // transform the string back to an array
                    return explode(', ', $tagsAsString);
                }
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'csrf_protection' => false,

        ]);
    }
}
