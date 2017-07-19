<?php

namespace GEMA\gemaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use GEMA\gemaBundle\Entity\TipoRaza;
use GEMA\gemaBundle\Form\TipoRazaType;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * TipoRaza controller.
 *
 */
class TipoRazaController extends Controller
{

    /**
     * Lists all TipoRaza entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = array();
        if ($request->isXmlHttpRequest()) {
            $repo = $em->getRepository('gemaBundle:TipoRaza');
            $qb = $repo->filtrar($request);
            $result = $this->get("gema.utiles")->paginar($request->get("current"), $request->get("rowCount"), $qb->getQuery());
            return new JsonResponse($result);
        } else {
            $entities = $em->getRepository('gemaBundle:TipoRaza')->findAll();
        }
        $accion = 'Listar Expedientes de TipoRaza';
        $this->get("gema.utiles")->traza($accion);
        
        

        return $this->render('gemaBundle:TipoRaza:index.html.twig', array(
                    'entities' => $entities,
        ));
    }
    /**
     * Creates a new TipoRaza entity.
     *
     */
    public function createAction(Request $request)
    {
    
        $entity = new TipoRaza();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $accion = '';
            $this->get("gema.utiles")->traza($accion);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_tiporaza'));
        }



    
       return $this->render('gemaBundle:TipoRaza:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a TipoRaza entity.
    *
    * @param TipoRaza $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(TipoRaza $entity)
    {
        $form = $this->createForm(new TipoRazaType(), $entity, array(
            'action' => $this->generateUrl('admin_tiporaza_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Crear'));

        return $form;
    }

    /**
     * Displays a form to create a new TipoRaza entity.
     *
     */
    public function newAction()
    {
        $entity = new TipoRaza();
        $form   = $this->createCreateForm($entity);

    
        return $this->render('gemaBundle:TipoRaza:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a TipoRaza entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:TipoRaza')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TipoRaza entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $accion = ' ';
        $this->get("gema.utiles")->traza($accion);

        return $this->render('gemaBundle:TipoRaza:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing TipoRaza entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:TipoRaza')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TipoRaza entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('gemaBundle:TipoRaza:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a TipoRaza entity.
    *
    * @param TipoRaza $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(TipoRaza $entity)
    {
        $form = $this->createForm(new TipoRazaType(), $entity, array(
            'action' => $this->generateUrl('admin_tiporaza_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar'));

        return $form;
    }
/**
    * Edits an existing TipoRaza entity.
*
    */
    public function updateAction(Request $request, $id)
{
    $em = $this->getDoctrine()->getManager();

    $entity = $em->getRepository('gemaBundle:TipoRaza')->find($id);

    if (!$entity) {
    throw $this->createNotFoundException('Unable to find TipoRaza entity.');
    }

    $deleteForm = $this->createDeleteForm($id);
    $editForm = $this->createEditForm($entity);
    $editForm->handleRequest($request);
    $accion = ' ';
    $this->get("gema.utiles")->traza($accion);
    $em->flush();

    
 

    
    return $this->redirect($this->generateUrl('admin_tiporaza'));
  
}
    /**
     * Deletes a TipoRaza entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('gemaBundle:TipoRaza')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find TipoRaza entity.');
            }
            
        $accion = ' ';
        $this->get("gema.utiles")->traza($accion);
            $em->remove($entity);
            $em->flush();

        return $this->redirect($this->generateUrl('admin_tiporaza'));
    }

    /**
     * Creates a form to delete a TipoRaza entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_tiporaza_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Eliminar'))
            ->getForm()
        ;
    }
}
