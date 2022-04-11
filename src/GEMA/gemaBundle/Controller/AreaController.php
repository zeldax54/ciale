<?php

namespace GEMA\gemaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use GEMA\gemaBundle\Entity\Area;
use GEMA\gemaBundle\Form\AreaType;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Area controller.
 *
 */
class AreaController extends Controller
{

    /**
     * Lists all Area entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = array();
        if ($request->isXmlHttpRequest()) {
            $repo = $em->getRepository('gemaBundle:Area');
            $qb = $repo->filtrar($request);
            $result = $this->get("gema.utiles")->paginar($request->get("current"), $request->get("rowCount"), $qb->getQuery());
            return new JsonResponse($result);
        } else {
            $entities = $em->getRepository('gemaBundle:Area')->findAll();
        }
        $accion = 'Listar Expedientes de Area';
        $this->get("gema.utiles")->traza($accion);

        return $this->render('gemaBundle:Area:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Area entity.
     *
     */
    public function createAction(Request $request)
    {

        $entity = new Area();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $accion = '';
            $this->get("gema.utiles")->traza($accion);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('area_show', array('id' => $entity->getId())));
        }

        return $this->render('gemaBundle:Area:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Area entity.
     *
     * @param Area $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Area $entity)
    {
        $form = $this->createForm(new AreaType(), $entity, array(
            'action' => $this->generateUrl('area_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Crear'));

        return $form;
    }

    /**
     * Displays a form to create a new Area entity.
     *
     */
    public function newAction()
    {
        $entity = new Area();
        $form   = $this->createCreateForm($entity);


        return $this->render('gemaBundle:Area:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Area entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:Area')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Area entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $accion = ' ';
        $this->get("gema.utiles")->traza($accion);

        return $this->render('gemaBundle:Area:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Area entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:Area')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Area entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('gemaBundle:Area:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Area entity.
     *
     * @param Area $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Area $entity)
    {
        $form = $this->createForm(new AreaType(), $entity, array(
            'action' => $this->generateUrl('area_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar'));

        return $form;
    }
    /**
     * Edits an existing Area entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:Area')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Area entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        $accion = ' ';
        $this->get("gema.utiles")->traza($accion);
        $em->flush();





        return $this->redirect($this->generateUrl('area_edit', array('id' => $id)));

    }
    /**
     * Deletes a Area entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('gemaBundle:Area')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Area entity.');
        }

        $accion = ' ';
        $this->get("gema.utiles")->traza($accion);
        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('area'));
    }

    /**
     * Creates a form to delete a Area entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('area_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Eliminar'))
            ->getForm()
            ;
    }
}
