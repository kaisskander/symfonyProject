<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\Booking;
use App\Entity\Image;
use App\Entity\Role;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder ;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }


    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        //ajouter un role admin a la table roles
        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);

        $adminUser = new User();
        $adminUser->setFirstName('kais')
                    ->setLastName('skander')
                    ->setEmail('kaisska119988@gmail.com')
                    ->setHash($this->encoder->encodePassword($adminUser,'password'))
                    ->setPicture('https://randomuser.me/api/portraits/men/2.jpg')
                    ->setIntroduction($faker->sentence())
                    ->setDescription('<p>' . join('</p><p>',$faker->paragraphs(3)).'</p>')
                    ->addUserRole($adminRole);
        $manager->persist($adminUser);


        $users=[];

        $genres = ['male','female'];



        //nos gerons les utilisateur
        for ($k = 1 ;$k <= 10 ;$k++){
            $user = new User();
            $genre = $faker->randomElement($genres);

           $picture =  'https://randomuser.me/api/portraits/';
           $pictureId = $faker->numberBetween(1,99) . '.jpg' ;

            $picture .=  ($genre == 'male' ? 'men/' : 'women/') .$pictureId;
            $hash = $this->encoder->encodePassword($user,'password');

            $user->setFirstName($faker->firstname($genre))
                  ->setLastName($faker->lastname)
                  ->setEmail($faker->email)
                  ->setIntroduction($faker->sentence())
                  ->setDescription('<p>' . join('</p><p>',$faker->paragraphs(3)).'</p>')
                  ->setHash($hash)
                  ->setPicture($picture);

                $manager->persist($user);
                $users[] = $user;
        }



        //nous gerons les annonces
        for($i=1 ;$i<=30 ;$i++){
        $ad = new  Ad();

        $user = $users[mt_rand(0,count($users)-1)];

        $ad->setTitle($faker->sentence())
            ->setCoverImage($faker->imageUrl(1000,350))
            ->setIntroduction($faker->paragraph(2))
            ->setContent('<p>' . join('</p><p>',$faker->paragraphs(5)).'</p>')
            ->setPrice(mt_rand(40,200))
            ->setRooms(mt_rand(1,5))
            ->setAuthor($user);

            for ($j =1 ; $j <= mt_rand(2,5); $j++)
            {
                $image = new Image();
                $image->setUrl($faker->imageUrl())
                     ->setCaption($faker->sentence())
                        ->setAd($ad);
                $manager->persist($image);
            }

            //gestion de reservation

            for($j=1; $j<= mt_rand(0,10);$j++ )
            { $booking = new Booking();

              $createdAt = $faker->dateTimeBetween('-6 months');
              $startDate = $faker->dateTimeBetween('-3 months');
              //Gestion de la date fin
              $duration  = mt_rand(3,10);
              $endDate   = (clone $startDate)->modify("+$duration days");
              $amount    =  $ad->getPrice()*$duration;

              $booker    = $users[mt_rand(0,count($users)-1)];

              $comment   = $faker->paragraph();

              $booking->setBooker($booker)
                      ->setAd($ad)
                      ->setStartDate($startDate)
                      ->setEndDate($endDate)
                      ->setCreatedAt($createdAt)
                      ->setAmount($amount)
                      ->setComment($comment)
                  ;
                    $manager->persist($booking);



            }


         $manager->persist($ad);
        }
        $manager->flush();
    }
}
