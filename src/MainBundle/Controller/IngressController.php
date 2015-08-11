<?php

namespace MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use MainBundle\Entity\Ingress;
use MainBundle\Form\IngressType;


use PHPExcel_Style_Border;
use PHPExcel_Style_Fill;
use PHPExcel_Worksheet;
use PHPExcel_Worksheet_PageSetup;
use PHPExcel_Style_NumberFormat;
use PHPExcel_Cell_DataType;
use PHPExcel_Writer_Excel2007;
use PHPExcel_Style_Alignment;
/**
 * Ingress controller.
 *
 */

class IngressController extends Controller
{

    /**
     * Lists all Ingress entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MainBundle:Ingress')->findAll();
        return $this->render('MainBundle:Ingress:index.html.twig', array(
            'entities' => $entities
        ));
    }
    /**
     * Creates a new Ingress entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Ingress();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->addFlash(
                'success',
                'El ingreso se ha declarado correctamente.'
            );
            
            return $this->redirect($this->generateUrl('ingress_show', array('id' => $entity->getId())));
        }

        return $this->render('MainBundle:Ingress:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create an Ingress entity.
     *
     * @param Ingress $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Ingress $entity)
    {
        $form = $this->createForm(new IngressType(), $entity, array(
            'action' => $this->generateUrl('ingress_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Crear', 'attr' => array('class' => 'btn-primary')));

        return $form;
    }

    /**
     * Displays a form to create a new Ingress entity.
     *
     */
    public function newAction()
    {
        $entity = new Ingress();
        $form   = $this->createCreateForm($entity);

        return $this->render('MainBundle:Ingress:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Ingress entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MainBundle:Ingress')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Ingress entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MainBundle:Ingress:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Ingress entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MainBundle:Ingress')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Ingress entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MainBundle:Ingress:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit an Ingress entity.
    *
    * @param Ingress $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Ingress $entity)
    {
        $form = $this->createForm(new IngressType(), $entity, array(
            'action' => $this->generateUrl('ingress_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Grabar', 'attr' => array('class' => 'btn-primary')));

        return $form;
    }
    /**
     * Edits an existing Ingress entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MainBundle:Ingress')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Ingress entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            $this->addFlash(
                'success',
                'El ingreso se ha grabado correctamente.'
            );
            
            return $this->redirect($this->generateUrl('ingress_show', array('id' => $id)));
        }

        return $this->render('MainBundle:Ingress:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes an Ingress entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MainBundle:Ingress')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Ingress entity.');
            }
            
            $em->remove($entity);
            $em->flush();
            
            $this->addFlash(
                'success',
                'El ingreso se ha eliminado correctamente.'
            );
            
            //$em->remove($entity);
            //$em->flush();
        }

        return $this->redirect($this->generateUrl('ingress'));
    }

    /**
     * Creates a form to delete an Ingress entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('ingress_delete', array('id' => $id)))
            ->setMethod('DELETE')
//            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
    
 /**
    * Función para generar reportes en excel.
    */
    public function exportAction($type) 
    {
        $em = $this->getDoctrine()->getManager();
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
        $registros = $em->getRepository('MainBundle:Ingress')->findAll();
        $countReg = count ($registros);
        
        $styleArrayRows = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );
        
        $styleArrayHeader = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => '777777')
            )
        );
        
        $styleCenter = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            )
        );
        
        $count = 3;
        
        foreach ($registros as $registro) {
            $j=0;
            foreach($registro->getExportLine2() as $value){
               $phpExcelObject->setActiveSheetIndex(0)->setCellValueByColumnAndRow($j, $count, $value);
               $phpExcelObject->getActiveSheet()->getStyleByColumnAndRow($j, $count)->applyFromArray($styleArrayRows);
               $j++; 
            }
            $count++;
        }
       
        
        $phpExcelObject->getActiveSheet()->setShowGridLines(false);
        $phpExcelObject->getActiveSheet()
                       ->setCellValue('A1', 'Listado de Ingresos:')
                       ->setCellValue('A2', 'BALN')
                       ->setCellValue('B2', 'Fecha')
                       ->setCellValue('C2', 'Proveedor')
                       ->setCellValue('D2', 'Dominio Camión')
                       ->setCellValue('E2', 'Dominio Acoplado')
                       ->setCellValue('F2', 'Transporte')
                       ->setCellValue('G2', 'Chofer')
                       ->setCellValue('H2', 'Peso Bruto')
                       ->setCellValue('I2', 'Tara')
                       ->setCellValue('J2', 'Producto')
                       ->setCellValue('K2', 'Densidad')
                       ->setCellValue('L2', 'Neto')
                       ->setCellValue('M2', 'Litros Reales')
                       ->setCellValue('N2', 'Número')
                       ->setCellValue('A'.$count, 'Mostrando '.$countReg.' registros');
        
        $phpExcelObject->getActiveSheet()->mergeCells('A1:N1');
        $phpExcelObject->getActiveSheet()->mergeCells('A'.$count.':N'.$count);
        
        $phpExcelObject->getActiveSheet()->getStyle("A1:N1")->applyFromArray($styleArrayHeader);
        $phpExcelObject->getActiveSheet()->getStyle("A2:N2")->applyFromArray($styleArrayHeader);
        $phpExcelObject->getActiveSheet()->getStyle("A1:N1")->applyFromArray($styleCenter);
        $phpExcelObject->getActiveSheet()->getStyle('A'.$count.':N'.$count)->applyFromArray($styleCenter);
        $phpExcelObject->getActiveSheet()->getStyle("A2:N2")->applyFromArray($styleCenter);
        
        
        for($col = 'A'; $col !== 'O'; $col++) {
            $phpExcelObject->getActiveSheet()
            ->getColumnDimension($col)
            ->setAutoSize(true);
        }
        
        
        $response;
        
        switch ($type) {
            case "excel":
                $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
                $response = $this->get('phpexcel')->createStreamedResponse($writer);
                $response->headers->set('Cache-Control','max-age=0');
                $response->headers->set('Content-Type','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                $response->headers->set('Content-Disposition','attachment;filename=Informe Ingresos.xls');
                break;
            case "pdf":
                $rendererLibraryPath = (dirname(__FILE__) . '/../../../vendor/mpdf/mpdf');
                $rendererName = \PHPExcel_Settings::PDF_RENDERER_MPDF;
                \PHPExcel_Settings::setPdfRenderer(
                    $rendererName, $rendererLibraryPath
                );        
                $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'PDF');
                $response = $this->get('phpexcel')->createStreamedResponse($writer);
                $response->headers->set('Cache-Control','max-age=0');
                $response->headers->set('Content-Type','application/pdf');
//                $response->headers->set('Content-Disposition', 'attachment;filename=Informe Ingresos.pdf');
                break;
            default:
                $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'HTML');
                $response = $this->get('phpexcel')->createStreamedResponse($writer);
                break;
        }
        
        return $response;
    }
    
}
