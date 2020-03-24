<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Image;
use App\Form\Ad2Type;
use App\Form\AdType;
use App\Repository\AdRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class AdController extends AbstractController
{
    /**
     * @Route("/ads", name="ads_index")
     */
    public function index(AdRepository $repo)
    {

        $ads =$repo->findAll();

        return $this->render('ad/index.html.twig', [
            'controller_name' => 'AdController',
            'ads' => $ads
        ]);
    }

    /**
     *permet de creer une annonce
     * @Route("ads/new" , name="ads_create")
     *
     */
   /* public function create(Request $request){
        $ad = new Ad();

        $form = $this->createForm(AdType::class,$ad);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($ad);
            $manager->flush();
        }
        return $this->render('ad/new.html.twig',[
            'form' => $form->createView()
        ]);
    }*/

    /**
     *permet de creer une annonce
     * @Route("ads/new2" , name="ads_create2")
     * @IsGranted("ROLE_USER")
     *
     */
    public function create2(Request $request,EntityManagerInterface $manager){
        $ad = new Ad();
        $form = $this->createForm(Ad2Type::class,$ad);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            foreach ($ad->getImages() as $image){
                $image->setAd($ad);
                $manager->persist($image);
            }
            $ad->setAuthor($this->getUser());
            $manager->persist($ad);
            $manager->flush();
            $this->addFlash('success',"l'annonce<strong>{{ad.title}}</strong>a bien été enregistrer!!!");

            return $this->redirectToRoute('ads_show',[
                'slug' => $ad->getSlug()
            ]);
        }
        return $this->render('ad/new2.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * permet de modifer le formulaire
     * @Route("/ads/{slug}/edit", name="ads_edit")
     * @Security("is_granted('ROLE_USER') and user === ad.getAuthor()", message="cette annonce ne vous appartient pas vous nous pouvez pas le modifier")
     * @return Response
     */

    public function edit(Ad $ad,Request $request,EntityManagerInterface $manager){


        $form = $this->createForm(Ad2Type::class,$ad);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            foreach ($ad->getImages() as $image){
                $image->setAd($ad);
                $manager->persist($image);
            }

            $manager->persist($ad);
            $manager->flush();
            $this->addFlash('success',"Modification bien enregistrer!!");

            return $this->redirectToRoute('ads_show',[
                'slug' => $ad->getSlug()
            ]);
        }

        return  $this->render('ad/edit.html.twig', [
            'form' => $form->createView(),
            'ad' => $ad
        ]);
    }


    /**
     *Permet d'afficher une seule annonce
     * @Route("/ads/{slug}" ,name ="ads_show")
     *
     */

    public function show(Ad $ad){

        return $this->render('ad/show.html.twig',[
            'ad' => $ad
        ]);
    }
}
