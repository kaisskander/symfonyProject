<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\BookingRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Booking
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $booker;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Ad", inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ad;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\GreaterThan("today",message="date must become after this day",  groups={"front"})
     *
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\GreaterThan(propertyPath="startDate",message="date must be far from startdate seriously gosh")
     *
     */
    private $endDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function prePersist()
    {
        if(empty($this->createdAt))
        {
            $this->createdAt = new \DateTime();
        }

        if(empty($this->amount)){
            //prix de l'annonce * nbr de jour
            $this->amount = $this->ad->getPrice()*$this->getDuration();
        }
    }


    public function getDuration()
    {
        $diff = $this->endDate->diff($this->startDate);
        return $diff->days;
    }

    public function isBookableDates()
    {
        //il faut connaitre les dates qui sont impossible pour l'annonce
        $notAvailableDays = $this->ad->getNotAvailableDays();
        //il faut comparer les dates choisies avec les dates impossible
        $bookingDays      = $this->getDays();
        //tableau des chaines de carateres de mes journées

        $formatDay = function ($day){
            return $day->format('Y-m-d');
        };

        $days             =  array_map($formatDay,$bookingDays);
        $notAvailable     =  array_map($formatDay,$notAvailableDays);

        foreach ($days as $day){
            if (array_search($day,$notAvailable) !== false) return false;
        }

            return true;
    }

    /**
     * Permet de récupere un tableau des journées qui correspondent à ma reservation
     * @return array un tableau qui represente les jours des reservation
     */

    public function getDays()
    {
        $resultat = range(
            $this->startDate->getTimestamp(),
            $this->endDate->getTimestamp(),
            24*60*60
        );
        $days = array_map(function ($dayTimestamp){
            return new \DateTime(date('Y-m-d',$dayTimestamp));
        },$resultat);

        return $days;

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBooker(): ?User
    {
        return $this->booker;
    }

    public function setBooker(?User $booker): self
    {
        $this->booker = $booker;

        return $this;
    }

    public function getAd(): ?Ad
    {
        return $this->ad;
    }

    public function setAd(?Ad $ad): self
    {
        $this->ad = $ad;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }
}
