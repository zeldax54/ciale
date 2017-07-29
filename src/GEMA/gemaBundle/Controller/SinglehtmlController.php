<?php

namespace GEMA\gemaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use GEMA\gemaBundle\Entity\Singlehtml;
use GEMA\gemaBundle\Form\SinglehtmlType;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Singlehtml controller.
 *
 */
class SinglehtmlController extends Controller
{

    /**
     * Lists all Singlehtml entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = array();
        if ($request->isXmlHttpRequest()) {
            $repo = $em->getRepository('gemaBundle:Singlehtml');
            $qb = $repo->filtrar($request);
            $result = $this->get("gema.utiles")->paginar($request->get("current"), $request->get("rowCount"), $qb->getQuery());
            return new JsonResponse($result);
        } else {
            $entities = $em->getRepository('gemaBundle:Singlehtml')->findAll();
        }
        $accion = 'Listar Expedientes de Singlehtml';
        $this->get("gema.utiles")->traza($accion);
        
        

        return $this->render('gemaBundle:Singlehtml:index.html.twig', array(
                    'entities' => $entities,
        ));
    }
    /**
     * Creates a new Singlehtml entity.
     *
     */
    public function createAction(Request $request)
    {
    
        $entity = new Singlehtml();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $accion = '';
            $this->get("gema.utiles")->traza($accion);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_singlehtml'));
        }



    
       return $this->render('gemaBundle:Singlehtml:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a Singlehtml entity.
    *
    * @param Singlehtml $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Singlehtml $entity)
    {
        $form = $this->createForm(new SinglehtmlType(), $entity, array(
            'action' => $this->generateUrl('admin_singlehtml_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Crear'));

        return $form;
    }

    /**
     * Displays a form to create a new Singlehtml entity.
     *
     */
    public function newAction()
    {
        $entity = new Singlehtml();
        $form   = $this->createCreateForm($entity);

    
        return $this->render('gemaBundle:Singlehtml:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Singlehtml entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:Singlehtml')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Singlehtml entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $accion = ' ';
        $this->get("gema.utiles")->traza($accion);

        return $this->render('gemaBundle:Singlehtml:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Singlehtml entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:Singlehtml')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Singlehtml entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('gemaBundle:Singlehtml:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Singlehtml entity.
    *
    * @param Singlehtml $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Singlehtml $entity)
    {
        $form = $this->createForm(new SinglehtmlType(), $entity, array(
            'action' => $this->generateUrl('admin_singlehtml_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar'));

        return $form;
    }
/**
    * Edits an existing Singlehtml entity.
*
    */
    public function updateAction(Request $request, $id)
{
    $em = $this->getDoctrine()->getManager();

    $entity = $em->getRepository('gemaBundle:Singlehtml')->find($id);

    if (!$entity) {
    throw $this->createNotFoundException('Unable to find Singlehtml entity.');
    }

    $deleteForm = $this->createDeleteForm($id);
    $editForm = $this->createEditForm($entity);
    $editForm->handleRequest($request);
    $accion = ' ';
    $this->get("gema.utiles")->traza($accion);
    $em->flush();

    
 

    
    return $this->redirect($this->generateUrl('admin_singlehtml_edit', array('id' => $id)));
  
}
    /**
     * Deletes a Singlehtml entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('gemaBundle:Singlehtml')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Singlehtml entity.');
            }
            
        $accion = 'HTML borrado';
        $this->get("gema.utiles")->traza($accion);
            $em->remove($entity);
            $em->flush();

        return $this->redirect($this->generateUrl('admin_singlehtml'));
    }

    /**
     * Creates a form to delete a Singlehtml entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_singlehtml_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Eliminar'))
            ->getForm()
        ;
    }
}
