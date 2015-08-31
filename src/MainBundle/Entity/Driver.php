<?php

namespace MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use MainBundle\Entity\Transport;

/**
 * Driver
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="MainBundle\Entity\DriverRepository")
 * @UniqueEntity("dni")
 */
class Driver {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="surname", type="string", length=255)
     */
    private $surname;

    /**
     * @var integer
     * @Assert\NotBlank()
     * @ORM\Column(name="dni", type="integer", unique=true)
     */
    private $dni;

    /**
     * @var string
     * @ORM\Column(name="phone", type="string")
     */
    private $phone;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Transport", inversedBy="drivers")
     * @ORM\JoinColumn(name="transport_id", referencedColumnName="id", nullable = false)
     * @Assert\NotBlank()
     * 
     */
    private $transport;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Driver
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set surname
     *
     * @param string $surname
     * @return Driver
     */
    public function setSurname($surname) {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Get surname
     *
     * @return string 
     */
    public function getSurname() {
        return $this->surname;
    }

    /**
     * Set dni
     *
     * @param integer $dni
     * @return Driver
     */
    public function setDni($dni) {
        $this->dni = $dni;

        return $this;
    }

    /**
     * Get dni
     *
     * @return integer 
     */
    public function getDni() {
        return $this->dni;
    }

    /**
     * Set phone
     *
     * @param integer $phone
     * @return Driver
     */
    public function setPhone($phone) {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return integer 
     */
    public function getPhone() {
        return $this->phone;
    }

    /**
     * Set transport
     *
     * @param \MainBundle\Entity\Transport $transport
     * @return Movement
     */
    public function setTransport(\MainBundle\Entity\Transport $transport = null)
    {
        $this->transport = $transport;

        return $this;
    }

    /**
     * Get transport
     *
     * @return \MainBundle\Entity\Transport 
     */
    public function getTransport()
    {
        return $this->transport;
    }
    
    public function __toString() {
        return $this->dni.' - '.$this->name.' '.$this->surname;
    }

}
