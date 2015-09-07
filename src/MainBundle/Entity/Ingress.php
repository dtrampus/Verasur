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
        $array[11]=$this->getTested();
        $array[12]=$this->getClean();
        $array[13]=$this->getRealLiter();
        $array[14]=$this->getBranchNumber().' - '.$this->getRemitNumber();
        $array[15]=$this->getObservation();
        $array[16]=$this->getUser();
        $array[17]=$this->getDistillationGout();
        $array[18]=$this->getFivePercent();
        $array[19]=$this->getTenPercent();
        $array[20]=$this->getTwentyPercent();
        $array[21]=$this->getThirtyPercent();
        $array[22]=$this->getFortyPercent();
        $array[23]=$this->getFiftyPercent();
        $array[24]=$this->getSixtyPercent();
        $array[25]=$this->getSeventyPercent();
        $array[26]=$this->getEightyPercent();
        $array[27]=$this->getNinetyPercent();
        $array[28]=$this->getNinetyFivePercent();
        $array[29]=$this->getpDry();
        $array[30]=$this->getpFinal();
        $array[31]=$this->getRecovered();
        
        
        return $array;
    }
    
}
