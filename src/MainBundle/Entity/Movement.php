<?php

namespace MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use MainBundle\Entity\Transport;
use MainBundle\Entity\Product;
use Symfony\Component\Validator\Constraints as Assert;
use MainBundle\Entity\Egress;
use MainBundle\Entity\Ingress;

/**
 * @ORM\Entity
 * @ORM\Table(name="movements")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"egress" = "Egress", "ingress" = "Ingress"})
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
     *
     * @ORM\Column(name="baln", type="string", length=255)
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
     *
     * @ORM\Column(name="truckDomain", type="string", length=255)
     */
    private $truckDomain;

    /**
     * @var string
     *
     * @ORM\Column(name="coupledDomain", type="string", length=255)
     */
    private $coupledDomain;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Transport", inversedBy="movements")
     * @ORM\JoinColumn(name="transport_id", referencedColumnName="id", nullable = false)
     * @Assert\NotBlank()
     * 
     */
    private $transport;

    /**
     * @var string
     *
     * @ORM\Column(name="driver", type="string", length=255)
     */
    private $driver;

    /**
     * @var float
     *
     * @ORM\Column(name="grossWeight", type="float")
     * @Assert\Regex(
     * pattern="/^\d{1,10}(\.\d{1,2})?$/", 
     * match=true,
     * message = "El campo solo admite numeros"
     * )
     */
    private $grossWeight;

    /**
     * @var float
     *
     * @ORM\Column(name="tareWeight", type="float")
     * @Assert\Regex(
     * pattern="/^\d{1,10}(\.\d{1,2})?$/", 
     * match=true,
     * message = "El campo solo admite numeros"
     * )
     */
    private $tareWeight;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="movements")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", nullable = false)
     * @Assert\NotBlank()
     * 
     */
    private $product;

    /**
     * @var float
     *
     * @ORM\Column(name="density", type="float")
     * @Assert\Regex(
     * pattern="/^\d{1,10}(\.\d{1,2})?$/", 
     * match=true,
     * message = "El campo solo admite numeros"
     * )
     */
    private $density;

    /**
     * @var float
     *
     * @ORM\Column(name="clean", type="float")
     * @Assert\Regex(
     * pattern="/^\d{1,10}(\.\d{1,2})?$/", 
     * match=true,
     * message = "El campo solo admite numeros"
     * )
     */
    private $clean;

    /**
     * @var float
     *
     * @ORM\Column(name="realLiter", type="float")
     * @Assert\Regex(
     * pattern="/^\d{1,10}(\.\d{1,2})?$/", 
     * match=true,
     * message = "El campo solo admite numeros"
     * )
     */
    private $realLiter;

    /**
     * @var float
     *
     * @ORM\Column(name="branchNumber", type="float")
     * @Assert\Regex(
     * pattern="/^\d{1,10}(\.\d{1,2})?$/", 
     * match=true,
     * message = "El campo solo admite numeros"
     * )
     */
    private $branchNumber;

    /**
     * @var float
     *
     * @ORM\Column(name="remitNumber", type="float")
     * @Assert\Regex(
     * pattern="/^\d{1,10}(\.\d{1,2})?$/", 
     * match=true,
     * message = "El campo solo admite numeros"
     * )
     */
    private $remitNumber;
    
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
}
