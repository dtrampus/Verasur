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
       $array[11]=$this->getClean();
       $array[12]=$this->getRealLiter();
       $array[13]=$this->getBranchNumber().' - '.$this->getRemitNumber();
       
       return $array;
   }
}
