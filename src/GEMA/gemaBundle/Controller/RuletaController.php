<?php

namespace GEMA\gemaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use GEMA\gemaBundle\Entity\Ruleta;
use GEMA\gemaBundle\Form\RuletaType;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Ruleta controller.
 *
 */
class RuletaController extends Controller
{

    /**
     * Lists all Ruleta entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = array();
        if ($request->isXmlHttpRequest()) {
            $repo = $em->getRepository('gemaBundle:Ruleta');
            $qb = $repo->filtrar($request);
            $result = $this->get("gema.utiles")->paginar($request->get("current"), $request->get("rowCount"), $qb->getQuery());
            return new JsonResponse($result);
        } else {
            $entities = $em->getRepository('gemaBundle:Ruleta')->findAll();
        }
        $accion = 'Listar Expedientes de Ruleta';
        $this->get("gema.utiles")->traza($accion);
        
        

        return $this->render('gemaBundle:Ruleta:index.html.twig', array(
                    'entities' => $entities,
        ));
    }
    /**
     * Creates a new Ruleta entity.
     *
     */
    public function createAction(Request $request)
    {
    
        $entity = new Ruleta();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $accion = '';
            $this->get("gema.utiles")->traza($accion);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('ruleta_show', array('id' => $entity->getId())));
        }



    
       return $this->render('gemaBundle:Ruleta:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a Ruleta entity.
    *
    * @param Ruleta $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Ruleta $entity)
    {
        $form = $this->createForm(new RuletaType(), $entity, array(
            'action' => $this->generateUrl('ruleta_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Crear'));

        return $form;
    }

    /**
     * Displays a form to create a new Ruleta entity.
     *
     */
    public function newAction()
    {
        $entity = new Ruleta();
        $form   = $this->createCreateForm($entity);

    
        return $this->render('gemaBundle:Ruleta:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Ruleta entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:Ruleta')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Ruleta entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $accion = ' ';
        $this->get("gema.utiles")->traza($accion);

        return $this->render('gemaBundle:Ruleta:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Ruleta entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:Ruleta')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Ruleta entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('gemaBundle:Ruleta:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Ruleta entity.
    *
    * @param Ruleta $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Ruleta $entity)
    {
        $form = $this->createForm(new RuletaType(), $entity, array(
            'action' => $this->generateUrl('ruleta_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar'));

        return $form;
    }
/**
    * Edits an existing Ruleta entity.
*
    */
    public function updateAction(Request $request, $id)
{
    $em = $this->getDoctrine()->getManager();

    $entity = $em->getRepository('gemaBundle:Ruleta')->find($id);

    if (!$entity) {
    throw $this->createNotFoundException('Unable to find Ruleta entity.');
    }

    $deleteForm = $this->createDeleteForm($id);
    $editForm = $this->createEditForm($entity);
    $editForm->handleRequest($request);
    $accion = ' ';
    $this->get("gema.utiles")->traza($accion);
    $em->flush();

    
 

    
    return $this->redirect($this->generateUrl('ruleta_edit', array('id' => $id)));
  
}
    /**
     * Deletes a Ruleta entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('gemaBundle:Ruleta')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Ruleta entity.');
            }
            
        $accion = ' ';
        $this->get("gema.utiles")->traza($accion);
            $em->remove($entity);
            $em->flush();

        return $this->redirect($this->generateUrl('ruleta'));
    }

    /**
     * Creates a form to delete a Ruleta entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('ruleta_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Eliminar'))
            ->getForm()
        ;
    }
}
