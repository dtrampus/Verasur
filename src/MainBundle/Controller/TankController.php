<?php

namespace MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use MainBundle\Entity\Tank;
use MainBundle\Form\TankType;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Tank controller.
 *
 */
class TankController extends Controller
{

    /**
     * Lists all Tank entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MainBundle:Tank')->findAll();

        return $this->render('MainBundle:Tank:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Tank entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Tank();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->addFlash(
                'success',
                'El tanque se ha creado correctamente.'
            );
            
            return $this->redirect($this->generateUrl('tank_show', array('id' => $entity->getId())));
        }

        return $this->render('MainBundle:Tank:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Tank entity.
     *
     * @param Tank $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Tank $entity)
    {
        $form = $this->createForm(new TankType(), $entity, array(
            'action' => $this->generateUrl('tank_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Crear', 'attr' => array('class' => 'btn-primary')));

        return $form;
    }

    /**
     * Displays a form to create a new Tank entity.
     *
     */
    public function newAction()
    {
        $entity = new Tank();
        $form   = $this->createCreateForm($entity);

        return $this->render('MainBundle:Tank:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Tank entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MainBundle:Tank')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tank entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MainBundle:Tank:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Tank entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MainBundle:Tank')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tank entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MainBundle:Tank:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Tank entity.
    *
    * @param Tank $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Tank $entity)
    {
        $form = $this->createForm(new TankType(), $entity, array(
            'action' => $this->generateUrl('tank_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Grabar', 'attr' => array('class' => 'btn-primary')));

        return $form;
    }
    /**
     * Edits an existing Tank entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MainBundle:Tank')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tank entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            
            $this->addFlash(
                'success',
                'El tanque se ha grabado correctamente.'
            );

            return $this->redirect($this->generateUrl('tank_show', array('id' => $id)));
        }

        return $this->render('MainBundle:Tank:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Tank entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MainBundle:Tank')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Tank entity.');
            }

            $this->addFlash(
                'success',
                'El tanque se ha eliminado correctamente.'
            );
            
            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('tank'));
    }

    /**
     * Creates a form to delete a Tank entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('tank_delete', array('id' => $id)))
            ->setMethod('DELETE')
            //->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
    
    public function getProductByTankAjaxAction($id){
        $response = array();
        //id es del tanke getproductsbytank
        $tank = $this->getDoctrine()->getManager()->getRepository('MainBundle:Tank')->find($id);
        $products = $tank->getProducts();
        
        foreach ($products as $product) {
            $res = array();
            $res[0] = $product->getId();
            $res[1] = $product->getCode();
            $res[2] = $product->getDescription();
            array_push($response, $res);
        }
             
        return new JsonResponse($response);
    }
    
     public function calculateCapacityAction($id)
   {
       $em = $this->getDoctrine()->getManager();
       $entity = $em->getRepository('MainBundle:Tank')->find($id);
       $result = $em->getRepository('MainBundle:Tank')->calculateFreeOcuped($entity);
       return new JsonResponse($result);
   }
}
