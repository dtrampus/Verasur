<?php

namespace MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use MainBundle\Entity\Provider;
use MainBundle\Form\ProviderType;

/**
 * Provider controller.
 *
 */
class ProviderController extends Controller
{

    /**
     * Lists all Provider entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MainBundle:Provider')->findAll();

        return $this->render('MainBundle:Provider:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Provider entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Provider();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setActive(true);
            $em->persist($entity);
            $em->flush();
            
            $this->addFlash(
                'success',
                'El proveedor se ha ingresado correctamente.'
            );

            return $this->redirect($this->generateUrl('provider_show', array('id' => $entity->getId())));
        }

        return $this->render('MainBundle:Provider:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Provider entity.
     *
     * @param Provider $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Provider $entity)
    {
        $form = $this->createForm(new ProviderType(), $entity, array(
            'action' => $this->generateUrl('provider_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Crear', 'attr' => array('class' => 'btn-primary')));

        return $form;
    }

    /**
     * Displays a form to create a new Provider entity.
     *
     */
    public function newAction()
    {
        $entity = new Provider();
        $form   = $this->createCreateForm($entity);

        return $this->render('MainBundle:Provider:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Provider entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MainBundle:Provider')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Provider entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MainBundle:Provider:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Provider entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MainBundle:Provider')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Provider entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MainBundle:Provider:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Provider entity.
    *
    * @param Provider $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Provider $entity)
    {
        $form = $this->createForm(new ProviderType(), $entity, array(
            'action' => $this->generateUrl('provider_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Grabar', 'attr' => array('class' => 'btn-primary')));

        return $form;
    }
    /**
     * Edits an existing Provider entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MainBundle:Provider')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Provider entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->addFlash(
                'success',
                'El proveedor se ha editado correctamente.'
            );
            
            return $this->redirect($this->generateUrl('provider_show', array('id' => $id)));
            //return $this->redirect($this->generateUrl('provider_edit', array('id' => $id)));
        }
       
        return $this->render('MainBundle:Provider:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Provider entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MainBundle:Provider')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Provider entity.');
            }

            $em->getRepository('MainBundle:Provider')->remove($entity);
            $em->flush();
        }
        
        $this->addFlash(
            'success',
            'El proveedor se ha eliminado correctamente.'
        );
        return $this->redirect($this->generateUrl('provider'));
    }

    /**
     * Creates a form to delete a Provider entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('provider_delete', array('id' => $id)))
            ->setMethod('DELETE')
            //->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
