<?php

namespace MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use MainBundle\Entity\Tank;
use MainBundle\Entity\Product;

/**
 * Inventory
 *
 * @ORM\Table(name="inventories")
 * @ORM\Entity(repositoryClass="MainBundle\Entity\InventoryRepository")
 * @UniqueEntity("date")
 */
class Inventory
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
     * @var \DateTime
     * @Assert\NotBlank()
     * @ORM\Column(name="date", type="datetime", unique=true)
     */
    private $date;

    /**
     * @var float
     * @ORM\Column(name="vacuum", type="float", nullable=true)
     */
    private $vacuum;

    /**
     * @var float
     * @Assert\NotBlank()
     * @ORM\Column(name="liter", type="float")
     */
    private $liter;
    
        /**
     * @var string
     * @ORM\Column(name="observation", type="text", nullable=true)
     */
    private $observation;
    
    /**
     * @ORM\ManyToOne(targetEntity="Tank", inversedBy="inventories", cascade={"persist"})
     * @ORM\JoinColumn(name="tank_id", referencedColumnName="id", onDelete="CASCADE")
     **/
    protected $tank;
    
    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="inventory")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     **/
    private $users;  
    
    /**
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="inventory")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     **/
    protected $product;  

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
     * Set date
     *
     * @param \DateTime $date
     * @return Inventory
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
     * Set vacuum
     *
     * @param float $vacuum
     * @return Inventory
     */
    public function setVacuum($vacuum)
    {
        $this->vacuum = $vacuum;

        return $this;
    }

    /**
     * Get vacuum
     *
     * @return float 
     */
    public function getVacuum()
    {
        return $this->vacuum;
    }

    /**
     * Set liter
     *
     * @param float $liter
     * @return Inventory
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
     * Set observation
     *
     * @param string $observation
     * @return Inventory
     */
    public function setObservation($observation)
    {
        $this->observation = $observation;
        return $this;
    }

    /**
     * Get observation
     * @return string
     */
    public function getObservation()
    {
        return $this->observation;
    }
    
    /**
     * Set tank
     *
     * @param \MainBundle\Entity\Tank $tank
     * @return Inventory
     */
    public function setTank(\MainBundle\Entity\Tank $tank = null)
    {
        $this->tank = $tank;

        return $this;
    }

    /**
     * Get tank
     *
     * @return \MainBundle\Entity\Tank 
     */
    public function getTank()
    {
        return $this->tank;
    }

    /**
     * Set users
     *
     * @param \UserBundle\Entity\User $users
     * @return Inventory
     */
    public function setUsers(\UserBundle\Entity\User $users = null)
    {
        $this->users = $users;

        return $this;
    }

    /**
     * Get users
     *
     * @return \UserBundle\Entity\User 
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Set product
     *
     * @param \MainBundle\Entity\Product $product
     * @return Inventory
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
