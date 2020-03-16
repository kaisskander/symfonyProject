<?php

namespace App\Form;

use App\Entity\Ad;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdType extends AbstractType
{
    private function getConfiguration($label , $placeholder){
        return [
            'label' => $label,
            'attr'=> [
                'placeholder' => $placeholder
            ]
        ] ;
    }



    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',TextType::class , $this->getConfiguration("Titre" , "Tapez un super titre pour votre anonce"))
            ->add('slug',TextType::class,$this->getConfiguration("Adresse web","Tapez l'adresse web automatique"))
            ->add('coverImage',UrlType::class,$this->getConfiguration("Url de l'image principale","Donnez l'adresse d'une image"))
            ->add('introduction',TextType::class,$this->getConfiguration("Introduction","Donnez une description globale de l'annonce"))
            ->add('content',TextareaType::class,$this->getConfiguration('Content',"contenu"))
            ->add('rooms',IntegerType::class,$this->getConfiguration("Nombre de chambre","le nombre de chambre disponible"))
            ->add('price',MoneyType::class,$this->getConfiguration("Prix par nuit","indiquer le prix que vous voulez pour une nuit"))

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
