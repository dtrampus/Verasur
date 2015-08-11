<?php

namespace MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use MainBundle\Entity\Movement;

/**
 * Transport
 *
 * @ORM\Table(name="transports")
 * @ORM\Entity(repositoryClass="MainBundle\Entity\TransportRepository")
 * @UniqueEntity("code")
 */
class Transport
{
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
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="code", type="string", length=255, unique=true)
     */
    private $code;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 2,
     *      minMessage = "El campo debe llevar minimo {{ limit }} caracteres"
     * )
     * @ORM\Column(name="transport", type="string", length=255)
     */
    private $transport;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;
    
    /**
     * @ORM\OneToMany(targetEntity="Movement", mappedBy="transport")
     */
    private $movements;

     public function __toString() {
        return $this->code.' - '.$this->transport;
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
     * Set code
     *
     * @param string $code
     * @return Transport
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
     * Set transport
     *
     * @param string $transport
     * @return Transport
     */
    public function setTransport($transport)
    {
        $this->transport = $transport;

        return $this;
    }

    /**
     * Get transport
     *
     * @return string 
     */
    public function getTransport()
    {
        return $this->transport;
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return Transport
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean 
     */
    public function getActive()
    {
        return $this->active;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->movements = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add movements
     *
     * @param \MainBundle\Entity\Movement $movements
     * @return Transport
     */
    public function addMovement(\MainBundle\Entity\Movement $movements)
    {
        $this->movements[] = $movements;

        return $this;
    }

    /**
     * Remove movements
     *
     * @param \MainBundle\Entity\Movement $movements
     */
    public function removeMovement(\MainBundle\Entity\Movement $movements)
    {
        $this->movements->removeElement($movements);
    }

    /**
     * Get movements
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMovements()
    {
        return $this->movements;
    }
    
    public function __toString() {
        return $this->code.' - '.$this->transport;
    }
}
