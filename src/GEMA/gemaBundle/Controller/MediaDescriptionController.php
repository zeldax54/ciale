<?php

namespace GEMA\gemaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use GEMA\gemaBundle\Entity\MediaDescription;
use GEMA\gemaBundle\Form\MediaDescriptionType;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * MediaDescription controller.
 *
 */
class MediaDescriptionController extends Controller
{

    /**
     * Lists all MediaDescription entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = array();
        if ($request->isXmlHttpRequest()) {
            $repo = $em->getRepository('gemaBundle:MediaDescription');
            $qb = $repo->filtrar($request);
            $result = $this->get("gema.utiles")->paginar($request->get("current"), $request->get("rowCount"), $qb->getQuery());
            return new JsonResponse($result);
        } else {
            $entities = $em->getRepository('gemaBundle:MediaDescription')->findAll();
        }
        $accion = 'Listar Expedientes de MediaDescription';
        $this->get("gema.utiles")->traza($accion);
        
        

        return $this->render('gemaBundle:MediaDescription:index.html.twig', array(
                    'entities' => $entities,
        ));
    }
    /**
     * Creates a new MediaDescription entity.
     *
     */
    public function createAction(Request $request)
    {
    
        $entity = new MediaDescription();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $accion = '';
            $this->get("gema.utiles")->traza($accion);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_mediadescription_show', array('id' => $entity->getId())));
        }



    
       return $this->render('gemaBundle:MediaDescription:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a MediaDescription entity.
    *
    * @param MediaDescription $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(MediaDescription $entity)
    {
        $form = $this->createForm(new MediaDescriptionType(), $entity, array(
            'action' => $this->generateUrl('admin_mediadescription_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Crear'));

        return $form;
    }

    /**
     * Displays a form to create a new MediaDescription entity.
     *
     */
    public function newAction()
    {
        $entity = new MediaDescription();
        $form   = $this->createCreateForm($entity);

    
        return $this->render('gemaBundle:MediaDescription:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a MediaDescription entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:MediaDescription')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MediaDescription entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $accion = ' ';
        $this->get("gema.utiles")->traza($accion);

        return $this->render('gemaBundle:MediaDescription:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing MediaDescription entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:MediaDescription')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MediaDescription entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('gemaBundle:MediaDescription:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a MediaDescription entity.
    *
    * @param MediaDescription $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(MediaDescription $entity)
    {
        $form = $this->createForm(new MediaDescriptionType(), $entity, array(
            'action' => $this->generateUrl('admin_mediadescription_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar'));

        return $form;
    }
/**
    * Edits an existing MediaDescription entity.
*
    */
    public function updateAction(Request $request, $id)
{
    $em = $this->getDoctrine()->getManager();

    $entity = $em->getRepository('gemaBundle:MediaDescription')->find($id);

    if (!$entity) {
    throw $this->createNotFoundException('Unable to find MediaDescription entity.');
    }

    $deleteForm = $this->createDeleteForm($id);
    $editForm = $this->createEditForm($entity);
    $editForm->handleRequest($request);
    $accion = ' ';
    $this->get("gema.utiles")->traza($accion);
    $em->flush();

    
 

    
    return $this->redirect($this->generateUrl('admin_mediadescription_edit', array('id' => $id)));
  
}
    /**
     * Deletes a MediaDescription entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('gemaBundle:MediaDescription')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find MediaDescription entity.');
            }
            
        $accion = ' ';
        $this->get("gema.utiles")->traza($accion);
            $em->remove($entity);
            $em->flush();

        return $this->redirect($this->generateUrl('admin_mediadescription'));
    }

    /**
     * Creates a form to delete a MediaDescription entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_mediadescription_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Eliminar'))
            ->getForm()
        ;
    }
}
