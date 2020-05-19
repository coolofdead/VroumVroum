<?php

namespace App\Form;

use App\Entity\CategoriePlat;
use App\Entity\Plat;
use App\Entity\TypePlat;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlatNewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('prix')
            ->add('nom')
            ->add('urlImg',UrlType::class)
            ->add('categorie', EntityType::class,[
                'class' => CategoriePlat::class,
                'choice_label' => 'categorie',
                'multiple' => false,
//                'expanded' => true,
                'required' => true,
            ])
            ->add('type', EntityType::class,[
                'class' => TypePlat::class,
                'choice_label' => 'type',
                'multiple' => false,
//                'expanded' => true,
                'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Plat::class,
            'csrf_protection' => false,

        ]);
    }
}
