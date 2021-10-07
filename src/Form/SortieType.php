<?php

namespace App\Form;

use App\Entity\Lieux;
use App\Entity\Sorties;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class,[
                'label' => 'Nom : '
            ])
            ->add('dateDebut', DateType::class,[
                'label' => 'Date début : ',
                'widget' => 'single_text'
            ])

            ->add('dateCloture',DateType::class,[
                'label' => 'Date cloture : ',
                'widget' => 'single_text'

            ])
            ->add('duree', IntegerType::class,[
                'label' => 'Durée : '
            ])
            ->add('nbInscriptionMax',IntegerType::class,[
                'label' => 'Nombre de place : '
            ])
            ->add('descriptionInfos',TextareaType::class,[
                'label' => 'Description : '
                ])

            ->add('noLieu', EntityType::class, array(
                'class'=> Lieux::class,
                'label'=> 'lieux : ',
                'choice_label'  =>function($organisateur){
                    return $organisateur->getNomLieu();
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sorties::class,
        ]);
    }
}
