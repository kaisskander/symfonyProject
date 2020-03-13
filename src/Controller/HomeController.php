<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController{

    /**
    * @Route("/hello/{prenom}/age/{age}", name="hello")
     * @Route("/hello",name ="hello_base")
     * @Route("/hello/{prenom}", name="hello_prenom")
     */
    public function hello($prenom  = null,$age = null){
        return  $this->render('hello.html.twig',[
            'prenom' => $prenom,
            'age' => $age
        ]);

    }





    /**
    * @Route("/",name="homepage")
     */
    public function home(){
        $prenoms =["lior" => 31 ,"Kias" =>24 ,"omar"=>55];
        return $this->render(
            'home.html.twig',
            [
                'title' =>"Bonjour à tous" ,
                'age' => 31,
                'tableau' => $prenoms
            ]
        );
    }

}


?>