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
}
