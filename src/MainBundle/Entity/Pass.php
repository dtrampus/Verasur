<?php

namespace MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use MainBundle\Entity\Movement;

/**
 * Pass
 *
 * @ORM\Table(name="passes")
 * @ORM\Entity(repositoryClass="MainBundle\Entity\PassRepository")
 */
class Pass extends Movement
{
    
}
