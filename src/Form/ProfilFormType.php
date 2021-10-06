<?php

namespace App\Form;

use App\Entity\Sites;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfilFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pseudo', TextType::class, [
                'required'=>true,
                'invalid_message' =>  'Le champ est obligatoire.'
                ])
            ->add('nom', TextType::class, [
                'required'=>true,
                'invalid_message' =>  'Le champ est obligatoire.'
                ])
            ->add('prenom', TextType::class, [
                'required'=>true,
                'invalid_message' =>  'Le champ est obligatoire.'
                ])
            ->add('telephone', TelType::class, [
                'required'=>false,
            ])
            ->add('noSite', EntityType::class, array(
                'class'         => Sites::class,
                'required'      =>true,
                'choice_label'  =>function($site){
                    return $site->getNomSite();
                }
            ))
            ->add('email', EmailType::class, [
                'required'=>true,
                'invalid_message' =>  'Le champ est obligatoire.'
                ])
            ->add('password', RepeatedType::class, array(
                'type'              => PasswordType::class,
                'required'          =>true,
                'mapped'            => true,
                'first_options'     => array('label' => 'Mot de passe :'),
                'second_options'    => array('label' => 'Confirmer le mot de passe :'),
                'invalid_message'   => 'Veuilez mettre un mot de passe identique',
            ))
        ;
        
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
