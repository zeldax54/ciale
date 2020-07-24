<?php

namespace GEMA\gemaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use GEMA\gemaBundle\Entity\Catalogo;
use GEMA\gemaBundle\Form\CatalogoType;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Catalogo controller.
 *
 */
class CatalogoController extends Controller
{

    /**
     * Lists all Catalogo entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = array();
        if ($request->isXmlHttpRequest()) {
            $repo = $em->getRepository('gemaBundle:Catalogo');
            $qb = $repo->filtrar($request);
            $result = $this->get("gema.utiles")->paginar($request->get("current"), $request->get("rowCount"), $qb->getQuery());
            return new JsonResponse($result);
        } else {
            $entities = $em->getRepository('gemaBundle:Catalogo')->findAll();
        }
        $accion = 'Listar Expedientes de Catalogo';
        $this->get("gema.utiles")->traza($accion);
        
        

        return $this->render('gemaBundle:Catalogo:index.html.twig', array(
                    'entities' => $entities,
        ));
    }
    /**
     * Creates a new Catalogo entity.
     *
     */
    public function createAction(Request $request)
    {
    
        $entity = new Catalogo();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $accion = '';
            $this->get("gema.utiles")->traza($accion);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('catalogo_show', array('id' => $entity->getId())));
        }



    
       return $this->render('gemaBundle:Catalogo:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a Catalogo entity.
    *
    * @param Catalogo $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Catalogo $entity)
    {
        $form = $this->createForm(new CatalogoType(), $entity, array(
            'action' => $this->generateUrl('catalogo_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Crear'));

        return $form;
    }

    /**
     * Displays a form to create a new Catalogo entity.
     *
     */
    public function newAction()
    {
        $entity = new Catalogo();
        $form   = $this->createCreateForm($entity);

    
        return $this->render('gemaBundle:Catalogo:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Catalogo entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:Catalogo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Catalogo entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $accion = ' ';
        $this->get("gema.utiles")->traza($accion);

        return $this->render('gemaBundle:Catalogo:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Catalogo entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:Catalogo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Catalogo entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('gemaBundle:Catalogo:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Catalogo entity.
    *
    * @param Catalogo $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Catalogo $entity)
    {
        $form = $this->createForm(new CatalogoType(), $entity, array(
            'action' => $this->generateUrl('catalogo_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar'));

        return $form;
    }
/**
    * Edits an existing Catalogo entity.
*
    */
    public function updateAction(Request $request, $id)
{
    $em = $this->getDoctrine()->getManager();

    $entity = $em->getRepository('gemaBundle:Catalogo')->find($id);

    if (!$entity) {
    throw $this->createNotFoundException('Unable to find Catalogo entity.');
    }

    $deleteForm = $this->createDeleteForm($id);
    $editForm = $this->createEditForm($entity);
    $editForm->handleRequest($request);
    $accion = ' ';
    $this->get("gema.utiles")->traza($accion);
    $em->flush();

    
 

    
    return $this->redirect($this->generateUrl('catalogo_edit', array('id' => $id)));
  
}
    /**
     * Deletes a Catalogo entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('gemaBundle:Catalogo')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Catalogo entity.');
            }
            
        $accion = ' ';
        $this->get("gema.utiles")->traza($accion);
            $em->remove($entity);
            $em->flush();

        return $this->redirect($this->generateUrl('catalogo'));
    }

    /**
     * Creates a form to delete a Catalogo entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('catalogo_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Eliminar'))
            ->getForm()
        ;
    }


    public function pdfgenerateAction($id){

        print($id);die();
    }
}
