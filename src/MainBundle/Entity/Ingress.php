<?php

namespace MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use MainBundle\Entity\Provider;
use MainBundle\Entity\Movement;

/**
 * Ingress
 *
 * @ORM\Table(name="ingresses")
 * @ORM\Entity(repositoryClass="MainBundle\Entity\IngressRepository")
 */
class Ingress extends Movement
{
    /**
     *
     * @ORM\ManyToOne(targetEntity="Provider", inversedBy="ingresess")
     * @ORM\JoinColumn(name="provider_id", referencedColumnName="id", nullable = false)
     * @Assert\NotBlank()
     * 
     */
    private $provider;

    /**
     * Set provider
     *
     * @param \MainBundle\Entity\Provider $provider
     * @return Ingress
     */
    public function setProvider(\MainBundle\Entity\Provider $provider = null)
    {
        $this->provider = $provider;

        return $this;
    }

    /**
     * Get provider
     *
     * @return \MainBundle\Entity\Provider 
     */
    public function getProvider()
    {
        return $this->provider;
    }
    
    public function getExportLine2(){
        $array = array();
        $array[0]=$this->getBaln();
        $array[1]=$this->getDate()->format('d/m/Y');
        $array[2]=$this->getProvider();
        $array[3]=$this->getTruckDomain();
        $array[4]=$this->getCoupledDomain();
        $array[5]=$this->getTransport();
        $array[6]=$this->getDriver();
        $array[7]=$this->getGrossWeight();
        $array[8]=$this->getTareWeight();
        $array[9]=$this->getProduct();
        $array[10]=$this->getDensity();
        $array[11]=$this->getClean();
        $array[12]=$this->getRealLiter();
        $array[13]=$this->getBranchNumber().' - '.$this->getRemitNumber();
        
        return $array;
    }
    
}
