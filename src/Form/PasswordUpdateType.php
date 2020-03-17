<?php

namespace App\Form;


use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;

class PasswordUpdateType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('oldPassword',PasswordType::class,$this->getConfiguration("Ancien Mdp","donnez votre Mdp Actuel..."))
            ->add('newPassword',PasswordType::class,$this->getConfiguration("Nouveau Mdp" ,"tapez votre Nouveau Mdp"))
            ->add('confirmPassword',PasswordType::class,$this->getConfiguration("Confirmation du mot de passe" ,"Confirmer votre nouveau Mdp"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
