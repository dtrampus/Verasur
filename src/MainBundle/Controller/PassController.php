<?php

namespace MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MainBundle\Entity\Pass;
use MainBundle\Entity\Tank;
use MainBundle\Form\PassType;
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

        $tanks = $em->getRepository('MainBundle:Tank')->findAll();

        return $this->render('MainBundle:Pass:index.html.twig', array(
                    "tanks" => $tanks
        ));
    }

    public function listAjaxAction(Request $request) {
        $get = $request->request->all();
        $em = $this->getDoctrine()->getEntityManager();
        $output = $em->getRepository('MainBundle:Pass')->findDataTable($get);
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
        $tank2->setStatus("N/A");

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

    /**
     * Finds and displays a Pass entity.
     *
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MainBundle:Pass')->find($id);


        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Pass entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MainBundle:Pass:show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView()
        ));
    }

    /**
     * Displays a form to edit an existing Inventory entity.
     *
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = new Pass();
        $tanks = $em->getRepository('MainBundle:Tank')->findAll();
        $passes = $em->getRepository('MainBundle:Pass')->find($id);

        return $this->render('MainBundle:Pass:edit.html.twig', array(
                    'entity' => $entity,
                    'tanks' => $tanks,
                    'passes' => $passes
        ));
    }

    /**
     * Creates a form to edit a Pass entity.
     *
     * @param Pass $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Pass $entity) {
        $form = $this->createForm(new PassType(), $entity, array(
            'action' => $this->generateUrl('pass_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Grabar', 'attr' => array('class' => 'btn-primary')));

        return $form;
    }

    /**
     * Edits an existing Pass entity.
     *
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MainBundle:Pass')->find($id);

        foreach ($entity->getMovementDetails() as $detail) {
            $em->remove($detail);
        }

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Product entity.');
        }
        $em->flush();

        $tank_1 = $request->request->get('tank_1');
        $tank1 = $em->getRepository('MainBundle:Tank')->find($tank_1);
        $tank_2 = $request->request->get('tank_2');
        $tank2 = $em->getRepository('MainBundle:Tank')->find($tank_2);
        $productId = $request->request->get('product');
        $quantity = $request->request->get('quantity');
        $tank2->setStatus("N/A");

        $product = $this->getDoctrine()->getRepository('MainBundle:Product')->find($productId);

        $date = $request->request->get('date');
        $date = $request->request->get('time');
        $observation = $request->request->get('observation');

       
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
                    'success', 'El pase se ha grabado correctamente.'
            );

            return $this->redirect($this->generateUrl('pass'));
        
    }

    /**
     * Deletes a Pass entity.
     *
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MainBundle:Pass')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Pass entity.');
            }

            $em->remove($entity);
            $em->flush();

            $this->addFlash(
                    'success', 'El pase se ha eliminado correctamente.'
            );
        }

        return $this->redirect($this->generateUrl('pass'));
    }

    /**
     * Creates a form to delete a Pass entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('pass_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        //->add('submit', 'submit', array('label' => 'Delete'))
                        ->getForm()
        ;
    }
    
    public function checkDateTimeAjaxAction($date){
        $datetime = \DateTime::createFromFormat('Y-m-d H:i', $date);
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('MainBundle:Pass')->findBy(array('date' => $datetime));
        if($entity != null){
            $entity = false;
        }else{
            $entity = true;
        }
        return new JsonResponse($entity);
    }

}
