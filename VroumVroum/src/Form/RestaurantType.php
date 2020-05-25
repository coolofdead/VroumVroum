<?php

namespace App\Form;

use App\Entity\CategoriePlat;
use App\Entity\CategorieRestaurant;
use App\Entity\Restaurant;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RestaurantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('longitude', NumberType::class, ['attr' => ['class' => 'form-control form-control-alternative ']])
            ->add('latitude',NumberType::class, ['attr' => ['class' => 'form-control form-control-alternative ']])
            ->add('nom',TextType::class, ['attr' => ['class' => 'form-control']])
            ->add('adresse',TextType::class ,['attr' => ['class' => 'form-control form-control-alternative ']])
            ->add('email',EmailType::class ,['attr' => ['class' => 'form-control form-control-alternative ']])
            ->add('url',UrlType::class, ['attr' => ['class' => 'form-control form-control-alternative ']])
            ->add('categorie', EntityType::class,[
//                'attr' => ['class' => 'form-check-input'],
                'class' => CategorieRestaurant::class,
                'choice_label' => 'Categorie',
                // used to render a select box, check boxes or radios
                 'multiple' => false,
                 'expanded' => true,
                 'required' => true,
            ])
            ->add('id', NumberType::class, ['required'=>true])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Restaurant::class,
            'csrf_protection' => false,
        ]);
    }
}
