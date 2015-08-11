<?php

namespace MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use MainBundle\Entity\Transport;
use MainBundle\Form\TransportType;

/**
 * Transport controller.
 *
 */
class TransportController extends Controller
{

    /**
     * Lists all Transport entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MainBundle:Transport')->findAll();

        return $this->render('MainBundle:Transport:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Transport entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Transport();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $entity->setActive(true);
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->addFlash(
                'success',
                'El transporte se ha creado correctamente.'
            );
            
            return $this->redirect($this->generateUrl('transport_show', array('id' => $entity->getId())));
        }

        return $this->render('MainBundle:Transport:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Transport entity.
     *
     * @param Transport $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Transport $entity)
    {
        $form = $this->createForm(new TransportType(), $entity, array(
            'action' => $this->generateUrl('transport_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Crear', 'attr' => array('class' => 'btn-primary')));

        return $form;
    }

    /**
     * Displays a form to create a new Transport entity.
     *
     */
    public function newAction()
    {
        $entity = new Transport();
        $form   = $this->createCreateForm($entity);

        return $this->render('MainBundle:Transport:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Transport entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MainBundle:Transport')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Transport entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MainBundle:Transport:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Transport entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MainBundle:Transport')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Transport entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MainBundle:Transport:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Transport entity.
    *
    * @param Transport $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Transport $entity)
    {
        $form = $this->createForm(new TransportType(), $entity, array(
            'action' => $this->generateUrl('transport_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Grabar', 'attr' => array('class' => 'btn-primary')));

        return $form;
    }
    /**
     * Edits an existing Transport entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MainBundle:Transport')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Transport entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            $this->addFlash(
                'success',
                'El transporte se ha grabado correctamente.'
            );
            
            return $this->redirect($this->generateUrl('transport_show', array('id' => $id)));
        }

        return $this->render('MainBundle:Transport:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Transport entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MainBundle:Transport')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Transport entity.');
            }
            
            $em->getRepository('MainBundle:Transport')->remove($entity);
            $em->flush();

            $this->addFlash(
                'success',
                'El transporte se ha eliminado correctamente.'
            );
            
            //$em->remove($entity);
            //$em->flush();
        }

        return $this->redirect($this->generateUrl('transport'));
    }

    /**
     * Creates a form to delete a Transport entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('transport_delete', array('id' => $id)))
            ->setMethod('DELETE')
            //->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
