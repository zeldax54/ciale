<?php

namespace GEMA\gemaBundle\Controller;

use GEMA\gemaBundle\Helpers\MyHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use GEMA\gemaBundle\Entity\Colaborador;
use GEMA\gemaBundle\Form\ColaboradorType;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Colaborador controller.
 *
 */
class ColaboradorController extends Controller
{

    /**
     * Lists all Colaborador entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = array();
        if ($request->isXmlHttpRequest()) {
            $repo = $em->getRepository('gemaBundle:Colaborador');
            $qb = $repo->filtrar($request);
            $result = $this->get("gema.utiles")->paginar($request->get("current"), $request->get("rowCount"), $qb->getQuery());
            return new JsonResponse($result);
        } else {
            $entities = $em->getRepository('gemaBundle:Colaborador')->findAll();
        }
        $accion = 'Listar Expedientes de Colaborador';
        $this->get("gema.utiles")->traza($accion);
        
        

        return $this->render('gemaBundle:Colaborador:index.html.twig', array(
                    'entities' => $entities,
        ));
    }
    /**
     * Creates a new Colaborador entity.
     *
     */
    public function createAction(Request $request)
    {
    
        $entity = new Colaborador();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $accion = '';
            $this->get("gema.utiles")->traza($accion);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('colaborador_show', array('id' => $entity->getId())));
        }



    
       return $this->render('gemaBundle:Colaborador:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a Colaborador entity.
    *
    * @param Colaborador $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Colaborador $entity)
    {
        $form = $this->createForm(new ColaboradorType(), $entity, array(
            'action' => $this->generateUrl('colaborador_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Crear'));

        return $form;
    }

    /**
     * Displays a form to create a new Colaborador entity.
     *
     */
    public function newAction()
    {
        $entity = new Colaborador();
        $form   = $this->createCreateForm($entity);
        $helper=new MyHelper();
    
        return $this->render('gemaBundle:Colaborador:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'guid'=> $helper->GUID(),
        ));
    }

    /**
     * Finds and displays a Colaborador entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:Colaborador')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Colaborador entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $accion = ' ';
        $this->get("gema.utiles")->traza($accion);

        return $this->render('gemaBundle:Colaborador:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Colaborador entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:Colaborador')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Colaborador entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('gemaBundle:Colaborador:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Colaborador entity.
    *
    * @param Colaborador $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Colaborador $entity)
    {
        $form = $this->createForm(new ColaboradorType(), $entity, array(
            'action' => $this->generateUrl('colaborador_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar'));

        return $form;
    }
/**
    * Edits an existing Colaborador entity.
*
    */
    public function updateAction(Request $request, $id)
{
    $em = $this->getDoctrine()->getManager();

    $entity = $em->getRepository('gemaBundle:Colaborador')->find($id);

    if (!$entity) {
    throw $this->createNotFoundException('Unable to find Colaborador entity.');
    }

    $deleteForm = $this->createDeleteForm($id);
    $editForm = $this->createEditForm($entity);
    $editForm->handleRequest($request);
    $accion = ' ';
    $this->get("gema.utiles")->traza($accion);
    $em->flush();

    
 

    
    return $this->redirect($this->generateUrl('colaborador_edit', array('id' => $id)));
  
}
    /**
     * Deletes a Colaborador entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('gemaBundle:Colaborador')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Colaborador entity.');
            }
            
        $accion = 'Elimianr colaborador';
        $guid=$entity->getGuid();
        $webPath2 = $this->get('kernel')->getRootDir().'/../web/toro/'.$guid.'P';
        $helper=new MyHelper();
        $helper->RemoveFolder($webPath2);
        $this->get("gema.utiles")->traza($accion);
            $em->remove($entity);
            $em->flush();

        return $this->redirect($this->generateUrl('colaborador'));
    }

    /**
     * Creates a form to delete a Colaborador entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('colaborador_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Eliminar'))
            ->getForm()
        ;
    }
}
