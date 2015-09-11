<?php

namespace MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MainBundle\Entity\Pass;
use MainBundle\Entity\Tank;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Pass controller.
 *
 */
class PassController extends Controller {

    /**
     * Lists all Pass entities.
     *
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository("MainBundle:Tank")->findall();
        return $this->render('MainBundle:Pass:index.html.twig', array(
            'entities' => $entities
        ));
    }

    public function listAjaxAction(Request $request) {
        $get = $request->query->all();
        /* Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */
        $em = $this->getDoctrine()->getEntityManager();
        $rResult = $em->getRepository('MainBundle:Pass')->ajaxTable($get, true)->getArrayResult();
        /*
         * Output
         */
        $output = array(
            "draw" => intval($get['draw']),
            "recordsTotal" => (int)$em->getRepository("MainBundle:Pass")->getCount(),
            "recordsFiltered" => (int)$em->getRepository("MainBundle:Pass")->getFilteredCount($get),
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
    
    /**
     * Creates a new Pass entity.
     *
     */
    public function createAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        
        $tank_1 = $request->request->get('tank_1');
        $tank1 = $em->getRepository('MainBundle:Tank')->find($tank_1);
        $tank_2 = $request->request->get('tank_2');
        $tank2 = $em->getRepository('MainBundle:Tank')->find($tank_2);
        $productId = $request->request->get('product');
        $quantity = $request->request->get('quantity');
        
        $product = $this->getDoctrine()->getRepository('MainBundle:Product')->find($productId);
        
        $date = $request->request->get('date');
        $date = $request->request->get('time');
        $observation = $request->request->get('observation');
        
        $entity = new Pass();
        $entity->setObservation($observation);
        $entity->setDate(new \DateTime($date));
        $entity->setRealLiter($quantity);
        $entity->setProduct($product);

        
        $detail_1 = new \MainBundle\Entity\MovementDetail();
        $detail_1->setTank($tank1);
        $detail_1->setQuantity(-$quantity);
        $detail_1->setMovement($entity);
        $entity->addMovementDetail($detail_1);
    
        $detail_2 = new \MainBundle\Entity\MovementDetail();
        $detail_2->setTank($tank2);
        $detail_2->setQuantity($quantity);
        $detail_2->setMovement($entity);
        $entity->addMovementDetail($detail_2);


        //crear objeto
        $em->persist($entity);
        $em->flush();

        $this->addFlash(
            'success', 'El pase se ha declarado correctamente.'
        );

        return $this->redirect($this->generateUrl('pass'));
    }

    /**
     * Displays a form to create a new Pass entity.
     *
     */
    public function newAction() {
        $em = $this->getDoctrine()->getManager();
        $entity = new Pass();
        $tanks = $em->getRepository('MainBundle:Tank')->findAll();

        return $this->render('MainBundle:Pass:new.html.twig', array(
                    'entity' => $entity,
                    'tanks' => $tanks
        ));
    }

}
