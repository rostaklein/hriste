<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reservations
 *
 * @ORM\Entity(repositoryClass="AppBundle\Entity\ReservationRepository")
 * @ORM\Table(name="reservation", indexes={@ORM\Index(name="user", columns={"user"})})
 */
class Reservation
{
    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=20, nullable=false)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="confirmToken", type="string", length=256, nullable=false)
     */
    private $confirmToken;


    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \AppBundle\Entity\Users
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user", referencedColumnName="id")
     * })
     */
    private $user;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdTime", type="datetime", nullable=true)
     */
    private $createdTime;

    /**
 * @var \DateTime
 *
 * @ORM\Column(name="confirmedTime", type="datetime", nullable=true)
 */
    private $confirmedTime;

    /**
     * @var string
     *
     * @ORM\Column(name="onlyName", type="string", length=20, nullable=true)
     */
    private $onlyName;

    /**
     * @return string
     */
    public function getOnlyName()
    {
        return $this->onlyName;
    }

    /**
     * @param string $onlyName
     */
    public function setOnlyName($onlyName)
    {
        $this->onlyName = $onlyName;
    }



    /**
     * Set code
     *
     * @param string $code
     *
     * @return Reservation
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set confirmed
     *
     * @param boolean $confirmed
     *
     * @return Reservation
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
     * Set createdTime
     *
     * @param \DateTime $createdTime
     *
     * @return Reservation
     */
    public function setCreatedTime($createdTime)
    {
        $this->createdTime = $createdTime;

        return $this;
    }

    /**
     * Get createdTime
     *
     * @return \DateTime
     */
    public function getCreatedTime()
    {
        return $this->createdTime;
    }

    /**
     * Set confirmedTime
     *
     * @param \DateTime $confirmedTime
     *
     * @return Reservation
     */
    public function setConfirmedTime($confirmedTime)
    {
        $this->confirmedTime = $confirmedTime;

        return $this;
    }

    /**
     * Get confirmedTime
     *
     * @return \DateTime
     */
    public function getConfirmedTime()
    {
        return $this->confirmedTime;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\Users $user
     *
     * @return Reservation
     */
    public function setUser(\AppBundle\Entity\Users $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\Users
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set confirmToken
     *
     * @param string $confirmToken
     *
     * @return Reservation
     */
    public function setConfirmToken($confirmToken)
    {
        $this->confirmToken = $confirmToken;

        return $this;
    }

    /**
     * Get confirmToken
     *
     * @return string
     */
    public function getConfirmToken()
    {
        return $this->confirmToken;
    }
}
