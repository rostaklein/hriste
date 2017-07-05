<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reservations
 *
 * @ORM\Table(name="reservations", indexes={@ORM\Index(name="field", columns={"field"}), @ORM\Index(name="reservation", columns={"reservation"})})
 * @ORM\Entity
 */
class Reservations
{

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=false)
     */
    private $date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="timeFrom", type="time", nullable=false)
     */
    private $timefrom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="timeTo", type="time", nullable=false)
     */
    private $timeto;


    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;


    /**
     * @var \AppBundle\Entity\Fields
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Fields")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="field", referencedColumnName="id")
     * })
     */
    private $field;

    /**
     * @var \AppBundle\Entity\Reservation
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Reservation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="reservation", referencedColumnName="id")
     * })
     */
    private $reservation;


    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Reservations
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set timefrom
     *
     * @param \DateTime $timefrom
     *
     * @return Reservations
     */
    public function setTimefrom($timefrom)
    {
        $this->timefrom = $timefrom;

        return $this;
    }

    /**
     * Get timefrom
     *
     * @return \DateTime
     */
    public function getTimefrom()
    {
        return $this->timefrom;
    }

    /**
     * Set timeto
     *
     * @param \DateTime $timeto
     *
     * @return Reservations
     */
    public function setTimeto($timeto)
    {
        $this->timeto = $timeto;

        return $this;
    }

    /**
     * Get timeto
     *
     * @return \DateTime
     */
    public function getTimeto()
    {
        return $this->timeto;
    }

    /**
     * Set confirmed
     *
     * @param boolean $confirmed
     *
     * @return Reservations
     */
    public function setConfirmed($confirmed)
    {
        $this->confirmed = $confirmed;

        return $this;
    }

    /**
     * Get confirmed
     *
     * @return boolean
     */
    public function getConfirmed()
    {
        return $this->confirmed;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }



    /**
     * Set field
     *
     * @param \AppBundle\Entity\Fields $field
     *
     * @return Reservations
     */
    public function setField(\AppBundle\Entity\Fields $field = null)
    {
        $this->field = $field;

        return $this;
    }

    /**
     * Get field
     *
     * @return \AppBundle\Entity\Fields
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * Set reservation
     *
     * @param \AppBundle\Entity\Reservation $reservation
     *
     * @return Reservations
     */
    public function setReservation(\AppBundle\Entity\Reservation $reservation = null)
    {
        $this->reservation = $reservation;

        return $this;
    }

    /**
     * Get reservation
     *
     * @return \AppBundle\Entity\Reservation
     */
    public function getReservation()
    {
        return $this->reservation;
    }
}
