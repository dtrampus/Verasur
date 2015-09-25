<?php

namespace MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use MainBundle\Entity\Inventory;
use MainBundle\Form\InventoryType;
use MainBundle\Entity\Tank;

/**
 * Inventory controller.
 *
 */
class InventoryController extends Controller
{

    /**
     * Lists all Inventory entities.
     *
     */
    public function indexAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $tank = $em->getRepository('MainBundle:Tank')->find($id);

        $entities = $em->getRepository('MainBundle:Inventory')->findby(array('tank' => $id));
        $tank = $em->getRepository('MainBundle:Tank')->find($id);
        
        return $this->render('MainBundle:Inventory:index.html.twig', array(
            'entities' => $entities,
            'tank' => $tank
        ));
    }
    
    public function listAjaxAction(Request $request) {
        $get = $request->query->all();
        $em = $this->getDoctrine()->getEntityManager();
        $output = $em->getRepository('MainBundle:Inventory')->findDataTable($get);
        return new JsonResponse($output);
    }
    
    /**
     * Creates a new Inventory entity.
     *
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $tank_id = $request->request->get('tank_id');
        $tank = $em->getRepository('MainBundle:Tank')->find($tank_id);
        $tank->setStatus("Liberado");
        
        $entity = new Inventory();
        $form = $this->createCreateForm($entity, $tank);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $entity->setUsers($this->getUser());
            $entity->setTank($tank);
            $em->persist($entity);
            $em->flush();
            
            $this->addFlash(
                'success',
                'El inventario se ha declarado correctamente.'
            );

            return $this->redirect($this->generateUrl('inventory', array('id' => $tank_id)));
        }

        return $this->render('MainBundle:Inventory:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'tank' => $tank
        ));
    }

    /**
     * Creates a form to create a Inventory entity.
     *
     * @param Inventory $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Inventory $entity, Tank $tank)
    {
        $form = $this->createForm(new InventoryType($tank), $entity, array(
            'action' => $this->generateUrl('inventory_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Crear', 'attr' => array('class' => 'btn-primary')));

        return $form;
    }

    /**
     * Displays a form to create a new Inventory entity.
     *
     */
    public function newAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $tank = $em->getRepository('MainBundle:Tank')->find($id);
        
        $entity = new Inventory();
        $form   = $this->createCreateForm($entity, $tank);

        return $this->render('MainBundle:Inventory:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'tank' => $tank
        ));
    }

    /**
     * Finds and displays a Inventory entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MainBundle:Inventory')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Inventory entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MainBundle:Inventory:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView()
        ));
    }

    /**
     * Displays a form to edit an existing Inventory entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MainBundle:Inventory')->find($id);
        $tank = $entity->getTank();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Inventory entity.');
        }

        $editForm = $this->createEditForm($entity, $tank);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MainBundle:Inventory:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'tank' => $tank
        ));
    }

    /**
    * Creates a form to edit a Inventory entity.
    *
    * @param Inventory $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Inventory $entity, Tank $tank)
    {
        $form = $this->createForm(new InventoryType($tank), $entity, array(
            'action' => $this->generateUrl('inventory_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Grabar', 'attr' => array('class' => 'btn-primary')));

        return $form;
    }
    /**
     * Edits an existing Inventory entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $tank_id = $request->request->get('tank_id');
        $tank = $em->getRepository('MainBundle:Tank')->find($tank_id);
        $tank->setStatus("Liberado");

        $entity = $em->getRepository('MainBundle:Inventory')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Inventory entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity, $tank);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            
            $this->addFlash(
                'success',
                'El inventario se ha grabado correctamente.'
            );

            return $this->redirect($this->generateUrl('inventory', array('id' => $id)));
        }

        return $this->render('MainBundle:Inventory:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'tank' => $tank
        ));
    }
    /**
     * Deletes a Inventory entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MainBundle:Inventory')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Inventory entity.');
            }

            $em->remove($entity);
            $em->flush();
            
            $this->addFlash(
                'success',
                'El inventario se ha eliminado correctamente.'
            );
        }

        return $this->redirect($this->generateUrl('inventory', array('id' => $entity->getTank()->getId())));
    }

    /**
     * Creates a form to delete a Inventory entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('inventory_delete', array('id' => $id)))
            ->setMethod('DELETE')
            //->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
    
}
