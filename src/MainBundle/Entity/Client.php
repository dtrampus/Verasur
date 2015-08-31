<?php

namespace MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use MainBundle\Entity\Egress;

/**
 * Client
 *
 * @ORM\Table(name="clients")
 * @ORM\Entity(repositoryClass="MainBundle\Entity\ClientRepository")
 * @UniqueEntity("code")
 */
class Client
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
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;
    
    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 2,
     *      minMessage = "El campo debe llevar minimo {{ limit }} caracteres"
     * )
     * @ORM\Column(name="delivery", type="string", length=255)
     */
    private $delivery;
    
    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 2,
     *      minMessage = "El campo debe llevar minimo {{ limit }} caracteres"
     * )
     * @ORM\Column(name="contact", type="string", length=255)
     */
    private $contact;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;
    
    /**
     * @ORM\OneToMany(targetEntity="Egress", mappedBy="client")
     */
    private $egresses;

    public function __toString() {
        return $this->code.' - '.$this->nombre;
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
     * @return Client
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
     * Set nombre
     *
     * @param string $nombre
     * @return Client
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }
    
    /**
     * Set delivery
     *
     * @param string $delivery
     * @return Client
     */
    public function setDelivery($delivery)
    {
        $this->delivery = $delivery;

        return $this;
    }

    /**
     * Get delivery
     *
     * @return string 
     */
    public function getDelivery()
    {
        return $this->delivery;
    }
    
    /**
     * Set contact
     *
     * @param string $contact
     * @return Client
     */
    public function setContact($contact)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Get contact
     *
     * @return string 
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return Client
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
        $this->egresses = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add egresses
     *
     * @param \MainBundle\Entity\Egress $egresses
     * @return Client
     */
    public function addEgress(\MainBundle\Entity\Egress $egresses)
    {
        $this->egresses[] = $egresses;

        return $this;
    }

    /**
     * Remove egresses
     *
     * @param \MainBundle\Entity\Egress $egresses
     */
    public function removeEgress(\MainBundle\Entity\Egress $egresses)
    {
        $this->egresses->removeElement($egresses);
    }

    /**
     * Get egresses
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEgresses()
    {
        return $this->egresses;
    }
}
