<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\Ad2Type;
use App\Repository\AdRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminAdController extends AbstractController
{
    /**
     * @Route("/admin/ads", name="admin_ads_index")
     */
    public function index(AdRepository $repository)
    {
        return $this->render('admin/ad/index.html.twig', [
            'ads' => $repository->findAll()
        ]);
    }

    /**
     * Permet d'afficher le formulaire d'edition
     * @Route("/admin/ads/{id}/edit",name="admin_ads_edit")
     * @param Ad $ad
     * @return Response
     */

    public function edit(Request $request,Ad $ad){

        $manager = $this->getDoctrine()->getManager();
        $form =$this->createForm(Ad2Type::class,$ad);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($ad);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'annonce <strong>{$ad->getTitle()}</strong> a bien été enregistrer avec success"
            );
        }
        return $this->render('admin/ad/edit.html.twig',[
            'ad' => $ad,
            'form' => $form->createView()
        ]);
    }

    /**
     * permet de supprimer une annonce
     * @Route("/admin/ads/{id}/delete" , name="admin_ads_delete")
     *
     * @param Ad $ad
     * @return void
     */

    public function delete(Ad $ad){
    $manager = $this->getDoctrine()->getManager();
    if (count($ad->getBookings()) > 0){
        $this->addFlash(
            'warning',
            "vous ne pouvez pas supprimer l'annonce <strong>{$ad->getTitle()}</strong> car elle possède deja des reservation"
        );
    }else{
        $manager->remove($ad);
        $manager->flush();

        $this->addFlash(
            'success',
            "l'annonce <strong>{$ad->getTitle()}</strong> a bien été supprimée !!"
        );
    }

    return $this->redirectToRoute('admin_ads_index');
    }
}
