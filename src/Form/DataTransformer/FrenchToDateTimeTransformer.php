<?php


namespace  App\Form\DataTransformer ;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class FrenchToDateTimeTransformer implements DataTransformerInterface{


    public function transform($date)
    {
        if ($date===null){
            return '';
        }
        return $date->format('d/m/Y');
    }

    public function reverseTransform($frenchDate)
    {
        // french date 21/02/2020
        if ($frenchDate===null){
            //exeception
            throw new TransformationFailedException("vous devez fournir une date");
        }

        $date = \DateTime::createFromFormat('d/m/Y',$frenchDate);

        if ($date === false){
            //execption
            throw new TransformationFailedException("le format de la date n'est pas bon!!!");
        }
        return $date;
    }
}