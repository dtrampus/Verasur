<?php

namespace MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MainBundle\Entity\Egress;
use MainBundle\Form\EgressType;


use PHPExcel_Style_Border;
use PHPExcel_Style_Fill;
use PHPExcel_Worksheet;
use PHPExcel_Worksheet_PageSetup;
use PHPExcel_Style_NumberFormat;
use PHPExcel_Cell_DataType;
use PHPExcel_Style_Alignment;

/**
 * Egress controller.
 *
 */
class EgressController extends Controller {

    /**
     * Lists all Egress entities.
     *
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MainBundle:Egress')->findAll();
        session_destroy();
        return $this->render('MainBundle:Egress:index.html.twig', array(
                    'entities' => $entities,
        ));
    }

    /**
     * Creates a new Egress entity.
     *
     */
    public function createAction(Request $request) {
        $entity = new Egress();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            //set user y despues get user
            $entity->setUser($this->getUser());
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->addFlash(
                    'success', 'El egreso se ha declarado correctamente.'
            );

            return $this->redirect($this->generateUrl('egress_show', array('id' => $entity->getId())));
        }

        return $this->render('MainBundle:Egress:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Egress entity.
     *
     * @param Egress $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Egress $entity) {
        $form = $this->createForm(new EgressType(), $entity, array(
            'action' => $this->generateUrl('egress_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Crear', 'attr' => array('class' => 'btn-primary')));

        return $form;
    }

    /**
     * Displays a form to create a new Egress entity.
     *
     */
    public function newAction() {
        $entity = new Egress();
        $form = $this->createCreateForm($entity);

        return $this->render('MainBundle:Egress:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Egress entity.
     *
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MainBundle:Egress')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Egress entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MainBundle:Egress:show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Egress entity.
     *
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MainBundle:Egress')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Egress entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MainBundle:Egress:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Egress entity.
     *
     * @param Egress $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Egress $entity) {
        $form = $this->createForm(new EgressType(), $entity, array(
            'action' => $this->generateUrl('egress_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Grabar', 'attr' => array('class' => 'btn-primary')));

        return $form;
    }

    /**
     * Edits an existing Egress entity.
     *
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MainBundle:Egress')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Egress entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            $this->addFlash(
                    'success', 'El egreso se ha grabado correctamente.'
            );

            return $this->redirect($this->generateUrl('egress_show', array('id' => $id)));
        }

        return $this->render('MainBundle:Egress:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Egress entity.
     *
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MainBundle:Egress')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Egress entity.');
            }

            $em->remove($entity);
            $em->flush();

            $this->addFlash(
                    'success', 'El egresso se ha eliminado correctamente.'
            );

            //$em->remove($entity);
            //$em->flush();
        }

        return $this->redirect($this->generateUrl('egress'));
    }

    /**
     * Creates a form to delete a Egress entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('egress_delete', array('id' => $id)))
                        ->setMethod('DELETE')
//            ->add('submit', 'submit', array('label' => 'Delete'))
                        ->getForm()
        ;
    }

    /**
     * Función para generar reportes en excel.
     */
    public function exportAction($type) {
        $em = $this->getDoctrine()->getManager();
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
        $registros = $em->getRepository('MainBundle:Egress')->findAll();
        $num_registros = count($registros);

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

        $count = 2;

        $count++;
        foreach ($registros as $registro) {
            $j = 0;
            foreach ($registro->getExportLine() as $egresos) {
                $phpExcelObject->setActiveSheetIndex(0)->setCellValueByColumnAndRow($j, $count, $egresos);
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
                ->setCellValue('A1', 'Listado de Egresos')
                ->setCellValue('A2', 'BALN')
                ->setCellValue('B2', 'Fecha')
                ->setCellValue('C2', 'Cliente')
                ->setCellValue('D2', 'Dominio Camión')
                ->setCellValue('E2', 'Dominio Acoplado')
                ->setCellValue('F2', 'Transporte')
                ->setCellValue('G2', 'Chofer')
                ->setCellValue('H2', 'Peso Bruto')
                ->setCellValue('I2', 'Tara')
                ->setCellValue('J2', 'Producto')
                ->setCellValue('K2', 'Densidad')
                ->setCellValue('L2', 'Testeado')
                ->setCellValue('M2', 'Neto')
                ->setCellValue('N2', 'Litros Reales')
                ->setCellValue('O2', 'Número')
                ->setCellValue('P2', 'Observación')
                ->setCellValue('Q2', 'Usuario Asociado')
                ->setCellValue('R2', 'Destilación la gota')
                ->setCellValue('S2', '5%')
                ->setCellValue('T2', '10%')
                ->setCellValue('U2', '20%')
                ->setCellValue('V2', '30%')
                ->setCellValue('W2', '40%')
                ->setCellValue('X2', '50%')
                ->setCellValue('Y2', '60%')
                ->setCellValue('Z2', '70%')
                ->setCellValue('AA2', '80%')
                ->setCellValue('AB2', '90%')
                ->setCellValue('AC2', '95%')
                ->setCellValue('AD2', 'P. Seco')
                ->setCellValue('AE2', 'P. Final')
                ->setCellValue('AF2', 'Recuperado')
                
                ->setCellValue('A' . $count, 'Mostrando ' . $num_registros . ' registros');

        $phpExcelObject->getActiveSheet()->mergeCells('A1:AF1');
        $phpExcelObject->getActiveSheet()->mergeCells('A' . $count . ':AF' . $count);
        $phpExcelObject->getActiveSheet()->getStyle("A1:AF1")->applyFromArray($styleArrayHeader);
        $phpExcelObject->getActiveSheet()->getStyle("A1:AF1")->applyFromArray($styleAlign);
        $phpExcelObject->getActiveSheet()->getStyle("A2:AF2")->applyFromArray($styleArrayHeader);
        $phpExcelObject->getActiveSheet()->getStyle("A2:AF2")->applyFromArray($styleAlign);
        $phpExcelObject->getActiveSheet()->getStyle('A' . $count . ':AF' . $count)->applyFromArray($styleArrayRows);
        $phpExcelObject->getActiveSheet()->getStyle('A' . $count . ':AF' . $count)->applyFromArray($styleAlign);
        $response;

        switch ($type) {
            case "excel":
                $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
                $response = $this->get('phpexcel')->createStreamedResponse($writer);
                $response->headers->set('Cache-Control', 'max-age=0');
                $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                $response->headers->set('Content-Disposition', 'attachment;filename=Informe Egresos.xls');
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

    public function listAjaxAction(Request $request) {
        $get = $request->query->all();
        /* Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */
        $em = $this->getDoctrine()->getEntityManager();
        $rResult = $em->getRepository('MainBundle:Egress')->ajaxTable($get, true)->getArrayResult();
        /*
         * Output
         */
        $output = array(
            "draw" => intval($get['draw']),
            "recordsTotal" => (int)$em->getRepository("MainBundle:Egress")->getCount(),
            "recordsFiltered" => (int)$em->getRepository("MainBundle:Egress")->getFilteredCount($get),
            "data" => array()
        );
        foreach ($rResult as $aRow) {
            $row = array();
            foreach ($aRow as $value) {
                if($value instanceof \DateTime){
                    $row[]=$value->format("d/m/Y H:i");
                }else{
                    $row[]=$value;  
                }
            }
            $row[] = '';
            $output['data'][] = $row;
        }
        unset($rResult);
        return new JsonResponse($output);
    }
    
}    
