<?php

namespace GEMA\gemaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use GEMA\gemaBundle\Entity\Razafather;
use GEMA\gemaBundle\Form\RazafatherType;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Razafather controller.
 *
 */
class RazafatherController extends Controller
{

    /**
     * Lists all Razafather entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = array();
        if ($request->isXmlHttpRequest()) {
            $repo = $em->getRepository('gemaBundle:Razafather');
            $qb = $repo->filtrar($request);
            $result = $this->get("gema.utiles")->paginar($request->get("current"), $request->get("rowCount"), $qb->getQuery());
            return new JsonResponse($result);
        } else {
            $entities = $em->getRepository('gemaBundle:Razafather')->findAll();
        }
        $accion = 'Listar Expedientes de Razafather';
        $this->get("gema.utiles")->traza($accion);
        
        

        return $this->render('gemaBundle:Razafather:index.html.twig', array(
                    'entities' => $entities,
        ));
    }
    /**
     * Creates a new Razafather entity.
     *
     */
    public function createAction(Request $request)
    {
    
        $entity = new Razafather();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $accion = '';
            $this->get("gema.utiles")->traza($accion);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_razafather_show', array('id' => $entity->getId())));
        }



    
       return $this->render('gemaBundle:Razafather:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a Razafather entity.
    *
    * @param Razafather $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Razafather $entity)
    {
        $form = $this->createForm(new RazafatherType(), $entity, array(
            'action' => $this->generateUrl('admin_razafather_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Crear'));

        return $form;
    }

    /**
     * Displays a form to create a new Razafather entity.
     *
     */
    public function newAction()
    {
        $entity = new Razafather();
        $form   = $this->createCreateForm($entity);

    
        return $this->render('gemaBundle:Razafather:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Razafather entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:Razafather')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Razafather entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $accion = ' ';
        $this->get("gema.utiles")->traza($accion);

        return $this->render('gemaBundle:Razafather:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Razafather entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:Razafather')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Razafather entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('gemaBundle:Razafather:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Razafather entity.
    *
    * @param Razafather $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Razafather $entity)
    {
        $form = $this->createForm(new RazafatherType(), $entity, array(
            'action' => $this->generateUrl('admin_razafather_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar'));

        return $form;
    }
/**
    * Edits an existing Razafather entity.
*
    */
    public function updateAction(Request $request, $id)
{
    $em = $this->getDoctrine()->getManager();

    $entity = $em->getRepository('gemaBundle:Razafather')->find($id);

    if (!$entity) {
    throw $this->createNotFoundException('Unable to find Razafather entity.');
    }

    $deleteForm = $this->createDeleteForm($id);
    $editForm = $this->createEditForm($entity);
    $editForm->handleRequest($request);
    $accion = ' ';
    $this->get("gema.utiles")->traza($accion);
    $em->flush();

    
 

    
    return $this->redirect($this->generateUrl('admin_razafather_edit', array('id' => $id)));
  
}
    /**
     * Deletes a Razafather entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('gemaBundle:Razafather')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Razafather entity.');
            }
        if(count($entity->getRazas())>0)
        {
            $request->getSession()->getFlashBag()->add(
                'error', 'No se puede borrar porque esta raza tiene razas hijas'
            );
            return $this->redirect($this->generateUrl('admin_razafather'));
        }

            
        $accion = 'Padre d eraza eliminado';
        $this->get("gema.utiles")->traza($accion);
            $em->remove($entity);
            $em->flush();

        return $this->redirect($this->generateUrl('admin_razafather'));
    }

    /**
     * Creates a form to delete a Razafather entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_razafather_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Eliminar'))
            ->getForm()
        ;
    }
}
