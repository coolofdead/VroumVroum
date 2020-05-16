<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, ['attr' => ['class' => 'form-control form-control-alternative ', 'id'=> '']])
            ->add('nom', TextType::class, ['attr' => ['class' => 'form-control form-control-alternative ', 'id'=> 'input-last-name']])
            ->add('prenom', TextType::class, ['attr' => ['class' => 'form-control form-control-alternative', 'id'=> 'input-first-name']])
            ->add('adresse', TextType::class, ['attr' => ['class' => 'form-control form-control-alternative ', 'id'=> 'input-address']])
            ->add('ville', TextType::class, ['attr' => ['class' => 'form-control form-control-alternative ', 'id'=> 'input-city']])
            ->add('pays', TextType::class, ['attr' => ['class' => 'form-control form-control-alternative', 'id'=> 'input-country']])
            ->add('codePostal', IntegerType::class, ['attr' => ['class' => 'form-control form-control-alternative', 'id'=> 'input-postal-code']])
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
