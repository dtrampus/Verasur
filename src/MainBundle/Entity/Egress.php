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
}
