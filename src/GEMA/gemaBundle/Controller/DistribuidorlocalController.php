<?php

namespace GEMA\gemaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use GEMA\gemaBundle\Entity\Distribuidorlocal;
use GEMA\gemaBundle\Form\DistribuidorlocalType;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Distribuidorlocal controller.
 *
 */
class DistribuidorlocalController extends Controller
{

    /**
     * Lists all Distribuidorlocal entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = array();
        if ($request->isXmlHttpRequest()) {
            $repo = $em->getRepository('gemaBundle:Distribuidorlocal');
            $qb = $repo->filtrar($request);
            $result = $this->get("gema.utiles")->paginar($request->get("current"), $request->get("rowCount"), $qb->getQuery());
            return new JsonResponse($result);
        } else {
            $entities = $em->getRepository('gemaBundle:Distribuidorlocal')->findAll();
        }
        $accion = 'Listar Expedientes de Distribuidorlocal';
        $this->get("gema.utiles")->traza($accion);
        
        

        return $this->render('gemaBundle:Distribuidorlocal:index.html.twig', array(
                    'entities' => $entities,
        ));
    }
    /**
     * Creates a new Distribuidorlocal entity.
     *
     */
    public function createAction(Request $request)
    {
    
        $entity = new Distribuidorlocal();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $accion = '';
            $this->get("gema.utiles")->traza($accion);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_distribuidorlocal_show', array('id' => $entity->getId())));
        }



    
       return $this->render('gemaBundle:Distribuidorlocal:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a Distribuidorlocal entity.
    *
    * @param Distribuidorlocal $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Distribuidorlocal $entity)
    {
        $form = $this->createForm(new DistribuidorlocalType(), $entity, array(
            'action' => $this->generateUrl('admin_distribuidorlocal_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Crear'));

        return $form;
    }

    /**
     * Displays a form to create a new Distribuidorlocal entity.
     *
     */
    public function newAction()
    {
        $entity = new Distribuidorlocal();
        $form   = $this->createCreateForm($entity);

    
        return $this->render('gemaBundle:Distribuidorlocal:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Distribuidorlocal entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:Distribuidorlocal')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Distribuidorlocal entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $accion = ' ';
        $this->get("gema.utiles")->traza($accion);

        return $this->render('gemaBundle:Distribuidorlocal:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Distribuidorlocal entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:Distribuidorlocal')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Distribuidorlocal entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('gemaBundle:Distribuidorlocal:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Distribuidorlocal entity.
    *
    * @param Distribuidorlocal $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Distribuidorlocal $entity)
    {
        $form = $this->createForm(new DistribuidorlocalType(), $entity, array(
            'action' => $this->generateUrl('admin_distribuidorlocal_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar'));

        return $form;
    }
/**
    * Edits an existing Distribuidorlocal entity.
*
    */
    public function updateAction(Request $request, $id)
{
    $em = $this->getDoctrine()->getManager();

    $entity = $em->getRepository('gemaBundle:Distribuidorlocal')->find($id);

    if (!$entity) {
    throw $this->createNotFoundException('Unable to find Distribuidorlocal entity.');
    }

    $deleteForm = $this->createDeleteForm($id);
    $editForm = $this->createEditForm($entity);
    $editForm->handleRequest($request);
    $accion = ' ';
    $this->get("gema.utiles")->traza($accion);
    $em->flush();

    
 

    
    return $this->redirect($this->generateUrl('admin_distribuidorlocal_edit', array('id' => $id)));
  
}
    /**
     * Deletes a Distribuidorlocal entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('gemaBundle:Distribuidorlocal')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Distribuidorlocal entity.');
            }
            
        $accion = ' ';
        $this->get("gema.utiles")->traza($accion);
            $em->remove($entity);
            $em->flush();

        return $this->redirect($this->generateUrl('admin_distribuidorlocal'));
    }

    /**
     * Creates a form to delete a Distribuidorlocal entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_distribuidorlocal_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Eliminar'))
            ->getForm()
        ;
    }
}
