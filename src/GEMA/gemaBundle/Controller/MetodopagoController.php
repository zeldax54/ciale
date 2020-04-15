<?php

namespace GEMA\gemaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use GEMA\gemaBundle\Entity\Metodopago;
use GEMA\gemaBundle\Form\MetodopagoType;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Metodopago controller.
 *
 */
class MetodopagoController extends Controller
{

    /**
     * Lists all Metodopago entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = array();
        if ($request->isXmlHttpRequest()) {
            $repo = $em->getRepository('gemaBundle:Metodopago');
            $qb = $repo->filtrar($request);
            $result = $this->get("gema.utiles")->paginar($request->get("current"), $request->get("rowCount"), $qb->getQuery());
            return new JsonResponse($result);
        } else {
            $entities = $em->getRepository('gemaBundle:Metodopago')->findAll();
        }
        $accion = 'Listar Expedientes de Metodopago';
        $this->get("gema.utiles")->traza($accion);
        
        

        return $this->render('gemaBundle:Metodopago:index.html.twig', array(
                    'entities' => $entities,
        ));
    }
    /**
     * Creates a new Metodopago entity.
     *
     */
    public function createAction(Request $request)
    {
    
        $entity = new Metodopago();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $accion = '';
            $this->get("gema.utiles")->traza($accion);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('metodopago_show', array('id' => $entity->getId())));
        }



    
       return $this->render('gemaBundle:Metodopago:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a Metodopago entity.
    *
    * @param Metodopago $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Metodopago $entity)
    {
        $form = $this->createForm(new MetodopagoType(), $entity, array(
            'action' => $this->generateUrl('metodopago_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Crear'));

        return $form;
    }

    /**
     * Displays a form to create a new Metodopago entity.
     *
     */
    public function newAction()
    {
        $entity = new Metodopago();
        $form   = $this->createCreateForm($entity);

    
        return $this->render('gemaBundle:Metodopago:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Metodopago entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:Metodopago')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Metodopago entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $accion = ' ';
        $this->get("gema.utiles")->traza($accion);

        return $this->render('gemaBundle:Metodopago:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Metodopago entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:Metodopago')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Metodopago entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('gemaBundle:Metodopago:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Metodopago entity.
    *
    * @param Metodopago $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Metodopago $entity)
    {
        $form = $this->createForm(new MetodopagoType(), $entity, array(
            'action' => $this->generateUrl('metodopago_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar'));

        return $form;
    }
/**
    * Edits an existing Metodopago entity.
*
    */
    public function updateAction(Request $request, $id)
{
    $em = $this->getDoctrine()->getManager();

    $entity = $em->getRepository('gemaBundle:Metodopago')->find($id);

    if (!$entity) {
    throw $this->createNotFoundException('Unable to find Metodopago entity.');
    }

    $deleteForm = $this->createDeleteForm($id);
    $editForm = $this->createEditForm($entity);
    $editForm->handleRequest($request);
    $accion = ' ';
    $this->get("gema.utiles")->traza($accion);
    $em->flush();

    
 

    
    return $this->redirect($this->generateUrl('metodopago_edit', array('id' => $id)));
  
}
    /**
     * Deletes a Metodopago entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('gemaBundle:Metodopago')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Metodopago entity.');
            }
            
        $accion = ' ';
        $this->get("gema.utiles")->traza($accion);
            $em->remove($entity);
            $em->flush();

        return $this->redirect($this->generateUrl('metodopago'));
    }

    /**
     * Creates a form to delete a Metodopago entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('metodopago_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Eliminar'))
            ->getForm()
        ;
    }
}
