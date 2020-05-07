<?php

namespace App\Form;

use App\Entity\CategoriePlat;
use App\Entity\CategorieRestaurant;
use App\Entity\Restaurant;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RestaurantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('longitude')
            ->add('latitude')
            ->add('nom')
            ->add('adresse')
            ->add('url')
            ->add('categorie', EntityType::class,[
                'class' => CategorieRestaurant::class,
                'choice_label' => 'Categorie',
                // used to render a select box, check boxes or radios
                 'multiple' => false,
                 'expanded' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Restaurant::class,
        ]);
    }
}
