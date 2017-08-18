<?php

namespace GEMA\gemaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use GEMA\gemaBundle\Entity\Configuracion;
use GEMA\gemaBundle\Form\ConfiguracionType;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Configuracion controller.
 *
 */


class ConfiguracionController extends Controller
{

    /**
     * Lists all Configuracion entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = array();
        if ($request->isXmlHttpRequest()) {
            $repo = $em->getRepository('gemaBundle:Configuracion');
            $qb = $repo->filtrar($request);
            $result = $this->get("gema.utiles")->paginar($request->get("current"), $request->get("rowCount"), $qb->getQuery());
            return new JsonResponse($result);
        } else {
            $entities = $em->getRepository('gemaBundle:Configuracion')->findAll();
        }
        $accion = 'Listar Expedientes de Configuracion';
        $this->get("gema.utiles")->traza($accion);
        
        

        return $this->render('gemaBundle:Configuracion:index.html.twig', array(
                    'entities' => $entities,
        ));
    }
    /**
     * Creates a new Configuracion entity.
     *
     */
    public function createAction(Request $request)
    {
    
        $entity = new Configuracion();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $accion = '';
            $this->get("gema.utiles")->traza($accion);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_configuracion_show', array('id' => $entity->getId())));
        }



    
       return $this->render('gemaBundle:Configuracion:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a Configuracion entity.
    *
    * @param Configuracion $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Configuracion $entity)
    {
        $form = $this->createForm(new ConfiguracionType(), $entity, array(
            'action' => $this->generateUrl('admin_configuracion_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Crear'));

        return $form;
    }

    /**
     * Displays a form to create a new Configuracion entity.
     *
     */
    public function newAction()
    {
        $entity = new Configuracion();
        $form   = $this->createCreateForm($entity);

    
        return $this->render('gemaBundle:Configuracion:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Configuracion entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:Configuracion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Configuracion entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $accion = ' ';
        $this->get("gema.utiles")->traza($accion);

        return $this->render('gemaBundle:Configuracion:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Configuracion entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:Configuracion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Configuracion entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('gemaBundle:Configuracion:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Configuracion entity.
    *
    * @param Configuracion $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Configuracion $entity)
    {
        $form = $this->createForm(new ConfiguracionType(), $entity, array(
            'action' => $this->generateUrl('admin_configuracion_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar'));

        return $form;
    }
/**
    * Edits an existing Configuracion entity.
*
    */
    public function updateAction(Request $request, $id)
{
    $em = $this->getDoctrine()->getManager();

    $entity = $em->getRepository('gemaBundle:Configuracion')->find($id);

    if (!$entity) {
    throw $this->createNotFoundException('Unable to find Configuracion entity.');
    }

    $deleteForm = $this->createDeleteForm($id);
    $editForm = $this->createEditForm($entity);
    $editForm->handleRequest($request);
    $accion = ' ';
    $this->get("gema.utiles")->traza($accion);
    $em->flush();

    
 

    
    return $this->redirect($this->generateUrl('admin_configuracion_edit', array('id' => $id)));
  
}
    /**
     * Deletes a Configuracion entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('gemaBundle:Configuracion')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Configuracion entity.');
            }
            
        $accion = ' ';
        $this->get("gema.utiles")->traza($accion);
            $em->remove($entity);
            $em->flush();

        return $this->redirect($this->generateUrl('admin_configuracion'));
    }

    /**
     * Creates a form to delete a Configuracion entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_configuracion_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Eliminar'))
            ->getForm()
        ;
    }



}
