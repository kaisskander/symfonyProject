<?php

namespace App\Controller;

use App\Entity\PasswordUpdate;
use App\Form\PasswordUpdateType;
use App\Entity\User;
use App\Form\AccountType;
use App\Form\RegistrationType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Form\FormTypeInterface;

class AccountController extends AbstractController
{
    /**
     * @Route("/login", name="account_login")
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername() ;

        return $this->render('account/login.html.twig',[
            'hasError' => $error !==null,
            'username' => $username
        ]);
    }

    /**
     * permet de se deconnnecté
     * @Route("/logout", name="account_logout")
     */
    public function logout(){

    }

    /**
     * permet d'afficher le formulaire d'inscription
     * @Route("/register", name="account_register")
     * @return Response
     */
    public function register(Request $request,UserPasswordEncoderInterface $encoder){
        $user = new User();
        $manager = $this->getDoctrine()->getManager();
        $form= $this->createForm(RegistrationType::class,$user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $hash = $encoder->encodePassword($user,$user->getHash());
            $user->setHash($hash);
            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success','Votre compte est validé § Vous pouvez maintenant vous connecter!!!');
            return $this->redirectToRoute('account_login');
        }


        return $this->render('account/registration.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    /**
     * Permet d'afficher et de traiter le formulaire de modification de profil
     * @Route("/account/profile",name="account_profile")
     * @return Response
     */

    public function profile(Request $request){
        $user = $this->getUser();
        $form = $this->createForm(AccountType::class,$user);
        $manager = $this->getDoctrine()->getManager();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $manager->persist($user);
            $manager->flush();
            $this->addFlash(
                'success','les donnnees sont modifié avec success'
            );
        }

        return $this->render('account/profile.html.twig',[
            'form' => $form->createView()
        ]);
    }


    /**
     * modifer password
     * @Route("/account/password-update" , name="account_password")
     * @return Response
     */

    public function updatePassword(Request $request,UserPasswordEncoderInterface $encoder) {
             $passwordUpdate = new PasswordUpdate();
             $manager = $this->getDoctrine()->getManager();
             $user = $this->getUser();
             $form = $this->createForm(PasswordUpdateType::class,$passwordUpdate);
             $form->handleRequest($request);
                if($form->isSubmitted() && $form->isValid())
                {   //verifier que le oldpassword du formulaire soit le meme que le password de l'user
                    if (!password_verify($passwordUpdate->getOldPassword(),$user->getHash()))
                    {
                        //gerer l'erreur
                        $form->get('oldPassword')->addError(new FormError("Le mot de passe que vous avez tapé n'est pas votre mot de passe actuel"));

                    }else{
                        $newPassword = $passwordUpdate->getNewPassword();
                        $hash = $encoder->encodePassword($user,$newPassword);
                        $user->setHash($hash);
                        $manager->persist($user);
                        $manager->flush();

                        $this->addFlash(
                            'success',
                            'Password est modifié avec success'
                        );

                        return $this->redirectToRoute('homepage');

                    }

                }
        return $this->render('account/password.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * permet d'afficher le profil de l'utilisateur connecté
     * @Route("/account" ,name="account_index")
     */

    public function myAccount()
    {
        return $this->render('user/index.html.twig',[
            'user' =>$this->getUser()
        ]);
    }

}
