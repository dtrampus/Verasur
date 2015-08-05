<?php

namespace MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use MainBundle\Entity\Egress;
use MainBundle\Form\EgressType;

/**
 * Egress controller.
 *
 */
class EgressController extends Controller
{

    /**
     * Lists all Egress entities.
     *
     */
    public function indexAction()
    {
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
    public function createAction(Request $request)
    {
        $entity = new Egress();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->addFlash(
                'success',
                'El egreso se ha declarado correctamente.'
            );
            
            return $this->redirect($this->generateUrl('egress_show', array('id' => $entity->getId())));
        }

        return $this->render('MainBundle:Egress:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Egress entity.
     *
     * @param Egress $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Egress $entity)
    {
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
    public function newAction()
    {
        $entity = new Egress();
        $form   = $this->createCreateForm($entity);

        return $this->render('MainBundle:Egress:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Egress entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MainBundle:Egress')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Egress entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MainBundle:Egress:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Egress entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MainBundle:Egress')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Egress entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MainBundle:Egress:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
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
    private function createEditForm(Egress $entity)
    {
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
    public function updateAction(Request $request, $id)
    {
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
                'success',
                'El egreso se ha grabado correctamente.'
            );
            
            return $this->redirect($this->generateUrl('egress_show', array('id' => $id)));
        }

        return $this->render('MainBundle:Egress:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Egress entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
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
                'success',
                'El egresso se ha eliminado correctamente.'
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
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('egress_delete', array('id' => $id)))
            ->setMethod('DELETE')
//            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}

