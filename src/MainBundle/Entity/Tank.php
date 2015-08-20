<?php

namespace MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use MainBundle\Entity\Product;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Tank
 *
 * @ORM\Table(name="tanks")
 * @ORM\Entity(repositoryClass="MainBundle\Entity\TankRepository")
 * @UniqueEntity("code")
 */
class Tank
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
     * @var boolean
     *
     * @ORM\Column(name="plant", type="boolean")
     */
    private $plant;
    
    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=255)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var boolean
     *
     * @ORM\Column(name="vertical", type="boolean")
     */
    private $vertical;

    /**
     * @var float
     *
     * @ORM\Column(name="circumference", type="decimal")
     */
    private $circumference;

    /**
     * @var float
     *
     * @ORM\Column(name="reference", type="float")
     */
    private $reference;

    /**
     * @var float
     *
     * @ORM\Column(name="coordinates", type="float")
     */
    private $coordinates;

    /**
     * @var float
     *
     * @ORM\Column(name="diameter", type="float")
     */
    private $diameter;

    /**
     * @var float
     *
     * @ORM\Column(name="liter", type="float")
     */
    private $liter;

    /**
     * @var float
     *
     * @ORM\Column(name="total_capacity", type="float")
     */
    private $totalCapacity;

    /**
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="tanks")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     * @Assert\NotBlank()
     * 
     */
    private $product;

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
     * Set vertical
     *
     * @param boolean $plant
     * @return Tank
     */
    public function setPlant($plant)
    {
        $this->plant = $plant;

        return $this;
    }

    /**
     * Get plant
     *
     * @return boolean 
     */
    public function getPlant()
    {
        return $this->plant;
    }
    
    /**
     * Set code
     *
     * @param string $code
     * @return Tank
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
     * Set description
     *
     * @param string $description
     * @return Tank
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set vertical
     *
     * @param boolean $vertical
     * @return Tank
     */
    public function setVertical($vertical)
    {
        $this->vertical = $vertical;

        return $this;
    }

    /**
     * Get vertical
     *
     * @return boolean 
     */
    public function getVertical()
    {
        return $this->vertical;
    }

    /**
     * Set circumference
     *
     * @param float $circumference
     * @return Tank
     */
    public function setCircumference($circumference)
    {
        $this->circumference = $circumference;

        return $this;
    }

    /**
     * Get circumference
     *
     * @return float 
     */
    public function getCircumference()
    {
        return $this->circumference;
    }

    /**
     * Set reference
     *
     * @param float $reference
     * @return Tank
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference
     *
     * @return float 
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Set coordinates
     *
     * @param float $coordinates
     * @return Tank
     */
    public function setCoordinates($coordinates)
    {
        $this->coordinates = $coordinates;

        return $this;
    }

    /**
     * Get coordinates
     *
     * @return float 
     */
    public function getCoordinates()
    {
        return $this->coordinates;
    }

    /**
     * Set diameter
     *
     * @param float $diameter
     * @return Tank
     */
    public function setDiameter($diameter)
    {
        $this->diameter = $diameter;

        return $this;
    }

    /**
     * Get diameter
     *
     * @return float 
     */
    public function getDiameter()
    {
        return $this->diameter;
    }

    /**
     * Set liter
     *
     * @param float $liter
     * @return Tank
     */
    public function setLiter($liter)
    {
        $this->liter = $liter;

        return $this;
    }

    /**
     * Get liter
     *
     * @return float 
     */
    public function getLiter()
    {
        return $this->liter;
    }

    /**
     * Set totalCapacity
     *
     * @param float $totalCapacity
     * @return Tank
     */
    public function setTotalCapacity($totalCapacity)
    {
        $this->totalCapacity = $totalCapacity;

        return $this;
    }

    /**
     * Get totalCapacity
     *
     * @return float 
     */
    public function getTotalCapacity()
    {
        return $this->totalCapacity;
    }

    /**
     * Set product
     *
     * @param \MainBundle\Entity\Product $product
     * @return Tank
     */
    public function setProduct(\MainBundle\Entity\Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \MainBundle\Entity\Product 
     */
    public function getProduct()
    {
        return $this->product;
    }
}
