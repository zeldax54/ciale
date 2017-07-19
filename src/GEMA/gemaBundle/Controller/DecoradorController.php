<?php

namespace GEMA\gemaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use GEMA\gemaBundle\Entity\Decorador;
use GEMA\gemaBundle\Form\DecoradorType;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Decorador controller.
 *
 */
class DecoradorController extends Controller
{

    /**
     * Lists all Decorador entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = array();
        if ($request->isXmlHttpRequest()) {
            $repo = $em->getRepository('gemaBundle:Decorador');
            $qb = $repo->filtrar($request);
            $result = $this->get("gema.utiles")->paginar($request->get("current"), $request->get("rowCount"), $qb->getQuery());
            return new JsonResponse($result);
        } else {
            $entities = $em->getRepository('gemaBundle:Decorador')->findAll();
        }
        $accion = 'Listar Expedientes de Decorador';
        $this->get("gema.utiles")->traza($accion);
        
        

        return $this->render('gemaBundle:Decorador:index.html.twig', array(
                    'entities' => $entities,
        ));
    }
    /**
     * Creates a new Decorador entity.
     *
     */
    public function createAction(Request $request)
    {
    
        $entity = new Decorador();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $accion = '';
            $this->get("gema.utiles")->traza($accion);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_decorador_show', array('id' => $entity->getId())));
        }



    
       return $this->render('gemaBundle:Decorador:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a Decorador entity.
    *
    * @param Decorador $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Decorador $entity)
    {
        $form = $this->createForm(new DecoradorType(), $entity, array(
            'action' => $this->generateUrl('admin_decorador_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Crear'));

        return $form;
    }

    /**
     * Displays a form to create a new Decorador entity.
     *
     */
    public function newAction()
    {
        $entity = new Decorador();
        $form   = $this->createCreateForm($entity);

    
        return $this->render('gemaBundle:Decorador:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Decorador entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:Decorador')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Decorador entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $accion = ' ';
        $this->get("gema.utiles")->traza($accion);

        return $this->render('gemaBundle:Decorador:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Decorador entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:Decorador')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Decorador entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('gemaBundle:Decorador:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Decorador entity.
    *
    * @param Decorador $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Decorador $entity)
    {
        $form = $this->createForm(new DecoradorType(), $entity, array(
            'action' => $this->generateUrl('admin_decorador_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar'));

        return $form;
    }
/**
    * Edits an existing Decorador entity.
*
    */
    public function updateAction(Request $request, $id)
{
    $em = $this->getDoctrine()->getManager();

    $entity = $em->getRepository('gemaBundle:Decorador')->find($id);

    if (!$entity) {
    throw $this->createNotFoundException('Unable to find Decorador entity.');
    }

    $deleteForm = $this->createDeleteForm($id);
    $editForm = $this->createEditForm($entity);
    $editForm->handleRequest($request);
    $accion = ' ';
    $this->get("gema.utiles")->traza($accion);
    $em->flush();

    
 

    
    return $this->redirect($this->generateUrl('admin_decorador_edit', array('id' => $id)));
  
}
    /**
     * Deletes a Decorador entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('gemaBundle:Decorador')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Decorador entity.');
            }
            
        $accion = ' ';
        $this->get("gema.utiles")->traza($accion);
            $em->remove($entity);
            $em->flush();

        return $this->redirect($this->generateUrl('admin_decorador'));
    }

    /**
     * Creates a form to delete a Decorador entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_decorador_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Eliminar'))
            ->getForm()
        ;
    }
}
