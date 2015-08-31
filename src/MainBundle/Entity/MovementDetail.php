<?php

namespace MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use MainBundle\Entity\Tank;
use MainBundle\Entity\Movement;

/**
 * MovementDetail
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="MainBundle\Entity\MovementDetailRepository")
 */
class MovementDetail {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Tank", inversedBy="movementDetails")
     * @ORM\JoinColumn(name="tank_id", referencedColumnName="id", nullable = false)
     */
    private $tank;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Movement", inversedBy="movementDetails")
     * @ORM\JoinColumn(name="movement_id", referencedColumnName="id", nullable = false)
     * 
     */
    private $movement;

    /**
     * @var float
     *
     * @ORM\Column(name="quantity", type="float")
     */
    private $quantity;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set quantity
     *
     * @param float $quantity
     * @return MovementDetail
     */
    public function setQuantity($quantity) {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return float 
     */
    public function getQuantity() {
        return $this->quantity;
    }

    /**
     * Set tank
     *
     * @param \MainBundle\Entity\Tank $tank
     * @return MovementDetail
     */
    public function setTank(\MainBundle\Entity\Tank $tank) {
        $this->tank = $tank;

        return $this;
    }

    /**
     * Get tank
     *
     * @return \MainBundle\Entity\Tank 
     */
    public function getTank() {
        return $this->tank;
    }


    /**
     * Set movement
     *
     * @param \MainBundle\Entity\Movement $movement
     * @return MovementDetail
     */
    public function setMovement(\MainBundle\Entity\Movement $movement)
    {
        $this->movement = $movement;

        return $this;
    }

    /**
     * Get movement
     *
     * @return \MainBundle\Entity\Movement 
     */
    public function getMovement()
    {
        return $this->movement;
    }
}
