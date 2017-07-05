<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Fields
 *
 * @ORM\Table(name="fields")
 * @ORM\Entity
 */
class Fields
{
    /**
     * @var string
     *
     * @ORM\Column(name="uname", type="string", length=10, nullable=false)
     */
    private $uname;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="typ", type="string", length=10, nullable=false)
     */
    private $typ;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="price", type="integer")
     */
    private $price;



    /**
     * Set uname
     *
     * @param string $uname
     *
     * @return Fields
     */
    public function setUname($uname)
    {
        $this->uname = $uname;

        return $this;
    }

    /**
     * Get uname
     *
     * @return string
     */
    public function getUname()
    {
        return $this->uname;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Fields
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set typ
     *
     * @param string $typ
     *
     * @return Fields
     */
    public function setTyp($typ)
    {
        $this->typ = $typ;

        return $this;
    }

    /**
     * Get typ
     *
     * @return string
     */
    public function getTyp()
    {
        return $this->typ;
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
}
