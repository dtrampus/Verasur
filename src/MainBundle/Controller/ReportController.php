<?php

namespace MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Report controller.
 *
 */
class ReportController extends Controller {
    
    public function inventoryIndexAction() {
        return $this->render('MainBundle:Report:inventory.html.twig');
    }
    
    public function inventoryAjaxListAction(Request $request)
    {
        $get = $request->query->all();
        $em = $this->getDoctrine()->getEntityManager();
        
        /* Validate search criteria */
        if(!isset($get["date"]) || $get["date"] == ""){
            $get["date"] = date("d/m/Y");
        }
        /* Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */                
        $aColumns = array(
          'tank' => "CONCAT(t.code,' - ',t.description)", 
          'measured_inventory' => "IFNULL((SELECT i.liter
                                          FROM inventories i
                                          WHERE i.tank_id = t.id AND DATE(i.date) = STR_TO_DATE('".$get["date"]."','%d/%m/%Y')
                                          ORDER BY i.date DESC
                                          LIMIT 1),0)", 
          'real_inventory' => "IFNULL((SELECT ROUND(SUM(md.quantity),2)
                                       FROM movement_detail md
                                        INNER JOIN movements m ON md.movement_id = m.id
                                       WHERE md.tank_id = t.id AND DATE(m.date) <= STR_TO_DATE('".$get["date"]."','%d/%m/%Y')),0)",
          'difference' => "IFNULL((SELECT i.liter
                                   FROM inventories i
                                   WHERE i.tank_id = t.id AND DATE(i.date) = STR_TO_DATE('".$get["date"]."','%d/%m/%Y')
                                   ORDER BY i.date DESC
                                   LIMIT 1),0) - 
                           IFNULL((SELECT ROUND(SUM(md.quantity),2)
                                   FROM movement_detail md
                                    INNER JOIN movements m ON md.movement_id = m.id
                                   WHERE md.tank_id = t.id AND DATE(m.date) <= STR_TO_DATE('".$get["date"]."','%d/%m/%Y')),0)");

        /* Indexed column (used for fast and accurate table cardinality) */
        $sIndexColumn = "t.id";

        $sFrom = "FROM tanks t";

        /*
         * Select
         */
        $sSelect = "SELECT ";
        $prefix = "";
        foreach ($get['columns'] as $value) {
            if ($value['name'] != "" && array_key_exists($value['name'], $aColumns)) {
                $sSelect .= $prefix.$aColumns[$value['name']]." AS ".$value['name'];
                $prefix = ", ";
            }
        }
        /*
         * Paging
         */
        $sLimit = "";
        if ( isset($get['start']) && $get['length'] != '-1' )
        {
            $sLimit = "LIMIT ".intval( $get['start'] ).", ".intval( $get['length'] );
        }


        /*
         * Ordering
         */
        $sOrder = "";
        if ( isset( $get['order'] ) )
        {
            $sOrder = "ORDER BY  ";
            for ( $i=0 ; $i<intval( $get['order'] ) ; $i++ )
            {
                $order = $get['order'][$i];
                if ( $get['columns'][$order['column']]['orderable'] == "true" )
                {
                    if ( $get['columns'][$order['column']]['name'] == "date" )
                    {
                        $sOrder .= "m.date"."
                        ".$order['dir'] .", ";
                    }else{
                        $sOrder .= $get['columns'][$order['column']]['name']."
                        ".$order['dir'] .", ";
                    }
                }
            }

            $sOrder = substr_replace( $sOrder, "", -2 );
            if ( $sOrder == "ORDER BY" )
            {
                $sOrder = "";
            }
        }


        /*
         * Filtering
         * NOTE this does not match the built-in DataTables filtering which does it
         * word by word on any field. It's possible to do here, but concerned about efficiency
         * on very large tables, and MySQL's regex functionality is very limited
         */
        $initWhere = "";
        $sWhere = $initWhere;
        if ( isset($get['search']) && $get['search'] != "" )
        {
            if ( $sWhere == "" )
            {
                $sWhere = "WHERE (";
            }
            else
            {
                $sWhere .= " AND (";
            }
            $columns = $get['columns'];
            for ( $i=0 ; $i<count($columns) ; $i++ )
            {
                if ( isset($columns[$i]['searchable']) && $columns[$i]['searchable'] == "true" )
                {
                    $sWhere .= $aColumns[$columns[$i]["name"]]." LIKE '%".$get['search']["value"]."%' OR ";
                }
            }
            $sWhere = substr_replace( $sWhere, "", -3 );
            $sWhere .= ')';
        }

        /* Individual column filtering */
//        
//        if(isset($get["fromDate"]) && $get["fromDate"] != ''){
//            if ( $sWhere == "" ){$sWhere = "WHERE ";}else{$sWhere .= " AND ";}
//            $sWhere .= "DATE(m.date) >= STR_TO_DATE('".$get["fromDate"]."','%d/%m/%Y')";
//        }
//        if(isset($get["toDate"]) && $get["toDate"] != ''){
//            if ( $sWhere == "" ){$sWhere = "WHERE ";}else{$sWhere .= " AND ";}
//            $sWhere .= "DATE(m.date) <= STR_TO_DATE('".$get["toDate"]."','%d/%m/%Y')";
//        }
//        if(isset($get["origin"]) && $get["origin"] != ''){
//            if ( $sWhere == "" ){$sWhere = "WHERE ";}else{$sWhere .= " AND ";}
//            $sWhere .= "(SELECT t.id
//                        FROM movement_detail md 
//                        INNER JOIN tanks t ON md.tank_id = t.id 
//                        WHERE quantity < 0 AND movement_id = m.id) = ".$get["origin"];
//        }
//        if(isset($get["destiny"]) && $get["destiny"] != ''){
//            if ( $sWhere == "" ){$sWhere = "WHERE ";}else{$sWhere .= " AND ";}
//            $sWhere .= "(SELECT t.id
//                        FROM movement_detail md 
//                        INNER JOIN tanks t ON md.tank_id = t.id 
//                        WHERE quantity > 0 AND movement_id = m.id) = ".$get["destiny"];
//        }

        /*
         * SQL queries
         * Get data to display
         */
        $sQuery = "
            $sSelect
            $sFrom
            $sWhere
            $sOrder
            $sLimit
        ";

        $stmt = $em->getConnection()->prepare($sQuery);
        $stmt->execute();
        $rResult = $stmt->fetchAll();

        /* Data set length after filtering */
        $sQuery = "
            SELECT IFNULL(COUNT(".$sIndexColumn."),0) AS count
            $sFrom
            $sWhere
        ";
        
        $stmt = $em->getConnection()->prepare($sQuery);
        $stmt->execute();
        $rResultFilteres = $stmt->fetch();
        $iFilteredTotal = $rResultFilteres["count"];;

        /* Total data set length */
        $sQuery = "
            SELECT IFNULL(COUNT(".$sIndexColumn."),0) AS count
            $sFrom
            $initWhere
        ";

        $stmt = $em->getConnection()->prepare($sQuery);
        $stmt->execute();
        $row = $stmt->fetch(); 
        $iTotal = $row["count"];
        /*
         * Output
         */
        $output = array(
          "draw" => intval($get['draw']),
          "recordsTotal" => $iTotal,
          "recordsFiltered" => $iFilteredTotal,
          "data" => array()
        );

        foreach ($rResult as $aRow) {
            $row = array();
            foreach ($aRow as $value) {
              $row[]=$value;  
            }
            $output['data'][] = $row;
        }

        return new JsonResponse($output);
    }
    
}