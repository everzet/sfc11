<?php

namespace Kinosklad\Bundle\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Kinosklad\Bundle\MainBundle\Entity\Film;
use Kinosklad\Bundle\MainBundle\Form\FilmType;
use Kinosklad\Bundle\MainBundle\Form\FilmLinkType;
use Kinosklad\Bundle\MainBundle\Form\Proxy\FilmProxy;
use Kinosklad\Bundle\MainBundle\Form\Proxy\FilmLinkProxy;

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
        $linkForm   = $this->createForm(new FilmLinkType(), new FilmLinkProxy());

        return $this->render('KinoskladMainBundle:Film:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
            'link_form'   => $linkForm->createView(),
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
        $entity->setAuthor($this->getSecurityContext()->getToken()->getUser());
        $request = $this->getRequest();
        $form    = $this->createForm(new FilmType(), new FilmProxy($entity));
        $form->bindRequest($request);

        if ($form->isValid()) {
            $form->getData()->evaluateUpload();
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

        if (!$this->getSecurityContext()->isGranted('EDIT', $entity)) {
            throw new AccessDeniedException();
        }

        $editForm = $this->createForm(new FilmType(), new FilmProxy($entity), array(
            'filmAlreadyHasImage' => null !== $entity->getImage()
        ));
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

        if (!$this->getSecurityContext()->isGranted('EDIT', $entity)) {
            throw new AccessDeniedException();
        }

        $editForm = $this->createForm(new FilmType(), new FilmProxy($entity), array(
            'filmAlreadyHasImage' => null !== $entity->getImage()
        ));
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $editForm->getData()->evaluateUpload();
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

    public function linkAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('KinoskladMainBundle:Film')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Film entity.');
        }

        if (!$this->getSecurityContext()->isGranted('ADD_LINK', $entity)) {
            throw new AccessDeniedException();
        }

        $request  = $this->getRequest();
        $linkForm = $this->createForm(new FilmLinkType(), new FilmLinkProxy($entity));
        $linkForm->bindRequest($request);

        if ($linkForm->isValid() && !$entity->hasLink($linkForm->getData()->url)) {
            $entity->addLink($linkForm->getData()->url);
            $em->persist($entity->translate());
            $em->flush();
        }

        return $this->redirect($this->generateUrl('films_show', array('id' => $id)));
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

            if (!$this->getSecurityContext()->isGranted('EDIT', $entity)) {
                throw new AccessDeniedException();
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

    private function getSecurityContext()
    {
        return $this->get('security.context');
    }
}
