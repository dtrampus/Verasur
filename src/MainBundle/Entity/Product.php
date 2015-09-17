<?php

namespace MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use MainBundle\Entity\Tank;
use MainBundle\Entity\Movement;
use MainBundle\Entity\Inventory;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Product
 *
 * @ORM\Table(name="products")
 * @ORM\Entity(repositoryClass="MainBundle\Entity\ProductRepository")
 * @UniqueEntity("code")
 */
class Product
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
     * @ORM\Column(name="description", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min=2)
     */
    private $description;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * @ORM\ManyToMany(targetEntity="Tank", mappedBy="products")
     */
    private $tanks;
    
    /**
     * @ORM\OneToMany(targetEntity="Movement", mappedBy="transport")
     */
    private $movements;
    
    /**
     * @ORM\OneToMany(targetEntity="Inventory", mappedBy="product")
     */
    protected $inventory;
    
    public function __construct()
    {
        $this->tanks = new ArrayCollection();
        $this->inventory = new ArrayCollection();
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
     * @return Product
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
     * @return Product
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
     * Add movements
     *
     * @param \MainBundle\Entity\Movement $movements
     * @return Product
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
    
    /*
     * Set active
     *
     * @param boolean $active
     * @return Product
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
     * Add inventory
     *
     * @param \MainBundle\Entity\Inventory $inventory
     * @return Product
     */
    public function addInventory(\MainBundle\Entity\Inventory $inventory)
    {
        $this->inventory[] = $inventory;

        return $this;
    }

    /**
     * Remove inventory
     *
     * @param \MainBundle\Entity\Inventory $inventory
     */
    public function removeInventory(\MainBundle\Entity\Inventory $inventory)
    {
        $this->inventory->removeElement($inventory);
    }

    /**
     * Get inventory
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getInventory()
    {
        return $this->inventory;
    }

    /**
     * Add tanks
     *
     * @param \MainBundle\Entity\Tank $tanks
     * @return Product
     */
    public function addTank(\MainBundle\Entity\Tank $tanks)
    {
        $this->tanks[] = $tanks;

        return $this;
    }

    /**
     * Remove tanks
     *
     * @param \MainBundle\Entity\Tank $tanks
     */
    public function removeTank(\MainBundle\Entity\Tank $tanks)
    {
        $this->tanks->removeElement($tanks);
    }

    /**
     * Get tanks
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTanks()
    {
        return $this->tanks;
    }
    
    public function __toString() {
    return $this->code . ' - ' . $this->description;;
}
}
