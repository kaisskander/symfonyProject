<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Booking;
use App\Form\BookingType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
     */
    public function show(Booking $booking){
        return $this->render('booking/show.html.twig',[
            'booking'=>$booking
        ]);
    }
}
