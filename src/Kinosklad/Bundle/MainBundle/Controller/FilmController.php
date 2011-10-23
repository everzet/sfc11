<?php

namespace Kinosklad\Bundle\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Kinosklad\Bundle\MainBundle\Entity\Film;
use Kinosklad\Bundle\MainBundle\Form\FilmType;
use Kinosklad\Bundle\MainBundle\Form\Proxy\FilmProxy;

/**
 * Film controller.
 *
 */
class FilmController extends Controller
{
    /**
     * Lists all Film entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('KinoskladMainBundle:Film')->findAll();

        return $this->render('KinoskladMainBundle:Film:index.html.twig', array(
            'entities' => $entities
        ));
    }

    /**
     * Finds and displays a Film entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('KinoskladMainBundle:Film')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Film entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('KinoskladMainBundle:Film:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),

        ));
    }

    /**
     * Displays a form to create a new Film entity.
     *
     */
    public function newAction()
    {
        $entity = new Film();
        $form   = $this->createForm(new FilmType(), new FilmProxy($entity));

        return $this->render('KinoskladMainBundle:Film:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }

    /**
     * Creates a new Film entity.
     *
     */
    public function createAction()
    {
        $entity  = new Film();
        $request = $this->getRequest();
        $form    = $this->createForm(new FilmType(), new FilmProxy($entity));
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('films_show', array('id' => $entity->getId())));

        }

        return $this->render('KinoskladMainBundle:Film:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }

    /**
     * Displays a form to edit an existing Film entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('KinoskladMainBundle:Film')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Film entity.');
        }

        $editForm = $this->createForm(new FilmType(), new FilmProxy($entity));
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('KinoskladMainBundle:Film:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Film entity.
     *
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('KinoskladMainBundle:Film')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Film entity.');
        }

        $editForm   = $this->createForm(new FilmType(), new FilmProxy($entity));
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity->translate());
            $em->flush();

            return $this->redirect($this->generateUrl('films_edit', array('id' => $id)));
        }

        return $this->render('KinoskladMainBundle:Film:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Film entity.
     *
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('KinoskladMainBundle:Film')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Film entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('films'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
