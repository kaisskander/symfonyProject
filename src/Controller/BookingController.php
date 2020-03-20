<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Booking;
use App\Entity\Comment;
use App\Form\BookingType;
use App\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class BookingController extends AbstractController
{
    /**
     * @Route("/ads/{slug}/book", name="booking_create")
     * @IsGranted("ROLE_USER",message="ACCES DENIED")
     */
    public function book(Ad $ad,Request $request)
    {
        $booking = new Booking();
        $form =  $this->createForm(BookingType::class,$booking);
        $manager = $this->getDoctrine()->getManager();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {   $user = $this->getUser();

            $booking->setBooker($user)
                    ->setAd($ad);
            //si les date ne sont pas disponibles ,message d'erreur
            if(!$booking->isBookableDates()){
                $this->addFlash(
                    'warning',"les dates que vous avez choisi ne peuvent etre reservee :elles sont deja prise"
                );
            }else{
                //sionon enregistrement et redirection
                $manager->persist($booking);
                $manager->flush();
                return $this->redirectToRoute('booking_show',['id'=>$booking->getId(),'withAlert'=>true]);
            }


        }

        return $this->render('booking/book.html.twig', [
            'ad' => $ad,
            'form' => $form->createView()
        ]);
    }

    /**
     * permet d'afficher la page d'une reservation
     * @Route("/booking/{id}",name="booking_show")
     * @param Booking $booking
     * @return Response
     */
    public function show(Booking $booking,Request $request){
        $comment = new Comment();
        $manager = $this->getDoctrine()->getManager();

        $form = $this->createForm(CommentType::class,$comment);

        $form->handleRequest($request);

          if ($form->isSubmitted() && $form->isValid()){
                $comment->setAd($booking->getAd())
                        ->setAuthor($this->getUser());

                $manager->persist($comment);
                $manager->flush();

                $this->addFlash('success',"votre commentaire a bien été pris en compte");

          }


            return $this->render('booking/show.html.twig',[
                'booking'=>$booking,
                'form'   => $form->createView()
            ]);
    }
}
