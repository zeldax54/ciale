<?php

namespace GEMA\gemaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use GEMA\gemaBundle\Entity\Calostrovideos;
use GEMA\gemaBundle\Form\CalostrovideosType;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Calostrovideos controller.
 *
 */
class CalostrovideosController extends Controller
{

    /**
     * Lists all Calostrovideos entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = array();
        if ($request->isXmlHttpRequest()) {
            $repo = $em->getRepository('gemaBundle:Calostrovideos');
            $qb = $repo->filtrar($request);
            $result = $this->get("gema.utiles")->paginar($request->get("current"), $request->get("rowCount"), $qb->getQuery());
            return new JsonResponse($result);
        } else {
            $entities = $em->getRepository('gemaBundle:Calostrovideos')->findAll();
        }
        $accion = 'Listar Expedientes de Calostrovideos';
        $this->get("gema.utiles")->traza($accion);
        
        

        return $this->render('gemaBundle:Calostrovideos:index.html.twig', array(
                    'entities' => $entities,
        ));
    }
    /**
     * Creates a new Calostrovideos entity.
     *
     */
    public function createAction(Request $request)
    {
    
        $entity = new Calostrovideos();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $accion = '';
            $this->get("gema.utiles")->traza($accion);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('calostrovideos_show', array('id' => $entity->getId())));
        }



    
       return $this->render('gemaBundle:Calostrovideos:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a Calostrovideos entity.
    *
    * @param Calostrovideos $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Calostrovideos $entity)
    {
        $form = $this->createForm(new CalostrovideosType(), $entity, array(
            'action' => $this->generateUrl('calostrovideos_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Crear'));

        return $form;
    }

    /**
     * Displays a form to create a new Calostrovideos entity.
     *
     */
    public function newAction()
    {
        $entity = new Calostrovideos();
        $form   = $this->createCreateForm($entity);

    
        return $this->render('gemaBundle:Calostrovideos:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Calostrovideos entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:Calostrovideos')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Calostrovideos entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $accion = ' ';
        $this->get("gema.utiles")->traza($accion);

        return $this->render('gemaBundle:Calostrovideos:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Calostrovideos entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:Calostrovideos')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Calostrovideos entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('gemaBundle:Calostrovideos:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Calostrovideos entity.
    *
    * @param Calostrovideos $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Calostrovideos $entity)
    {
        $form = $this->createForm(new CalostrovideosType(), $entity, array(
            'action' => $this->generateUrl('calostrovideos_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar'));

        return $form;
    }
/**
    * Edits an existing Calostrovideos entity.
*
    */
    public function updateAction(Request $request, $id)
{
    $em = $this->getDoctrine()->getManager();

    $entity = $em->getRepository('gemaBundle:Calostrovideos')->find($id);

    if (!$entity) {
    throw $this->createNotFoundException('Unable to find Calostrovideos entity.');
    }

    $deleteForm = $this->createDeleteForm($id);
    $editForm = $this->createEditForm($entity);
    $editForm->handleRequest($request);
    $accion = ' ';
    $this->get("gema.utiles")->traza($accion);
    $em->flush();

    
 

    
    return $this->redirect($this->generateUrl('calostrovideos_edit', array('id' => $id)));
  
}
    /**
     * Deletes a Calostrovideos entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('gemaBundle:Calostrovideos')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Calostrovideos entity.');
            }
            
        $accion = ' ';
        $this->get("gema.utiles")->traza($accion);
            $em->remove($entity);
            $em->flush();

        return $this->redirect($this->generateUrl('calostrovideos'));
    }

    /**
     * Creates a form to delete a Calostrovideos entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('calostrovideos_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Eliminar'))
            ->getForm()
        ;
    }
}
