<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\AdminBookingType;
use App\Repository\BookingRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminBookingController extends AbstractController
{
    /**
     * @Route("/admin/bookings", name="admin_bookings_index")
     */
    public function index(BookingRepository $repo)
    {
        $bookings = $repo->findAll();
        return $this->render('admin/booking/index.html.twig',[
            'bookings' => $bookings
        ]);
    }

    /**
     * Permet d editer une reservation
     * @Route("/admin/bookings/{id}/edit",name="admin_booking_edit")
     * @return Response
     */

    public function edit(Booking $booking,Request $request){
        $manager = $this->getDoctrine()->getManager();

        //pour spécifier les validation qui nous interessent
        //   'validation_groups' => ["default" , "front"]
        $form =$this->createForm(AdminBookingType::class,$booking);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $booking->setAmount(0);
            $manager->persist($booking);
            $manager->flush();
            $this->addFlash(
                'success',
                "la reservation n{$booking->getId()} a bien été modifier"
            );
            return $this->redirectToRoute("admin_bookings_index");
        }


        return $this->render('admin/booking/edit.html.twig',[
            'form' => $form->createView(),
            'booking' => $booking
        ]);

    }

    /**
     * @Route("/admin/bookings/{id}/delete",name="admin_booking_delete")
     * @return Response
     */

    public function delete(Booking $booking){
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($booking);
        $manager->flush();

        $this->addFlash(
            'success',
            "l'annonce a été supprimer"
        );

        return  $this->redirectToRoute('admin_bookings_index');

    }
}
