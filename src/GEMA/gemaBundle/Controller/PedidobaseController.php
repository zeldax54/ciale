<?php

namespace GEMA\gemaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use GEMA\gemaBundle\Entity\Pedidobase;
use GEMA\gemaBundle\Form\PedidobaseType;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Pedidobase controller.
 *
 */
class PedidobaseController extends Controller
{

    /**
     * Lists all Pedidobase entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = array();
        if ($request->isXmlHttpRequest()) {
            $repo = $em->getRepository('gemaBundle:Pedidobase');
            $qb = $repo->filtrar($request);
            $result = $this->get("gema.utiles")->paginar($request->get("current"), $request->get("rowCount"), $qb->getQuery());
            return new JsonResponse($result);
        } else {
            $entities = $em->getRepository('gemaBundle:Pedidobase')->findAll();
        }
        $accion = 'Listar Expedientes de Pedidobase';
        $this->get("gema.utiles")->traza($accion);
        
        

        return $this->render('gemaBundle:Pedidobase:index.html.twig', array(
                    'entities' => $entities,
        ));
    }
    /**
     * Creates a new Pedidobase entity.
     *
     */
    public function createAction(Request $request)
    {
    
        $entity = new Pedidobase();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $accion = '';
            $this->get("gema.utiles")->traza($accion);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('pedidobase_show', array('id' => $entity->getId())));
        }



    
       return $this->render('gemaBundle:Pedidobase:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a Pedidobase entity.
    *
    * @param Pedidobase $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Pedidobase $entity)
    {
        $form = $this->createForm(new PedidobaseType(), $entity, array(
            'action' => $this->generateUrl('pedidobase_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Crear'));

        return $form;
    }

    /**
     * Displays a form to create a new Pedidobase entity.
     *
     */
    public function newAction()
    {
        $entity = new Pedidobase();
        $form   = $this->createCreateForm($entity);

    
        return $this->render('gemaBundle:Pedidobase:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Pedidobase entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:Pedidobase')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Pedidobase entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $accion = ' ';
        $this->get("gema.utiles")->traza($accion);

        return $this->render('gemaBundle:Pedidobase:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Pedidobase entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:Pedidobase')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Pedidobase entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('gemaBundle:Pedidobase:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Pedidobase entity.
    *
    * @param Pedidobase $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Pedidobase $entity)
    {
        $form = $this->createForm(new PedidobaseType(), $entity, array(
            'action' => $this->generateUrl('pedidobase_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar'));

        return $form;
    }
/**
    * Edits an existing Pedidobase entity.
*
    */
    public function updateAction(Request $request, $id)
{
    $em = $this->getDoctrine()->getManager();

    $entity = $em->getRepository('gemaBundle:Pedidobase')->find($id);

    if (!$entity) {
    throw $this->createNotFoundException('Unable to find Pedidobase entity.');
    }

    $deleteForm = $this->createDeleteForm($id);
    $editForm = $this->createEditForm($entity);
    $editForm->handleRequest($request);
    $accion = ' ';
    $this->get("gema.utiles")->traza($accion);
    $em->flush();

    
 

    
    return $this->redirect($this->generateUrl('pedidobase'));
  
}
    /**
     * Deletes a Pedidobase entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('gemaBundle:Pedidobase')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Pedidobase entity.');
            }
            
        $accion = ' ';
        $this->get("gema.utiles")->traza($accion);
            $em->remove($entity);
            $em->flush();

        return $this->redirect($this->generateUrl('pedidobase'));
    }

    /**
     * Creates a form to delete a Pedidobase entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('pedidobase_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Eliminar'))
            ->getForm()
        ;
    }
}
