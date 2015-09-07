<?php

namespace MainBundle\Entity;

use Doctrine\ORM\EntityRepository;
use MainBundle\Entity\MovementDetail;

/**
 * TankRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TankRepository extends EntityRepository
{
    public function calculateFreeOcuped(Tank $t) {
       $em = $this->getEntityManager();
       $tankCapacity = $t->getTotalCapacity();
       $tankId = $t->getId();

       $dql = "SELECT SUM(t.quantity) AS total FROM MainBundle:MovementDetail t " .
              "WHERE t.tank = ?1";
       $calculateTotal = (float)$em->createQuery($dql)
                            ->setParameter(1, $tankId)
                            ->getSingleScalarResult();
       
       $result = array();
       $result['tankFreeCapacity'] = $tankCapacity-$calculateTotal;
       $result['tankOcupedCapacity'] = $calculateTotal;
       
       return $result;
   }
}
