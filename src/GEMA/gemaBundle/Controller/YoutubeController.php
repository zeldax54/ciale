<?php

namespace GEMA\gemaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use GEMA\gemaBundle\Entity\Youtube;
use GEMA\gemaBundle\Form\YoutubeType;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Youtube controller.
 *
 */
class YoutubeController extends Controller
{

    /**
     * Lists all Youtube entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = array();
        if ($request->isXmlHttpRequest()) {
            $repo = $em->getRepository('gemaBundle:Youtube');
            $qb = $repo->filtrar($request);
            $result = $this->get("gema.utiles")->paginar($request->get("current"), $request->get("rowCount"), $qb->getQuery());
            return new JsonResponse($result);
        } else {
            $entities = $em->getRepository('gemaBundle:Youtube')->findAll();
        }
        $accion = 'Listar Expedientes de Youtube';
        $this->get("gema.utiles")->traza($accion);
        
        

        return $this->render('gemaBundle:Youtube:index.html.twig', array(
                    'entities' => $entities,
        ));
    }
    /**
     * Creates a new Youtube entity.
     *
     */
    public function createAction(Request $request)
    {
    
        $entity = new Youtube();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $accion = '';
            $this->get("gema.utiles")->traza($accion);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_youtube_show', array('id' => $entity->getId())));
        }



    
       return $this->render('gemaBundle:Youtube:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a Youtube entity.
    *
    * @param Youtube $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Youtube $entity)
    {
        $form = $this->createForm(new YoutubeType(), $entity, array(
            'action' => $this->generateUrl('admin_youtube_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Crear'));

        return $form;
    }

    /**
     * Displays a form to create a new Youtube entity.
     *
     */
    public function newAction()
    {
        $entity = new Youtube();
        $form   = $this->createCreateForm($entity);

    
        return $this->render('gemaBundle:Youtube:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Youtube entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:Youtube')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Youtube entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $accion = ' ';
        $this->get("gema.utiles")->traza($accion);

        return $this->render('gemaBundle:Youtube:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Youtube entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:Youtube')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Youtube entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('gemaBundle:Youtube:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Youtube entity.
    *
    * @param Youtube $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Youtube $entity)
    {
        $form = $this->createForm(new YoutubeType(), $entity, array(
            'action' => $this->generateUrl('admin_youtube_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar'));

        return $form;
    }
/**
    * Edits an existing Youtube entity.
*
    */
    public function updateAction(Request $request, $id)
{
    $em = $this->getDoctrine()->getManager();

    $entity = $em->getRepository('gemaBundle:Youtube')->find($id);

    if (!$entity) {
    throw $this->createNotFoundException('Unable to find Youtube entity.');
    }

    $deleteForm = $this->createDeleteForm($id);
    $editForm = $this->createEditForm($entity);
    $editForm->handleRequest($request);
    $accion = ' ';
    $this->get("gema.utiles")->traza($accion);
    $em->flush();

    
 

    
    return $this->redirect($this->generateUrl('admin_youtube_edit', array('id' => $id)));
  
}
    /**
     * Deletes a Youtube entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('gemaBundle:Youtube')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Youtube entity.');
            }
            
        $accion = ' ';
        $this->get("gema.utiles")->traza($accion);
            $em->remove($entity);
            $em->flush();

        return $this->redirect($this->generateUrl('admin_youtube'));
    }

    /**
     * Creates a form to delete a Youtube entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_youtube_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Eliminar'))
            ->getForm()
        ;
    }
}
