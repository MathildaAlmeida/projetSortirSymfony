<?php

namespace App\Form;

use App\Entity\Lieux;
use App\Entity\Villes;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddLieuxType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('noVille', EntityType::class,[
                'label'=>'Ville :',
                'class'=> Villes::class,
                'choice_label' => function($Ville){
                    return $Ville->getNomVille();
                }
            ])
            ->add('nomLieu', TextType::class,[
                'label'=>'Nom :'
            ])
            ->add('rue', TextType::class,[
                'label' => 'Rue :',
                'required'=>false
            ])
            ->add('latitude',IntegerType::class,[
                'label'=>'latitude :',
                'required'=>false
            ])
            ->add('longitude', IntegerType::class,[
                'label'=> 'longitude :',
                'required'=>false
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Lieux::class,
        ]);
    }
}
