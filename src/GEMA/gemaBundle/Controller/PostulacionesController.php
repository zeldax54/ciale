<?php

namespace GEMA\gemaBundle\Controller;

use GEMA\gemaBundle\Helpers\MyHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use GEMA\gemaBundle\Entity\Postulaciones;
use GEMA\gemaBundle\Form\PostulacionesType;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Postulaciones controller.
 *
 */
class PostulacionesController extends Controller
{

    /**
     * Lists all Postulaciones entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = array();
        if ($request->isXmlHttpRequest()) {
            $repo = $em->getRepository('gemaBundle:Postulaciones');
            $qb = $repo->filtrar($request);
            $result = $this->get("gema.utiles")->paginar($request->get("current"), $request->get("rowCount"), $qb->getQuery());
            return new JsonResponse($result);
        } else {
            $entities = $em->getRepository('gemaBundle:Postulaciones')->findAll();
        }
        $accion = 'Listar Expedientes de Postulaciones';
        $this->get("gema.utiles")->traza($accion);

        $helper=new MyHelper();


//        foreach ($entities as $e){
//            $e->setArchivo( $request->getUriForPath($helper->randomurlFile(DIRECTORY_SEPARATOR.'postulaciones'.DIRECTORY_SEPARATOR.$e->getGuid().DIRECTORY_SEPARATOR)));
//        }
//
//         print_r($entities[0]->getArchivo());die();


        return $this->render('gemaBundle:Postulaciones:index.html.twig', array(
                    'entities' => $entities,
        ));
    }
    /**
     * Creates a new Postulaciones entity.
     *
     */
    public function createAction(Request $request)
    {
    
        $entity = new Postulaciones();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $accion = '';
            $this->get("gema.utiles")->traza($accion);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('postulaciones_show', array('id' => $entity->getId())));
        }



    
       return $this->render('gemaBundle:Postulaciones:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a Postulaciones entity.
    *
    * @param Postulaciones $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Postulaciones $entity)
    {
        $form = $this->createForm(new PostulacionesType(), $entity, array(
            'action' => $this->generateUrl('postulaciones_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Crear'));

        return $form;
    }

    /**
     * Displays a form to create a new Postulaciones entity.
     *
     */
    public function newAction()
    {
        $entity = new Postulaciones();
        $form   = $this->createCreateForm($entity);

    
        return $this->render('gemaBundle:Postulaciones:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Postulaciones entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:Postulaciones')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Postulaciones entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $accion = ' ';
        $this->get("gema.utiles")->traza($accion);

        return $this->render('gemaBundle:Postulaciones:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Postulaciones entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:Postulaciones')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Postulaciones entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('gemaBundle:Postulaciones:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Postulaciones entity.
    *
    * @param Postulaciones $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Postulaciones $entity)
    {
        $form = $this->createForm(new PostulacionesType(), $entity, array(
            'action' => $this->generateUrl('postulaciones_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar'));

        return $form;
    }
/**
    * Edits an existing Postulaciones entity.
*
    */
    public function updateAction(Request $request, $id)
{
    $em = $this->getDoctrine()->getManager();

    $entity = $em->getRepository('gemaBundle:Postulaciones')->find($id);

    if (!$entity) {
    throw $this->createNotFoundException('Unable to find Postulaciones entity.');
    }

    $deleteForm = $this->createDeleteForm($id);
    $editForm = $this->createEditForm($entity);
    $editForm->handleRequest($request);
    $accion = ' ';
    $this->get("gema.utiles")->traza($accion);
    $em->flush();

    
 

    
    return $this->redirect($this->generateUrl('postulaciones_edit', array('id' => $id)));
  
}
    /**
     * Deletes a Postulaciones entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('gemaBundle:Postulaciones')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Postulaciones entity.');
            }
            
        $accion = ' ';
        $this->get("gema.utiles")->traza($accion);
            $em->remove($entity);
            $em->flush();

        return $this->redirect($this->generateUrl('postulaciones'));
    }

    /**
     * Creates a form to delete a Postulaciones entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('postulaciones_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Eliminar'))
            ->getForm()
        ;
    }
}
