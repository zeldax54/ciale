<?php

namespace GEMA\gemaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use GEMA\gemaBundle\Entity\Distribuidor;
use GEMA\gemaBundle\Form\DistribuidorType;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Distribuidor controller.
 *
 */
class DistribuidorController extends Controller
{

    /**
     * Lists all Distribuidor entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = array();
        if ($request->isXmlHttpRequest()) {
            $repo = $em->getRepository('gemaBundle:Distribuidor');
            $qb = $repo->filtrar($request);
            $result = $this->get("gema.utiles")->paginar($request->get("current"), $request->get("rowCount"), $qb->getQuery());
            return new JsonResponse($result);
        } else {
            $entities = $em->getRepository('gemaBundle:Distribuidor')->findAll();
        }
        $accion = 'Listar Expedientes de Distribuidor';
        $this->get("gema.utiles")->traza($accion);
        
        

        return $this->render('gemaBundle:Distribuidor:index.html.twig', array(
                    'entities' => $entities,
        ));
    }
    /**
     * Creates a new Distribuidor entity.
     *
     */
    public function createAction(Request $request)
    {
    
        $entity = new Distribuidor();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $accion = '';
            $this->get("gema.utiles")->traza($accion);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_distribuidor'));
        }



    
       return $this->render('gemaBundle:Distribuidor:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a Distribuidor entity.
    *
    * @param Distribuidor $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Distribuidor $entity)
    {
        $form = $this->createForm(new DistribuidorType(), $entity, array(
            'action' => $this->generateUrl('admin_distribuidor_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Crear'));

        return $form;
    }

    /**
     * Displays a form to create a new Distribuidor entity.
     *
     */
    public function newAction()
    {
        $entity = new Distribuidor();
        $form   = $this->createCreateForm($entity);

    
        return $this->render('gemaBundle:Distribuidor:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Distribuidor entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:Distribuidor')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Distribuidor entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $accion = ' ';
        $this->get("gema.utiles")->traza($accion);

        return $this->render('gemaBundle:Distribuidor:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Distribuidor entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:Distribuidor')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Distribuidor entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('gemaBundle:Distribuidor:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Distribuidor entity.
    *
    * @param Distribuidor $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Distribuidor $entity)
    {
        $form = $this->createForm(new DistribuidorType(), $entity, array(
            'action' => $this->generateUrl('admin_distribuidor_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar'));

        return $form;
    }
/**
    * Edits an existing Distribuidor entity.
*
    */
    public function updateAction(Request $request, $id)
{
    $em = $this->getDoctrine()->getManager();

    $entity = $em->getRepository('gemaBundle:Distribuidor')->find($id);

    if (!$entity) {
    throw $this->createNotFoundException('Unable to find Distribuidor entity.');
    }

    $deleteForm = $this->createDeleteForm($id);
    $editForm = $this->createEditForm($entity);
    $editForm->handleRequest($request);
    $accion = ' ';
    $this->get("gema.utiles")->traza($accion);
    $em->flush();

    
 

    
    return $this->redirect($this->generateUrl('admin_distribuidor_edit', array('id' => $id)));
  
}
    /**
     * Deletes a Distribuidor entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('gemaBundle:Distribuidor')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Distribuidor entity.');
            }
            
        $accion = ' ';
        $this->get("gema.utiles")->traza($accion);
            $em->remove($entity);
            $em->flush();

        return $this->redirect($this->generateUrl('admin_distribuidor'));
    }

    /**
     * Creates a form to delete a Distribuidor entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_distribuidor_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Eliminar'))
            ->getForm()
        ;
    }
}
