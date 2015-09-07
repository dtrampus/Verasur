<?php

namespace MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use MainBundle\Entity\Transport;
use MainBundle\Entity\Product;
use Symfony\Component\Validator\Constraints as Assert;
use MainBundle\Entity\Pass;
use MainBundle\Entity\Egress;
use MainBundle\Entity\Ingress;
use MainBundle\Entity\MovementDetail;

/**
 * @ORM\Entity
 * @ORM\Table(name="movements")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"egress" = "Egress", "ingress" = "Ingress", "passes" = "Pass"})
 */
abstract class Movement
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
     * @Assert\NotBlank()
     * @ORM\Column(name="baln", type="string", length=255, nullable=true)
     */
    private $baln;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;
   
    
    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="truckDomain", type="string", length=255, nullable=true)
     */
    private $truckDomain;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="coupledDomain", type="string", length=255, nullable=true)
     */
    private $coupledDomain;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Transport", inversedBy="movements")
     * @ORM\JoinColumn(name="transport_id", referencedColumnName="id", nullable = true)
     * @Assert\NotBlank()
     * 
     */
    private $transport;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="driver", type="string", length=255, nullable=true)
     */
    private $driver;

    /**
     * @var float
     *
     * @ORM\Column(name="grossWeight", type="float", nullable=true)
     * @Assert\Regex(
     * pattern="/^\d{1,10}(\.\d{1,2})?$/", 
     * match=true,
     * message = "El campo solo admite numeros"
     * )
     * @Assert\NotBlank()
     */
    private $grossWeight;

    /**
     * @var float
     *
     * @ORM\Column(name="tareWeight", type="float", nullable=true)
     * @Assert\Regex(
     * pattern="/^\d{1,10}(\.\d{1,2})?$/", 
     * match=true,
     * message = "El campo solo admite numeros"
     * )
     * @Assert\NotBlank()
     */
    private $tareWeight;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="movements")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", nullable = true)
     * @Assert\NotBlank()
     * 
     */
    private $product;

    /**
     * @var float
     *
     * @ORM\Column(name="density", type="float", nullable=true)
     * @Assert\Regex(
     * pattern="/^\d{1,10}(\.\d{1,2})?$/", 
     * match=true,
     * message = "El campo solo admite numeros"
     * )
     * @Assert\NotBlank()
     */
    private $density;
    
    /**
     * @var boolean
     * @Assert\NotBlank()
     * @ORM\Column(name="tested", type="boolean", nullable=true)
     */
    private $tested;    

    /**
     * @var float
     *
     * @ORM\Column(name="clean", type="float", nullable=true)
     * @Assert\Regex(
     * pattern="/^\d{1,10}(\.\d{1,2})?$/", 
     * match=true,
     * message = "El campo solo admite numeros"
     * )
     * @Assert\NotBlank()
     */
    private $clean;

    /**
     * @var float
     *
     * @ORM\Column(name="realLiter", type="float", nullable=true)
     * @Assert\Regex(
     * pattern="/^\d{1,10}(\.\d{1,2})?$/", 
     * match=true,
     * message = "El campo solo admite numeros"
     * )
     * @Assert\NotBlank()
     */
    private $realLiter;

    /**
     * @var float
     *
     * @ORM\Column(name="branchNumber", type="float", nullable=true)
     * @Assert\Regex(
     * pattern="/^\d{1,10}(\.\d{1,2})?$/", 
     * match=true,
     * message = "El campo solo admite numeros"
     * )
     * @Assert\NotBlank()
     */
    private $branchNumber;

    /**
     * @var float
     *
     * @ORM\Column(name="remitNumber", type="float", nullable=true)
     * @Assert\Regex(
     * pattern="/^\d{1,10}(\.\d{1,2})?$/", 
     * match=true,
     * message = "El campo solo admite numeros"
     * )
     * @Assert\NotBlank()
     */
    private $remitNumber;
    
    /**
     * @var string
     *
     * @ORM\Column(name="observation", type="text", nullable=true)
     * @Assert\NotBlank()
     * @Assert\Length(min=3)
     */
    private $observation;
    
    /**
     *
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="movements")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable = true)
     * 
     */
    private $user;
    
    /**
     *
     * @ORM\OneToMany(targetEntity="MovementDetail", mappedBy="movement", cascade={"persist"})
     * 
     */
    private $movementDetails;
    
    /**
     * @var float
     *
     * @ORM\Column(name="distillationGout", type="float", nullable=true)
     * @Assert\Regex(
        * pattern="/^\d{1,3}(\.\d)?$/", 
        * match=true,
        * message = "El campo solo admite numeros"
    *   ))
     */
    private $distillationGout;
    
    /**
     * @var float
     *
     * @ORM\Column(name="fivePercent", type="float", nullable=true)
     * @Assert\Regex(
        * pattern="/^\d{1,3}(\.\d)?$/", 
        * match=true,
        * message = "El campo solo admite numeros"
    *   ))
     */
    private $fivePercent;
    
    /**
     * @var float
     *
     * @ORM\Column(name="tenPercent", type="float", nullable=true)
     * @Assert\Regex(
        * pattern="/^\d{1,3}(\.\d)?$/", 
        * match=true,
        * message = "El campo solo admite numeros"
    *   ))
     */
    private $tenPercent;
    
    /**
     * @var float
     *
     * @ORM\Column(name="twentyPercent", type="float", nullable=true)
     * @Assert\Regex(
        * pattern="/^\d{1,3}(\.\d)?$/", 
        * match=true,
        * message = "El campo solo admite numeros"
    *   ))
     */
    private $twentyPercent;
    
    /**
     * @var float
     *
     * @ORM\Column(name="thirtyPercent", type="float", nullable=true)
     * @Assert\Regex(
        * pattern="/^\d{1,3}(\.\d)?$/", 
        * match=true,
        * message = "El campo solo admite numeros"
    *   ))
     */
    private $thirtyPercent;
    
    /**
     * @var float
     *
     * @ORM\Column(name="fortyPercent", type="float", nullable=true)
     * @Assert\Regex(
        * pattern="/^\d{1,3}(\.\d)?$/", 
        * match=true,
        * message = "El campo solo admite numeros"
    *   ))
     */
    private $fortyPercent;
    
    /**
     * @var float
     *
     * @ORM\Column(name="fiftyPercent", type="float", nullable=true)
     * @Assert\Regex(
        * pattern="/^\d{1,3}(\.\d)?$/", 
        * match=true,
        * message = "El campo solo admite numeros"
    *   ))
     */
    private $fiftyPercent;
    
    /**
     * @var float
     *
     * @ORM\Column(name="sixtyPercent", type="float", nullable=true)
     * @Assert\Regex(
        * pattern="/^\d{1,3}(\.\d)?$/", 
        * match=true,
        * message = "El campo solo admite numeros"
    *   ))
     */
    private $sixtyPercent;
    
    /**
     * @var float
     *
     * @ORM\Column(name="seventyPercent", type="float", nullable=true)
     * @Assert\Regex(
        * pattern="/^\d{1,3}(\.\d)?$/", 
        * match=true,
        * message = "El campo solo admite numeros"
    *   ))
     */
    private $seventyPercent;
    
    /**
     * @var float
     *
     * @ORM\Column(name="eightyPercent", type="float", nullable=true)
     * @Assert\Regex(
        * pattern="/^\d{1,3}(\.\d)?$/", 
        * match=true,
        * message = "El campo solo admite numeros"
    *   ))
     */
    private $eightyPercent;
    
    /**
     * @var float
     *
     * @ORM\Column(name="ninetyPercent", type="float", nullable=true)
     * @Assert\Regex(
        * pattern="/^\d{1,3}(\.\d)?$/", 
        * match=true,
        * message = "El campo solo admite numeros"
    *   ))
     */
    private $ninetyPercent;
    
    /**
     * @var float
     *
     * @ORM\Column(name="ninetyFivePercent", type="float", nullable=true)
     * @Assert\Regex(
        * pattern="/^\d{1,3}(\.\d)?$/", 
        * match=true,
        * message = "El campo solo admite numeros"
    *   ))
     */
    private $ninetyFivePercent;
    
    /**
     * @var float
     *
     * @ORM\Column(name="pDry", type="float", nullable=true)
     * @Assert\Regex(
        * pattern="/^\d{1,3}(\.\d)?$/", 
        * match=true,
        * message = "El campo solo admite numeros"
    *   ))
     */
    private $pDry;
    
    /**
     * @var float
     *
     * @ORM\Column(name="pFinal", type="float", nullable=true)
     * @Assert\Regex(
        * pattern="/^\d{1,3}(\.\d)?$/", 
        * match=true,
        * message = "El campo solo admite numeros"
    *   ))
     */
    private $pFinal;
    
    /**
     * @var float
     *
     * @ORM\Column(name="recovered", type="float", nullable=true)
     * @Assert\Regex(
        * pattern="/^\d{1,3}(\.\d)?$/", 
        * match=true,
        * message = "El campo solo admite numeros"
    *   ))
     */
    private $recovered;
    
    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255, nullable=true)
     */
    private $status;
    
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
     * Set baln
     *
     * @param string $baln
     * @return Movement
     */
    public function setBaln($baln)
    {
        $this->baln = $baln;

        return $this;
    }

    /**
     * Get baln
     *
     * @return string 
     */
    public function getBaln()
    {
        return $this->baln;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Movement
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
     * Set truckDomain
     *
     * @param string $truckDomain
     * @return Movement
     */
    public function setTruckDomain($truckDomain)
    {
        $this->truckDomain = $truckDomain;

        return $this;
    }

    /**
     * Get truckDomain
     *
     * @return string 
     */
    public function getTruckDomain()
    {
        return $this->truckDomain;
    }

    /**
     * Set coupledDomain
     *
     * @param string $coupledDomain
     * @return Movement
     */
    public function setCoupledDomain($coupledDomain)
    {
        $this->coupledDomain = $coupledDomain;

        return $this;
    }

    /**
     * Get coupledDomain
     *
     * @return string 
     */
    public function getCoupledDomain()
    {
        return $this->coupledDomain;
    }

    /**
     * Set driver
     *
     * @param string $driver
     * @return Movement
     */
    public function setDriver($driver)
    {
        $this->driver = $driver;

        return $this;
    }

    /**
     * Get driver
     *
     * @return string 
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * Set grossWeight
     *
     * @param float $grossWeight
     * @return Movement
     */
    public function setGrossWeight($grossWeight)
    {
        $this->grossWeight = $grossWeight;

        return $this;
    }

    /**
     * Get grossWeight
     *
     * @return float 
     */
    public function getGrossWeight()
    {
        return $this->grossWeight;
    }

    /**
     * Set tareWeight
     *
     * @param float $tareWeight
     * @return Movement
     */
    public function setTareWeight($tareWeight)
    {
        $this->tareWeight = $tareWeight;

        return $this;
    }

    /**
     * Get tareWeight
     *
     * @return float 
     */
    public function getTareWeight()
    {
        return $this->tareWeight;
    }

    /**
     * Set density
     *
     * @param float $density
     * @return Movement
     */
    public function setDensity($density)
    {
        $this->density = $density;

        return $this;
    }

    /**
     * Get density
     *
     * @return float 
     */
    public function getDensity()
    {
        return $this->density;
    }
    
    /**
     * Set tested
     *
     * @param boolean $tested
     * @return Movement
     */
    public function setTested($tested)
    {
        $this->tested = $tested;

        return $this;
    }

    /**
     * Get tested
     *
     * @return boolean 
     */
    public function getTested()
    {
        return $this->tested;
    }    

    /**
     * Set clean
     *
     * @param float $clean
     * @return Movement
     */
    public function setClean($clean)
    {
        $this->clean = $clean;

        return $this;
    }

    /**
     * Get clean
     *
     * @return float 
     */
    public function getClean()
    {
        return $this->clean;
    }

    /**
     * Set realLiter
     *
     * @param float $realLiter
     * @return Movement
     */
    public function setRealLiter($realLiter)
    {
        $this->realLiter = $realLiter;

        return $this;
    }

    /**
     * Get realLiter
     *
     * @return float 
     */
    public function getRealLiter()
    {
        return $this->realLiter;
    }

    /**
     * Set branchNumber
     *
     * @param float $branchNumber
     * @return Movement
     */
    public function setBranchNumber($branchNumber)
    {
        $this->branchNumber = $branchNumber;

        return $this;
    }

    /**
     * Get branchNumber
     *
     * @return float 
     */
    public function getBranchNumber()
    {
        return $this->branchNumber;
    }

    /**
     * Set remitNumber
     *
     * @param float $remitNumber
     * @return Movement
     */
    public function setRemitNumber($remitNumber)
    {
        $this->remitNumber = $remitNumber;

        return $this;
    }

    /**
     * Get remitNumber
     *
     * @return float 
     */
    public function getRemitNumber()
    {
        return $this->remitNumber;
    }
    
    /**
     * Set observation
     *
     * @param string $observation
     * @return Movement
     */
    public function setObservation($observation)
    {
        $this->observation = $observation;

        return $this;
    }

    /**
     * Get observation
     *
     * @return string 
     */
    public function getObservation()
    {
        return $this->observation;
    }

    /**
     * Set transport
     *
     * @param \MainBundle\Entity\Transport $transport
     * @return Movement
     */
    public function setTransport(\MainBundle\Entity\Transport $transport = null)
    {
        $this->transport = $transport;

        return $this;
    }

    /**
     * Get transport
     *
     * @return \MainBundle\Entity\Transport 
     */
    public function getTransport()
    {
        return $this->transport;
    }

    /**
     * Set product
     *
     * @param \MainBundle\Entity\Product $product
     * @return Movement
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
    
    /**
     * Set user
     *
     * @param \UserBundle\Entity\User $user
     * @return Movement
     */
    public function setUser(\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }    
    
    
    //------------------------------------------------Getters y setters de muestra------------------------
    
    /**
     * Set distillationGout
     *
     * @param float $distillationGout
     * @return Movement
     */
    public function setDistillationGout($distillationGout)
    {
        $this->distillationGout = $distillationGout;

        return $this;
    }

    /**
     * Get distillationGout
     *
     * @return float 
     */
    public function getDistillationGout()
    {
        return $this->distillationGout;
    }
    
    
    /**
     * Set fivePercent
     *
     * @param float $fivePercent
     * @return Movement
     */
    public function setFivePercent($fivePercent)
    {
        $this->fivePercent = $fivePercent;

        return $this;
    }

    /**
     * Get fivePercent
     *
     * @return float 
     */
    public function getFivePercent()
    {
        return $this->fivePercent;
    }
    
    
    /**
     * Set tenPercent
     *
     * @param float $tenPercent
     * @return Movement
     */
    public function setTenPercent($tenPercent)
    {
        $this->tenPercent = $tenPercent;

        return $this;
    }

    /**
     * Get tenPercent
     *
     * @return float 
     */
    public function getTenPercent()
    {
        return $this->tenPercent;
    }
    
    /**
     * Set twentyPercent
     *
     * @param float $twentyPercent
     * @return Movement
     */
    public function setTwentyPercent($twentyPercent)
    {
        $this->twentyPercent = $twentyPercent;

        return $this;
    }

    /**
     * Get twentyPercent
     *
     * @return float 
     */
    public function getTwentyPercent()
    {
        return $this->twentyPercent;
    }
    
    /**
     * Set thirtyPercent
     *
     * @param float $thirtyPercent
     * @return Movement
     */
    public function setThirtyPercent($thirtyPercent)
    {
        $this->thirtyPercent = $thirtyPercent;

        return $this;
    }

    /**
     * Get thirtyPercent
     *
     * @return float 
     */
    public function getThirtyPercent()
    {
        return $this->thirtyPercent;
    }
    
    /**
     * Set fortyPercent
     *
     * @param float $fortyPercent
     * @return Movement
     */
    public function setFortyPercent($fortyPercent)
    {
        $this->fortyPercent = $fortyPercent;

        return $this;
    }

    /**
     * Get fortyPercent
     *
     * @return float 
     */
    public function getFortyPercent()
    {
        return $this->fortyPercent;
    }
    
    /**
     * Set fiftyPercent
     *
     * @param float $fiftyPercent
     * @return Movement
     */
    public function setFiftyPercent($fiftyPercent)
    {
        $this->fiftyPercent = $fiftyPercent;

        return $this;
    }

    /**
     * Get fiftyPercent
     *
     * @return float 
     */
    public function getFiftyPercent()
    {
        return $this->fiftyPercent;
    }
    
    /**
     * Set sixtyPercent
     *
     * @param float $sixtyPercent
     * @return Movement
     */
    public function setSixtyPercent($sixtyPercent)
    {
        $this->sixtyPercent = $sixtyPercent;

        return $this;
    }

    /**
     * Get sixtyPercent
     *
     * @return float 
     */
    public function getSixtyPercent()
    {
        return $this->sixtyPercent;
    }
    
    /**
     * Set seventyPercent
     *
     * @param float $seventyPercent
     * @return Movement
     */
    public function setSeventyPercent($seventyPercent)
    {
        $this->seventyPercent = $seventyPercent;

        return $this;
    }

    /**
     * Get seventyPercent
     *
     * @return float 
     */
    public function getSeventyPercent()
    {
        return $this->seventyPercent;
    }
    
    /**
     * Set eightyPercent
     *
     * @param float $eightyPercent
     * @return Movement
     */
    public function setEightyPercent($eightyPercent)
    {
        $this->eightyPercent = $eightyPercent;

        return $this;
    }

    /**
     * Get eightyPercent
     *
     * @return float 
     */
    public function getEightyPercent()
    {
        return $this->eightyPercent;
    }
    
    /**
     * Set ninetyPercent
     *
     * @param float $ninetyPercent
     * @return Movement
     */
    public function setNinetyPercent($ninetyPercent)
    {
        $this->ninetyPercent = $ninetyPercent;

        return $this;
    }

    /**
     * Get ninetyPercent
     *
     * @return float 
     */
    public function getNinetyPercent()
    {
        return $this->ninetyPercent;
    }
    
    /**
     * Set ninetyFivePercent
     *
     * @param float $ninetyFivePercent
     * @return Movement
     */
    public function setNinetyFivePercent($ninetyFivePercent)
    {
        $this->ninetyFivePercent = $ninetyFivePercent;

        return $this;
    }

    /**
     * Get ninetyFivePercent
     *
     * @return float 
     */
    public function getNinetyFivePercent()
    {
        return $this->ninetyFivePercent;
    }
    
    /**
     * Set recovered
     *
     * @param float $recovered
     * @return Movement
     */
    public function setRecovered($recovered)
    {
        $this->recovered = $recovered;

        return $this;
    }

    /**
     * Get recovered
     *
     * @return float 
     */
    public function getRecovered()
    {
        return $this->recovered;
    }    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->movementDetails = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add movementDetails
     *
     * @param \MainBundle\Entity\MovementDetail $movementDetails
     * @return Movement
     */
    public function addMovementDetail(\MainBundle\Entity\MovementDetail $movementDetails)
    {
        $this->movementDetails[] = $movementDetails;

        return $this;
    }

    /**
     * Remove movementDetails
     *
     * @param \MainBundle\Entity\MovementDetail $movementDetails
     */
    public function removeMovementDetail(\MainBundle\Entity\MovementDetail $movementDetails)
    {
        $this->movementDetails->removeElement($movementDetails);
    }

    /**
     * Get movementDetails
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMovementDetails()
    {
        return $this->movementDetails;
    }
    
    /**
     * Set status
     *
     * @param string $status
     * @return Movement
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
     * Set pDry
     *
     * @param float $pDry
     * @return Movement
     */
    public function setPDry($pDry)
    {
        $this->pDry = $pDry;

        return $this;
    }

    /**
     * Get pDry
     *
     * @return float 
     */
    public function getPDry()
    {
        return $this->pDry;
    }

    /**
     * Set pFinal
     *
     * @param float $pFinal
     * @return Movement
     */
    public function setPFinal($pFinal)
    {
        $this->pFinal = $pFinal;

        return $this;
    }

    /**
     * Get pFinal
     *
     * @return float 
     */
    public function getPFinal()
    {
        return $this->pFinal;
    }
}
