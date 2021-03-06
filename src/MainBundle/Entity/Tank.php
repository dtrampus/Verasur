<?php

namespace MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use MainBundle\Entity\Product;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use MainBundle\Entity\MovementDetail;
use MainBundle\Entity\Inventory;

/**
 * Tank
 *
 * @ORM\Table(name="tanks")
 * @ORM\Entity(repositoryClass="MainBundle\Entity\TankRepository")
 * @UniqueEntity("code")
 */
class Tank {

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
     * @Assert\NotBlank()
     * @ORM\Column(name="code", type="string", length=255)
     */
    private $code;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="description", type="string")
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
     * @ORM\Column(name="circumference", type="decimal", nullable=true)
     */
    private $circumference;

    /**
     * @var float
     *
     * @ORM\Column(name="reference", type="float", nullable=true)
     */
    private $reference;

    /**
     * @var float
     *
     * @ORM\Column(name="coordinates", type="float", nullable=true)
     */
    private $coordinates = '0';

    /**
     * @var float
     *
     * @ORM\Column(name="diameter", type="float", nullable=true)
     */
    private $diameter;

    /**
     * @var float
     *
     * @ORM\Column(name="liter", type="float", nullable=true)
     */
    private $liter;

    /**
     * @var float
     * @Assert\NotBlank()
     * @ORM\Column(name="total_capacity", type="float")
     */
    private $totalCapacity;

    /**
     * @ORM\ManyToMany(targetEntity="Product", inversedBy="tanks")
     * @ORM\JoinTable(name="tanks_products")
     * @Assert\NotBlank()
     * 
     */
    protected $products;
    
    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255)
     */
    private $status;  
    
    /**
     * @var string
     *
     * @ORM\Column(name="reason", type="text", nullable=true)
     */
    private $reason;

    /**
     * @ORM\OneToMany(targetEntity="MovementDetail", mappedBy="tank", cascade={"persist"})
     */
    protected $movementDetails;

    /**
     * @ORM\OneToMany(targetEntity="Inventory", mappedBy="tank", cascade={"persist"})
     */
    protected $inventories;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set vertical
     *
     * @param boolean $plant
     * @return Tank
     */
    public function setPlant($plant) {
        $this->plant = $plant;

        return $this;
    }

    /**
     * Get plant
     *
     * @return boolean 
     */
    public function getPlant() {
        return $this->plant;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return Tank
     */
    public function setCode($code) {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode() {
        return $this->code;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Tank
     */
    public function setDescription($description) {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Set vertical
     *
     * @param boolean $vertical
     * @return Tank
     */
    public function setVertical($vertical) {
        $this->vertical = $vertical;

        return $this;
    }

    /**
     * Get vertical
     *
     * @return boolean 
     */
    public function getVertical() {
        return $this->vertical;
    }

    /**
     * Set circumference
     *
     * @param float $circumference
     * @return Tank
     */
    public function setCircumference($circumference) {
        $this->circumference = $circumference;

        return $this;
    }

    /**
     * Get circumference
     *
     * @return float 
     */
    public function getCircumference() {
        return $this->circumference;
    }

    /**
     * Set reference
     *
     * @param float $reference
     * @return Tank
     */
    public function setReference($reference) {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference
     *
     * @return float 
     */
    public function getReference() {
        return $this->reference;
    }

    /**
     * Set coordinates
     *
     * @param float $coordinates
     * @return Tank
     */
    public function setCoordinates($coordinates) {
        $this->coordinates = $coordinates;

        return $this;
    }

    /**
     * Get coordinates
     *
     * @return float 
     */
    public function getCoordinates() {
        return $this->coordinates;
    }

    /**
     * Set diameter
     *
     * @param float $diameter
     * @return Tank
     */
    public function setDiameter($diameter) {
        $this->diameter = $diameter;

        return $this;
    }

    /**
     * Get diameter
     *
     * @return float 
     */
    public function getDiameter() {
        return $this->diameter;
    }

    /**
     * Set liter
     *
     * @param float $liter
     * @return Tank
     */
    public function setLiter($liter) {
        $this->liter = $liter;

        return $this;
    }

    /**
     * Get liter
     *
     * @return float 
     */
    public function getLiter() {
        return $this->liter;
    }

    /**
     * Set totalCapacity
     *
     * @param float $totalCapacity
     * @return Tank
     */
    public function setTotalCapacity($totalCapacity) {
        $this->totalCapacity = $totalCapacity;

        return $this;
    }

    /**
     * Get totalCapacity
     *
     * @return float 
     */
    public function getTotalCapacity() {
        return $this->totalCapacity;
    }

    /**
     * Add movementDetails
     *
     * @param \MainBundle\Entity\MovementDetail $movementDetails
     * @return Tank
     */
    public function addMovementDetail(\MainBundle\Entity\MovementDetail $movementDetails) {
        $this->movementDetails[] = $movementDetails;

        return $this;
    }

    /**
     * Remove movementDetails
     *
     * @param \MainBundle\Entity\MovementDetail $movementDetails
     */
    public function removeMovementDetail(\MainBundle\Entity\MovementDetail $movementDetails) {
        $this->movementDetails->removeElement($movementDetails);
    }

    /**
     * Get movementDetails
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMovementDetails() {
        return $this->movementDetails;
    }

    public function __toString() {
        return $this->code . ' - ' . $this->description;
    }

    /**
     * Add inventories
     *
     * @param \MainBundle\Entity\Inventory $inventories
     * @return Tank
     */
    public function addInventory(\MainBundle\Entity\Inventory $inventories) {
        $this->inventories[] = $inventories;

        return $this;
    }

    /**
     * Remove inventories
     *
     * @param \MainBundle\Entity\Inventory $inventories
     */
    public function removeInventory(\MainBundle\Entity\Inventory $inventories) {
        $this->inventories->removeElement($inventories);
    }

    /**
     * Get inventories
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getInventories() {
        return $this->inventories;
    }

    /**
     * Add products
     *
     * @param \MainBundle\Entity\Product $products
     * @return Tank
     */
    public function addProduct(\MainBundle\Entity\Product $products) {
        $this->products[] = $products;

        return $this;
    }

    /**
     * Remove products
     *
     * @param \MainBundle\Entity\Product $products
     */
    public function removeProduct(\MainBundle\Entity\Product $products) {
        $this->products->removeElement($products);
    }

    /**
     * Get products
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProducts() {
        return $this->products;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->products = new \Doctrine\Common\Collections\ArrayCollection();
        $this->movementDetails = new \Doctrine\Common\Collections\ArrayCollection();
        $this->inventories = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Set status
     *
     * @param string $status
     * @return Provider
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }
    
        /**
     * Set reason
     *
     * @param string $reason
     * @return Movement
     */
    public function setReason($reason)
    {
        $this->reason = $reason;

        return $this;
    }

    /**
     * Get reason
     *
     * @return string 
     */
    public function getReason()
    {
        return $this->reason;
    }
}
