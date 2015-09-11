<?php

namespace MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use MainBundle\Entity\Tank;
use UserBundle\Entity\User;

/**
 * Inventory
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="MainBundle\Entity\InventoryRepository")
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
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="product", type="string", length=255)
     */
    private $product;

    /**
     * @var float
     * @Assert\NotBlank()
     * @ORM\Column(name="vacuum", type="float")
     */
    private $vacuum;

    /**
     * @var float
     * @Assert\NotBlank()
     * @ORM\Column(name="liter", type="float")
     */
    private $liter;
    

    /**
     * @ORM\ManyToOne(targetEntity="Tank", inversedBy="inventories")
     * @ORM\JoinColumn(name="tank_id", referencedColumnName="id")
     **/
    private $tank;
    
    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="inventory")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     **/
    private $users;  


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
     * Set product
     *
     * @param string $product
     * @return Inventory
     */
    public function setProduct($product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return string 
     */
    public function getProduct()
    {
        return $this->product;
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
}
