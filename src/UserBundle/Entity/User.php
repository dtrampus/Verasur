<?php
// src/UserBundle/Entity/User.php

namespace UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use MainBundle\Entity\Movement;
use MainBundle\Entity\Inventory;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 * @UniqueEntity("username")
 * @UniqueEntity("email")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $firstname;
    
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $lastname;

    /**
     * @ORM\ManyToMany(targetEntity="UserBundle\Entity\Group")
     * @ORM\JoinTable(name="users_groups",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    protected $groups;
    
    /**
     * @ORM\OneToMany(targetEntity="MainBundle\Entity\Movement", mappedBy="users")
     */
    protected $movement;
    
    /**
     * @ORM\OneToMany(targetEntity="MainBundle\Entity\Inventory", mappedBy="inventory")
     */
    protected $inventory;
    
    
    public function __construct()
    {
        parent::__construct();
        $this->groups = new ArrayCollection();
        $this->movement = new ArrayCollection();
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
     * Set firstname
     *
     * @param string $firstname
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string 
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string 
     */
    public function getLastname()
    {
        return $this->lastname;
    }
    
    /**
     * Add movement
     *
     * @param \MainBundle\Entity\Movement $movement
     * @return User
     */
    public function addMovement(\MainBundle\Entity\Movement $movement)
    {
        $this->movement[] = $movement;

        return $this;
    }

    /**
     * Remove movement
     *
     * @param \MainBundle\Entity\Movement $movement
     */
    public function removeMovement(\MainBundle\Entity\Movement $movement)
    {
        $this->movement->removeElement($movement);
    }

    /**
     * Get movement
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMovement()
    {
        return $this->movement;
    }

    /**
     * Add inventory
     *
     * @param \MainBundle\Entity\Inventory $inventory
     * @return User
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
}
