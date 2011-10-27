<?php

namespace Kinosklad\Bundle\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Kinosklad\Bundle\MainBundle\Entity\Genre;
use Kinosklad\Bundle\MainBundle\Form\GenreType;
use Kinosklad\Bundle\MainBundle\Form\Proxy\GenreProxy;

/**
 * Genre controller.
 *
 */
class GenreController extends Controller
{
    /**
     * Lists all Genre entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('KinoskladMainBundle:Genre')->findAll();

        return $this->render('KinoskladMainBundle:Genre:index.html.twig', array(
            'entities' => $entities
        ));
    }

    /**
     * Displays a form to create a new Genre entity.
     *
     */
    public function newAction()
    {
        $entity = new Genre();
        $form   = $this->createForm(new GenreType(), new GenreProxy($entity));

        return $this->render('KinoskladMainBundle:Genre:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }

    /**
     * Creates a new Genre entity.
     *
     */
    public function createAction()
    {
        $entity  = new Genre();
        $request = $this->getRequest();
        $form    = $this->createForm(new GenreType(), new GenreProxy($entity));
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('genres'));

        }

        return $this->render('KinoskladMainBundle:Genre:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }

    /**
     * Displays a form to edit an existing Genre entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('KinoskladMainBundle:Genre')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Genre entity.');
        }

        $editForm   = $this->createForm(new GenreType(), new GenreProxy($entity));
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('KinoskladMainBundle:Genre:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Genre entity.
     *
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('KinoskladMainBundle:Genre')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Genre entity.');
        }

        $editForm   = $this->createForm(new GenreType(), new GenreProxy($entity));
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity->translate());
            $em->flush();

            return $this->redirect($this->generateUrl('genres'));
        }

        return $this->render('KinoskladMainBundle:Genre:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Genre entity.
     *
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('KinoskladMainBundle:Genre')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Genre entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('genres'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
