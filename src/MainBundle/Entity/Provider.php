<?php

namespace MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use MainBundle\Entity\Ingress;

/**
 * Provider
 *
 * @ORM\Table(name="providers")
 * @ORM\Entity(repositoryClass="MainBundle\Entity\ProviderRepository")
 * @UniqueEntity("code")
 */
class Provider
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
     * @ORM\Column(name="code", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min=3)
     */
    private $name;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;
    
    /*
     * @ORM\OneToMany(targetEntity="Ingress", mappedBy="provider")
     */
    private $ingresess;

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
     * @return Provider
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
     * Set name
     *
     * @param string $name
     * @return Provider
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
     * Constructor
     */
    public function __construct()
    {
        $this->ingresess = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add ingresess
     *
     * @param \MainBundle\Entity\Ingress $ingresess
     * @return Provider
     */
    public function addIngresess(\MainBundle\Entity\Ingress $ingresess)
    {
        $this->ingresess[] = $ingresess;

        return $this;
    }

    /**
     * Remove ingresess
     *
     * @param \MainBundle\Entity\Ingress $ingresess
     */
    public function removeIngresess(\MainBundle\Entity\Ingress $ingresess)
    {
        $this->ingresess->removeElement($ingresess);
    }

    /**
     * Get ingresess
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIngresess()
    {
        return $this->ingresess;
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return Provider
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
}