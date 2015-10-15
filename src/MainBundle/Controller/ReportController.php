<?php

namespace MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

use PHPExcel_Style_Border;
use PHPExcel_Style_Fill;
use PHPExcel_Worksheet;
use PHPExcel_Worksheet_PageSetup;
use PHPExcel_Style_NumberFormat;
use PHPExcel_Cell_DataType;
use PHPExcel_Style_Alignment;

/**
 * Report controller.
 *
 */
class ReportController extends Controller {

    public function inventoryIndexAction() {
        return $this->render('MainBundle:Report:inventory.html.twig');
    }

    public function inventoryAjaxListAction(Request $request) {
        $get = $request->query->all();
        $em = $this->getDoctrine()->getEntityManager();

        /* Validate search criteria */
        if (!isset($get["date"]) || $get["date"] == "") {
            $get["date"] = date("d/m/Y");
        }
        /* Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */
        $aColumns = array(
            'tank' => "CONCAT(t.code,' - ',t.description)",
            'measured_inventory' => "IFNULL((SELECT i.liter
                                          FROM inventories i
                                          WHERE i.tank_id = t.id AND DATE(i.date) = STR_TO_DATE('" . $get["date"] . "','%d/%m/%Y')
                                          ORDER BY i.date DESC
                                          LIMIT 1),0)",
            'real_inventory' => "IFNULL((SELECT ROUND(SUM(md.quantity),2)
                                       FROM movement_detail md
                                        INNER JOIN movements m ON md.movement_id = m.id
                                       WHERE md.tank_id = t.id AND DATE(m.date) <= STR_TO_DATE('" . $get["date"] . "','%d/%m/%Y')),0)",
            'difference' => "IFNULL((SELECT i.liter
                                   FROM inventories i
                                   WHERE i.tank_id = t.id AND DATE(i.date) = STR_TO_DATE('" . $get["date"] . "','%d/%m/%Y')
                                   ORDER BY i.date DESC
                                   LIMIT 1),0) - 
                           IFNULL((SELECT ROUND(SUM(md.quantity),2)
                                   FROM movement_detail md
                                    INNER JOIN movements m ON md.movement_id = m.id
                                   WHERE md.tank_id = t.id AND DATE(m.date) <= STR_TO_DATE('" . $get["date"] . "','%d/%m/%Y')),0)");

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
                $sSelect .= $prefix . $aColumns[$value['name']] . " AS " . $value['name'];
                $prefix = ", ";
            }
        }
        /*
         * Paging
         */
        $sLimit = "";
        if (isset($get['start']) && $get['length'] != '-1') {
            $sLimit = "LIMIT " . intval($get['start']) . ", " . intval($get['length']);
        }


        /*
         * Ordering
         */
        $sOrder = "";
        if (isset($get['order'])) {
            $sOrder = "ORDER BY  ";
            for ($i = 0; $i < intval($get['order']); $i++) {
                $order = $get['order'][$i];
                if ($get['columns'][$order['column']]['orderable'] == "true") {
                    if ($get['columns'][$order['column']]['name'] == "date") {
                        $sOrder .= "m.date" . "
                        " . $order['dir'] . ", ";
                    } else {
                        $sOrder .= $get['columns'][$order['column']]['name'] . "
                        " . $order['dir'] . ", ";
                    }
                }
            }

            $sOrder = substr_replace($sOrder, "", -2);
            if ($sOrder == "ORDER BY") {
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
        if (isset($get['search']) && $get['search'] != "") {
            if ($sWhere == "") {
                $sWhere = "WHERE (";
            } else {
                $sWhere .= " AND (";
            }
            $columns = $get['columns'];
            for ($i = 0; $i < count($columns); $i++) {
                if (isset($columns[$i]['searchable']) && $columns[$i]['searchable'] == "true") {
                    $sWhere .= $aColumns[$columns[$i]["name"]] . " LIKE '%" . $get['search']["value"] . "%' OR ";
                }
            }
            $sWhere = substr_replace($sWhere, "", -3);
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
            SELECT IFNULL(COUNT(" . $sIndexColumn . "),0) AS count
            $sFrom
            $sWhere
        ";

        $stmt = $em->getConnection()->prepare($sQuery);
        $stmt->execute();
        $rResultFilteres = $stmt->fetch();
        $iFilteredTotal = $rResultFilteres["count"];
        ;

        /* Total data set length */
        $sQuery = "
            SELECT IFNULL(COUNT(" . $sIndexColumn . "),0) AS count
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
                $row[] = $value;
            }
            $output['data'][] = $row;
        }

        return new JsonResponse($output);
    }

    public function tankIndexAction() {
        $em = $this->getDoctrine()->getManager();

        $tanks = $em->getRepository('MainBundle:Tank')->findAll();

        return $this->render('MainBundle:Report:tankReport.html.twig', array(
                    "tanks" => $tanks
        ));
    }

    public function tankAjaxListAction(Request $request) {
        $get = $request->query->all();
        $em = $this->getDoctrine()->getEntityManager();

        /* Validate search criteria */
        if (!isset($get["date"]) || $get["date"] == "") {
            $get["date"] = date("d/m/Y");
        }
        /* Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */
        $aColumns = array(
            'date' => "DATE_FORMAT(m.date,'%d/%m/%Y %H:%i')",
            'origin' => "case m.discr
			  when 'ingress' then NULL
			  when 'egress' then
					(SELECT CONCAT(t.code,' - ',t.description) 
					FROM movement_detail md2 
					INNER JOIN tanks t ON md2.tank_id = t.id 
					WHERE md2.id = md.id)
			  when 'pass' then
					(SELECT CONCAT(t.code,' - ',t.description) 
					FROM movement_detail md2 
					INNER JOIN tanks t ON md2.tank_id = t.id 
					WHERE md2.quantity < 0 AND md2.movement_id = m.id)
                        end",
            'destiny' => "case m.discr
			  when 'ingress' then
				  (SELECT CONCAT(t.code,' - ',t.description) 
				   FROM movement_detail md2
				   INNER JOIN tanks t ON md2.tank_id = t.id 
				   WHERE md2.id = m.id)
			  when 'egress' then NULL
			  when 'pass' then
				  (SELECT CONCAT(t.code,' - ',t.description) 
				   FROM movement_detail md2
				   INNER JOIN tanks t ON md2.tank_id = t.id 
				   WHERE md2.quantity > 0 AND md2.movement_id = m.id)
                         end",
            'movementType' => "case m.discr
                                    when 'egress' then 'Egreso'
                                    when 'ingress' then 'Ingreso'
                                    when 'pass' then 'Pase'
                              end",
            'product' => "CONCAT(p.code,' - ',p.description)",
            'quantity' => "md.quantity");

        /* Indexed column (used for fast and accurate table cardinality) */
        $sIndexColumn = "md.id";

        $sFrom = "FROM movement_detail md INNER JOIN movements m ON md.movement_id = m.id INNER JOIN products p ON m.product_id = p.id";

        /*
         * Select
         */
        $sSelect = "SELECT ";
        $prefix = "";
        foreach ($get['columns'] as $value) {
            if ($value['name'] != "" && array_key_exists($value['name'], $aColumns)) {
                $sSelect .= $prefix . $aColumns[$value['name']] . " AS " . $value['name'];
                $prefix = ", ";
            }
        }
        /*
         * Paging
         */
        $sLimit = "";
        if (isset($get['start']) && $get['length'] != '-1') {
            $sLimit = "LIMIT " . intval($get['start']) . ", " . intval($get['length']);
        }


        /*
         * Ordering
         */
        $sOrder = "";
        if (isset($get['order'])) {
            $sOrder = "ORDER BY  ";
            for ($i = 0; $i < intval($get['order']); $i++) {
                $order = $get['order'][$i];
                if ($get['columns'][$order['column']]['orderable'] == "true") {
                    if ($get['columns'][$order['column']]['name'] == "date") {
                        $sOrder .= "m.date" . "
                        " . $order['dir'] . ", ";
                    } else {
                        $sOrder .= $get['columns'][$order['column']]['name'] . "
                        " . $order['dir'] . ", ";
                    }
                }
            }

            $sOrder = substr_replace($sOrder, "", -2);
            if ($sOrder == "ORDER BY") {
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
        if (isset($get['search']) && $get['search'] != "") {
            if ($sWhere == "") {
                $sWhere = "WHERE (";
            } else {
                $sWhere .= " AND (";
            }
            $columns = $get['columns'];
            for ($i = 0; $i < count($columns); $i++) {
                if (isset($columns[$i]['searchable']) && $columns[$i]['searchable'] == "true") {
                    $sWhere .= $aColumns[$columns[$i]["name"]] . " LIKE '%" . $get['search']["value"] . "%' OR ";
                }
            }
            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere .= ')';
        }

        /* Individual column filtering */
        
        if(isset($get["fromDate"]) && $get["fromDate"] != ''){
            if ( $sWhere == "" ){$sWhere = "WHERE ";}else{$sWhere .= " AND ";}
            $sWhere .= "DATE(m.date) >= STR_TO_DATE('".$get["fromDate"]."','%d/%m/%Y')";
        }
        if(isset($get["toDate"]) && $get["toDate"] != ''){
            if ( $sWhere == "" ){$sWhere = "WHERE ";}else{$sWhere .= " AND ";}
            $sWhere .= "DATE(m.date) <= STR_TO_DATE('".$get["toDate"]."','%d/%m/%Y')";
        }
        if (isset($get["tank"]) && $get["tank"] != '') {
            if ($sWhere == "") {
                $sWhere = "WHERE ";
            } else {
                $sWhere .= " AND ";
            }
            $sWhere .= "md.tank_id = ".$get["tank"];
        }
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
            SELECT IFNULL(COUNT(" . $sIndexColumn . "),0) AS count
            $sFrom
            $sWhere
        ";

        $stmt = $em->getConnection()->prepare($sQuery);
        $stmt->execute();
        $rResultFilteres = $stmt->fetch();
        $iFilteredTotal = $rResultFilteres["count"];
        ;

        /* Total data set length */
        $sQuery = "
            SELECT IFNULL(COUNT(" . $sIndexColumn . "),0) AS count
            $sFrom
            $initWhere
        ";

        $stmt = $em->getConnection()->prepare($sQuery);
        $stmt->execute();
        $row = $stmt->fetch();
        $iTotal = $row["count"];
        
        /* Data set length after filtering */
        $sQuery = "
            SELECT ROUND(SUM(md.quantity),2) as countQuantity
            $sFrom
            $sWhere
        ";

        $stmt = $em->getConnection()->prepare($sQuery);
        $stmt->execute();
        $rResultFilteres = $stmt->fetch();
        $total = $rResultFilteres["countQuantity"];
        
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
                $row[] = $value;
            }
            $output['data'][] = $row;
        }
        $output['total'] = $total;
        
        return new JsonResponse($output);
    }
    
    public function tankExportAction(Request $request) {
        $getFileType = $request->request->get('file-type');
        $get = json_decode($request->request->get('parameters'), true);
        $em = $this->getDoctrine()->getManager();
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

        $styleArrayRows = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            ),
        );

        $styleArrayHeader = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'D3D3D3')
            )
        );

        $styleAlign = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
            )
        );

        
        /* Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */
        $aColumns = array(
            'date' => "DATE_FORMAT(m.date,'%d/%m/%Y %H:%i')",
            'origin' => "case m.discr
			  when 'ingress' then NULL
			  when 'egress' then
					(SELECT CONCAT(t.code,' - ',t.description) 
					FROM movement_detail md2 
					INNER JOIN tanks t ON md2.tank_id = t.id 
					WHERE md2.id = md.id)
			  when 'pass' then
					(SELECT CONCAT(t.code,' - ',t.description) 
					FROM movement_detail md2 
					INNER JOIN tanks t ON md2.tank_id = t.id 
					WHERE md2.quantity < 0 AND md2.movement_id = m.id)
                        end",
            'destiny' => "case m.discr
			  when 'ingress' then
				  (SELECT CONCAT(t.code,' - ',t.description) 
				   FROM movement_detail md2
				   INNER JOIN tanks t ON md2.tank_id = t.id 
				   WHERE md2.id = m.id)
			  when 'egress' then NULL
			  when 'pass' then
				  (SELECT CONCAT(t.code,' - ',t.description) 
				   FROM movement_detail md2
				   INNER JOIN tanks t ON md2.tank_id = t.id 
				   WHERE md2.quantity > 0 AND md2.movement_id = m.id)
                         end",
            'movementType' => "case m.discr
                                    when 'egress' then 'Egreso'
                                    when 'ingress' then 'Ingreso'
                                    when 'pass' then 'Pase'
                              end",
            'product' => "CONCAT(p.code,' - ',p.description)",
            'quantity' => "md.quantity");

        /* Indexed column (used for fast and accurate table cardinality) */
        $sIndexColumn = "md.id";

        $sFrom = "FROM movement_detail md INNER JOIN movements m ON md.movement_id = m.id INNER JOIN products p ON m.product_id = p.id";

        /*
         * Select
         */
        $sSelect = "SELECT ";
        $prefix = "";
        foreach ($get['columns'] as $value) {
            if ($value['name'] != "" && array_key_exists($value['name'], $aColumns)) {
                $sSelect .= $prefix . $aColumns[$value['name']] . " AS " . $value['name'];
                $prefix = ", ";
            }
        }


        /*
         * Ordering
         */
        $sOrder = "";
        if (isset($get['order'])) {
            $sOrder = "ORDER BY  ";
            for ($i = 0; $i < intval($get['order']); $i++) {
                $order = $get['order'][$i];
                if ($get['columns'][$order['column']]['orderable'] == "true") {
                    if ($get['columns'][$order['column']]['name'] == "date") {
                        $sOrder .= "m.date" . "
                        " . $order['dir'] . ", ";
                    } else {
                        $sOrder .= $get['columns'][$order['column']]['name'] . "
                        " . $order['dir'] . ", ";
                    }
                }
            }

            $sOrder = substr_replace($sOrder, "", -2);
            if ($sOrder == "ORDER BY") {
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
        if (isset($get['search']) && $get['search'] != "") {
            if ($sWhere == "") {
                $sWhere = "WHERE (";
            } else {
                $sWhere .= " AND (";
            }
            $columns = $get['columns'];
            for ($i = 0; $i < count($columns); $i++) {
                if (isset($columns[$i]['searchable']) && $columns[$i]['searchable'] == "true") {
                    $sWhere .= $aColumns[$columns[$i]["name"]] . " LIKE '%" . $get['search']["value"] . "%' OR ";
                }
            }
            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere .= ')';
        }

        /* Individual column filtering */
        
        if(isset($get["fromDate"]) && $get["fromDate"] != ''){
            if ( $sWhere == "" ){$sWhere = "WHERE ";}else{$sWhere .= " AND ";}
            $sWhere .= "DATE(m.date) >= STR_TO_DATE('".$get["fromDate"]."','%d/%m/%Y')";
        }
        if(isset($get["toDate"]) && $get["toDate"] != ''){
            if ( $sWhere == "" ){$sWhere = "WHERE ";}else{$sWhere .= " AND ";}
            $sWhere .= "DATE(m.date) <= STR_TO_DATE('".$get["toDate"]."','%d/%m/%Y')";
        }
        if (isset($get["tank"]) && $get["tank"] != '') {
            if ($sWhere == "") {
                $sWhere = "WHERE ";
            } else {
                $sWhere .= " AND ";
            }
            $sWhere .= "md.tank_id = ".$get["tank"];
        }

        /*
         * SQL queries
         * Get data to display
         */
        $sQuery = $sSelect.' '.$sFrom.' '.$sWhere.' '.$sOrder;

        $stmt = $em->getConnection()->prepare($sQuery);
        $stmt->execute();
        $rResult = $stmt->fetchAll();
        
                /* Data set length after filtering */
        $sQuery = "
            SELECT IFNULL(COUNT(" . $sIndexColumn . "),0) AS count
            $sFrom
            $sWhere
        ";

        $stmt = $em->getConnection()->prepare($sQuery);
        $stmt->execute();
        $rResultFilteres = $stmt->fetch();
        $iFilteredTotal = $rResultFilteres["count"];
        ;

        /* Total data set length */
        $sQuery = "
            SELECT IFNULL(COUNT(" . $sIndexColumn . "),0) AS count
            $sFrom
            $initWhere
        ";

        $stmt = $em->getConnection()->prepare($sQuery);
        $stmt->execute();
        $row = $stmt->fetch();
        $iTotal = $row["count"];
        
        /* Data set length after filtering */
        $sQuery = "
            SELECT ROUND(SUM(md.quantity),2) as countQuantity
            $sFrom
            $sWhere
        ";

        $stmt = $em->getConnection()->prepare($sQuery);
        $stmt->execute();
        $rResultFilteres = $stmt->fetch();
        $total = $rResultFilteres["countQuantity"];
        
        $count = 7;
        $count++;
        foreach ($rResult as $registro) {
            $j = 0;
            foreach ($registro as $movimiento) {
                $phpExcelObject->setActiveSheetIndex(0)->setCellValueByColumnAndRow($j, $count, $movimiento);
                $phpExcelObject->getActiveSheet()->getStyleByColumnAndRow($j, $count)->applyFromArray($styleArrayRows);
                $j++;
            }
            $count++;
        }

        for ($variable = 'A'; $variable != 'O'; $variable++) {
            $phpExcelObject->getActiveSheet()->getColumnDimension($variable)->setAutoSize(true);
        }
        
        $phpExcelObject->getActiveSheet()->setShowGridLines(false);
        $phpExcelObject->getActiveSheet()
                ->setCellValue('A1', 'Listado de Movimientos')
                ->setCellValue('A2', 'Filtros')
                ->setCellValue('B2', 'Desde:')
                ->setCellValue('C2', $get["fromDate"])
                ->setCellValue('B3', 'Hasta:')
                ->setCellValue('C3', $get["toDate"])
                ->setCellValue('B4', 'Tanque:')
                ->setCellValue('C4', $get["tank"])
                ->setCellValue('B5', 'Global:')
                ->setCellValue('C5', $get['search']["value"])
                ->setCellValue('A7', 'Fecha')
                ->setCellValue('B7', 'Origen')
                ->setCellValue('C7', 'Destino')
                ->setCellValue('D7', 'Tipo de movimiento')
                ->setCellValue('E7', 'Producto')
                ->setCellValue('F7', 'Cantidad')
                ->setCellValue('E'.$count, 'Total:')
                ->setCellValue('F'.$count, $total)
                ->setCellValue('A'.($count+1), 'Mostrando '.$iFilteredTotal.' registros (filtrado de un total de '.$iTotal.' registros)');

        $phpExcelObject->getActiveSheet()->mergeCells('A1:F1');
        $phpExcelObject->getActiveSheet()->mergeCells('A'.($count+1) .':F'.($count+1));
        $phpExcelObject->getActiveSheet()->getStyle("A2")->applyFromArray($styleArrayHeader);
        $phpExcelObject->getActiveSheet()->getStyle("E".$count)->applyFromArray($styleArrayHeader);
        $phpExcelObject->getActiveSheet()->getStyle('B2:C2')->applyFromArray($styleArrayRows);
        $phpExcelObject->getActiveSheet()->getStyle('B3:C3')->applyFromArray($styleArrayRows);
        $phpExcelObject->getActiveSheet()->getStyle('B4:C4')->applyFromArray($styleArrayRows);
        $phpExcelObject->getActiveSheet()->getStyle('B5:C5')->applyFromArray($styleArrayRows);
        $phpExcelObject->getActiveSheet()->getStyle('E'.$count.':F'.$count)->applyFromArray($styleArrayRows);
        $phpExcelObject->getActiveSheet()->getStyle("A1:F1")->applyFromArray($styleArrayHeader);
        $phpExcelObject->getActiveSheet()->getStyle("A1:F1")->applyFromArray($styleAlign);
        $phpExcelObject->getActiveSheet()->getStyle("A7:F7")->applyFromArray($styleArrayHeader);
        $phpExcelObject->getActiveSheet()->getStyle("A7:F7")->applyFromArray($styleAlign);
        $phpExcelObject->getActiveSheet()->getStyle('A'.($count+1) .':F'.($count+1))->applyFromArray($styleArrayRows);
        $phpExcelObject->getActiveSheet()->getStyle('A'.($count+1) .':F'.($count+1))->applyFromArray($styleAlign);
        $response;

        switch ($getFileType) {
            case "excel":
                $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
                $response = $this->get('phpexcel')->createStreamedResponse($writer);
                $response->headers->set('Cache-Control', 'max-age=0');
                $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                $response->headers->set('Content-Disposition', 'attachment;filename=Informe de Movimientos.xls');
                break;
            case "pdf":
                $rendererLibraryPath = (dirname(__FILE__) . '/../../../vendor/mpdf/mpdf');
                $rendererName = \PHPExcel_Settings::PDF_RENDERER_MPDF;
                \PHPExcel_Settings::setPdfRenderer(
                        $rendererName, $rendererLibraryPath
                );
                $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'PDF');
                $response = $this->get('phpexcel')->createStreamedResponse($writer);
                $response->headers->set('Cache-Control', 'max-age=0');
                $response->headers->set('Content-Type', 'application/pdf');
                //$response->headers->set('Content-Disposition', 'attachment;filename=Informe Egresos.pdf');
                break;
            default:
                $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'HTML');
                $response = $this->get('phpexcel')->createStreamedResponse($writer);
                break;
        }

        return $response;
    }

    public function inventoryExportAction(Request $request)
    {
        $getFileType = $request->request->get('file-type');
        $get = json_decode($request->request->get('parameters'), true);
        $em = $this->getDoctrine()->getManager();
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

        $styleArrayRows = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            ),
        );

        $styleArrayHeader = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'D3D3D3')
            )
        );

        $styleAlign = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
            )
        );

        
        /* Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */
        $aColumns = array(
          'tank' => "CONCAT(t.code,' - ',t.description)", 
          'measured_inventory' => "IFNULL((SELECT i.liter
                                          FROM inventories i
                                          WHERE i.tank_id = t.id AND DATE(i.date) = STR_TO_DATE('".$get['date']."','%d/%m/%Y')
                                          ORDER BY i.date DESC
                                          LIMIT 1),0)", 
          'real_inventory' => "IFNULL((SELECT ROUND(SUM(md.quantity),2)
                                       FROM movement_detail md
                                        INNER JOIN movements m ON md.movement_id = m.id
                                       WHERE md.tank_id = t.id AND DATE(m.date) <= STR_TO_DATE('".$get['date']."','%d/%m/%Y')),0)",
          'difference' => "IFNULL((SELECT i.liter
                                   FROM inventories i
                                   WHERE i.tank_id = t.id AND DATE(i.date) = STR_TO_DATE('".$get['date']."','%d/%m/%Y')
                                   ORDER BY i.date DESC
                                   LIMIT 1),0) - 
                           IFNULL((SELECT ROUND(SUM(md.quantity),2)
                                   FROM movement_detail md
                                    INNER JOIN movements m ON md.movement_id = m.id
                                   WHERE md.tank_id = t.id AND DATE(m.date) <= STR_TO_DATE('".$get['date']."','%d/%m/%Y')),0)");

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

        $sQuery = $sSelect.' '.$sFrom.' '.$sWhere.' '.$sOrder;

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
        
        $count = 5;
        $count++;
        foreach ($rResult as $registro) {
            $j = 0;
            foreach ($registro as $movimientos) {
                $phpExcelObject->setActiveSheetIndex(0)->setCellValueByColumnAndRow($j, $count, $movimientos);
                $phpExcelObject->getActiveSheet()->getStyleByColumnAndRow($j, $count)->applyFromArray($styleArrayRows);
                $j++;
            }
            $count++;
        }

        for ($variable = 'A'; $variable != 'O'; $variable++) {
            $phpExcelObject->getActiveSheet()->getColumnDimension($variable)->setAutoSize(true);
        }

        $phpExcelObject->getActiveSheet()->setShowGridLines(false);
        $phpExcelObject->getActiveSheet()
                ->setCellValue('A1', 'Listado de Inventario')
                ->setCellValue('A2', 'Filtros')
                ->setCellValue('B2', 'Fecha:')
                ->setCellValue('C2', $get["date"])
                ->setCellValue('B3', 'Global:')
                ->setCellValue('C3', $get["search"]['value'])
                ->setCellValue('A5', 'Tanque')
                ->setCellValue('B5', 'Stock Medido')
                ->setCellValue('C5', 'Stock Actual')
                ->setCellValue('D5', 'Diferencia')
                
                ->setCellValue('A'.$count, 'Mostrando '.$iFilteredTotal.' registros de '.$iTotal);

        $phpExcelObject->getActiveSheet()->mergeCells('A1:D1');
        $phpExcelObject->getActiveSheet()->mergeCells('A'.$count.':D'.$count);
        $phpExcelObject->getActiveSheet()->getStyle("A2")->applyFromArray($styleArrayHeader);
        $phpExcelObject->getActiveSheet()->getStyle('B2:C2')->applyFromArray($styleArrayRows);
        $phpExcelObject->getActiveSheet()->getStyle('B3:C3')->applyFromArray($styleArrayRows);
        $phpExcelObject->getActiveSheet()->getStyle("A1:D1")->applyFromArray($styleArrayHeader);
        $phpExcelObject->getActiveSheet()->getStyle("A1:D1")->applyFromArray($styleAlign);
        $phpExcelObject->getActiveSheet()->getStyle("A5:D5")->applyFromArray($styleArrayHeader);
        $phpExcelObject->getActiveSheet()->getStyle("A5:D5")->applyFromArray($styleAlign);
        $phpExcelObject->getActiveSheet()->getStyle('A'.$count.':D'.$count)->applyFromArray($styleArrayRows);
        $phpExcelObject->getActiveSheet()->getStyle('A'.$count.':D'.$count)->applyFromArray($styleAlign);
        $response;

        switch ($getFileType) {
            case "excel":
                $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
                $response = $this->get('phpexcel')->createStreamedResponse($writer);
                $response->headers->set('Cache-Control', 'max-age=0');
                $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                $response->headers->set('Content-Disposition', 'attachment;filename=Informe de Inventario.xls');
                break;
            case "pdf":
                $rendererLibraryPath = (dirname(__FILE__) . '/../../../vendor/mpdf/mpdf');
                $rendererName = \PHPExcel_Settings::PDF_RENDERER_MPDF;
                \PHPExcel_Settings::setPdfRenderer(
                        $rendererName, $rendererLibraryPath
                );
                $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'PDF');
                $response = $this->get('phpexcel')->createStreamedResponse($writer);
                $response->headers->set('Cache-Control', 'max-age=0');
                $response->headers->set('Content-Type', 'application/pdf');
                //$response->headers->set('Content-Disposition', 'attachment;filename=Informe Egresos.pdf');
                break;
            default:
                $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'HTML');
                $response = $this->get('phpexcel')->createStreamedResponse($writer);
                break;
        }

        return $response;
    }
    
}
