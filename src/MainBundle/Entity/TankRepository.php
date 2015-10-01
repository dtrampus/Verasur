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
   
   public function calculateGraphic() {
       $em = $this->getEntityManager();
//       $end = $page*4;
//       $start = $end-4;

       $query = "SELECT t.id as id,
                        CONCAT(t.code,' - ',t.description) AS x,
                        ((sum(IFNULL(md.quantity,0))*100)/t.total_capacity) AS y,
                        (100-((sum(IFNULL(md.quantity,0))*100)/t.total_capacity)) AS z
                FROM tanks t
                LEFT JOIN movement_detail md ON t.id=md.tank_id
                GROUP BY t.id
                ORDER BY x ASC";
       
       $stmt = $em->getConnection()->prepare($query);
       $stmt->execute();
       $result = $stmt->fetchAll();
       
       return $result;
   }
   
   public function calculateGraphic2($code) {
       $em = $this->getEntityManager();

       $query = "SELECT CONCAT(t.code,' - ',t.description) AS name,
                        t.status AS status,
                        t.total_capacity AS totalCapacity,
                        ROUND(SUM(IFNULL(md.quantity,0)),2) AS occupied,
                        ROUND(t.total_capacity - sum(IFNULL(md.quantity,0)),2) AS free
                FROM tanks t
                LEFT JOIN movement_detail md ON t.id=md.tank_id
                WHERE t.code = $code
                GROUP BY t.id";
       
       $stmt = $em->getConnection()->prepare($query);
       $stmt->execute();
       $result = $stmt->fetch();
       
       return $result;
   }
}
