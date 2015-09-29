<?php

namespace MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use MainBundle\Entity\Driver;
use MainBundle\Form\DriverType;

/**
 * Driver controller.
 *
 */
class DriverController extends Controller
{

    /**
     * Lists all Driver entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MainBundle:Driver')->findAll();

        return $this->render('MainBundle:Driver:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Driver entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Driver();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            
            $this->addFlash(
                'success',
                'El chofer se ha creado correctamente.'
            );

            return $this->redirect($this->generateUrl('driver'));
        }

        return $this->render('MainBundle:Driver:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Driver entity.
     *
     * @param Driver $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Driver $entity)
    {
        $form = $this->createForm(new DriverType(), $entity, array(
            'action' => $this->generateUrl('driver_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Crear', 'attr' => array('class' => 'btn-primary')));

        return $form;
    }

    /**
     * Displays a form to create a new Driver entity.
     *
     */
    public function newAction()
    {
        $entity = new Driver();
        $form   = $this->createCreateForm($entity);

        return $this->render('MainBundle:Driver:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Driver entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MainBundle:Driver')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Driver entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MainBundle:Driver:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Driver entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MainBundle:Driver')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Driver entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MainBundle:Driver:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Driver entity.
    *
    * @param Driver $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Driver $entity)
    {
        $form = $this->createForm(new DriverType(), $entity, array(
            'action' => $this->generateUrl('driver_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Grabar', 'attr' => array('class' => 'btn-primary')));

        return $form;
    }
    /**
     * Edits an existing Driver entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MainBundle:Driver')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Driver entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            
            $this->addFlash(
                'success',
                'El chofer se ha grabado correctamente.'
            );

            return $this->redirect($this->generateUrl('driver_show', array('id' => $id)));
        }

        return $this->render('MainBundle:Driver:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Driver entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MainBundle:Driver')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Driver entity.');
            }

            $em->remove($entity);
            $em->flush();
            
            $this->addFlash(
                'success',
                'El chofer se ha eliminado correctamente.'
            );
        }

        return $this->redirect($this->generateUrl('driver'));
    }

    /**
     * Creates a form to delete a Driver entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('driver_delete', array('id' => $id)))
            ->setMethod('DELETE')
            //->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
    
    public function getDriverByTransportAjaxAction($id){
        $response = array();
        
        $getResult = $this->getDoctrine()->getManager()->getRepository('MainBundle:Driver')->findBy(array('transport' => $id));
        foreach ($getResult as $result) {
            $res = array();
            $res[0] = $result->getId();
            $res[1] = $result->getDni();
            $res[2] = $result->getName();
            $res[3] = $result->getSurname();
            array_push($response, $res);
        }
             
        return new JsonResponse($response);
    }
}
