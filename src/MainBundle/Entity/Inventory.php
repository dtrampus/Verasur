<?php

namespace MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use MainBundle\Entity\Tank;

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
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="user", type="string", length=255)
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="product", type="string", length=255)
     */
    private $product;

    /**
     * @var float
     *
     * @ORM\Column(name="vacuum", type="float")
     */
    private $vacuum;

    /**
     * @var float
     *
     * @ORM\Column(name="liter", type="float")
     */
    private $liter;
    

    /**
     * @ORM\ManyToOne(targetEntity="Tank", inversedBy="inventories")
     * @ORM\JoinColumn(name="tank_id", referencedColumnName="id")
     **/
    private $tank;  


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
     * Set user
     *
     * @param string $user
     * @return Inventory
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return string 
     */
    public function getUser()
    {
        return $this->user;
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

}
