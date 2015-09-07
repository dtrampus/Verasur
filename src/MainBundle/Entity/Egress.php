<?php

namespace MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use MainBundle\Entity\Client;
use MainBundle\Entity\Movement;

/**
 * Egress
 *
 * @ORM\Table(name="egresess")
 * @ORM\Entity(repositoryClass="MainBundle\Entity\EgressRepository")
 */
class Egress extends Movement
{
    /**
     *
     * @ORM\ManyToOne(targetEntity="Client", inversedBy="egresses")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id", nullable = false)
     * @Assert\NotBlank()
     * 
     */
    private $client;

    /**
     * Set client
     *
     * @param \MainBundle\Entity\Client $client
     * @return Egress
     */
    public function setClient(\MainBundle\Entity\Client $client = null)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return \MainBundle\Entity\Client 
     */
    public function getClient()
    {
        return $this->client;
    }
    
     public function getExportLine(){
        $array = array();
        $array[0]=$this->getBaln();
        $array[1]=$this->getDate()->format('d/m/Y');
        $array[2]=$this->getClient();
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
