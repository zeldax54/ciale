<?php

namespace GEMA\gemaBundle\Controller;

use GEMA\gemaBundle\Helpers\MyHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use GEMA\gemaBundle\Entity\Remate;
use GEMA\gemaBundle\Form\RemateType;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Remate controller.
 *
 */
class RemateController extends Controller
{

    /**
     * Lists all Remate entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = array();
        if ($request->isXmlHttpRequest()) {
            $repo = $em->getRepository('gemaBundle:Remate');
            $qb = $repo->filtrar($request);
            $result = $this->get("gema.utiles")->paginar($request->get("current"), $request->get("rowCount"), $qb->getQuery());
            return new JsonResponse($result);
        } else {
            $entities = $em->getRepository('gemaBundle:Remate')->findAll();
        }
        $accion = 'Listar Expedientes de Remate';
        $this->get("gema.utiles")->traza($accion);
        
        

        return $this->render('gemaBundle:Remate:index.html.twig', array(
                    'entities' => $entities,
        ));
    }
    /**
     * Creates a new Remate entity.
     *
     */
    public function createAction(Request $request)
    {
    
        $entity = new Remate();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $accion = '';
            $this->get("gema.utiles")->traza($accion);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('remate_show', array('id' => $entity->getId())));
        }


          $helper=new MyHelper();
    
       return $this->render('gemaBundle:Remate:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                 'guid'=> $helper->GUID(),
        ));
    }

    /**
    * Creates a form to create a Remate entity.
    *
    * @param Remate $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Remate $entity)
    {
        $form = $this->createForm(new RemateType(), $entity, array(
            'action' => $this->generateUrl('remate_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Crear'));

        return $form;
    }

    /**
     * Displays a form to create a new Remate entity.
     *
     */
    public function newAction()
    {
        $entity = new Remate();
        $form   = $this->createCreateForm($entity);
        $helper=new MyHelper();

    
        return $this->render('gemaBundle:Remate:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'guid'=> $helper->Guid()

        ));
    }

    /**
     * Finds and displays a Remate entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:Remate')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Remate entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $accion = ' ';
        $this->get("gema.utiles")->traza($accion);

        return $this->render('gemaBundle:Remate:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Remate entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:Remate')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Remate entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('gemaBundle:Remate:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Remate entity.
    *
    * @param Remate $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Remate $entity)
    {
        $form = $this->createForm(new RemateType(), $entity, array(
            'action' => $this->generateUrl('remate_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar'));

        return $form;
    }
/**
    * Edits an existing Remate entity.
*
    */
    public function updateAction(Request $request, $id)
{
    $em = $this->getDoctrine()->getManager();

    $entity = $em->getRepository('gemaBundle:Remate')->find($id);

    if (!$entity) {
    throw $this->createNotFoundException('Unable to find Remate entity.');
    }

    $deleteForm = $this->createDeleteForm($id);
    $editForm = $this->createEditForm($entity);
    $editForm->handleRequest($request);
    $accion = ' ';
    $this->get("gema.utiles")->traza($accion);
    $em->flush();

    
 

    
    return $this->redirect($this->generateUrl('remate_edit', array('id' => $id)));
  
}
    /**
     * Deletes a Remate entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('gemaBundle:Remate')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Remate entity.');
            }
            
        $accion = ' ';
        $this->get("gema.utiles")->traza($accion);
            $em->remove($entity);
            $em->flush();

        return $this->redirect($this->generateUrl('remate'));
    }

    /**
     * Creates a form to delete a Remate entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('remate_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Eliminar'))
            ->getForm()
        ;
    }




    public function ClientIndexAction(){
        $em = $this->getDoctrine()->getManager();
        $remaets = $em->getRepository('gemaBundle:Remate')->findAll();
        $helper=new MyHelper();
        foreach ($remaets as $remate){
            $img=$helper->randomPic('remates'.DIRECTORY_SEPARATOR.$remate->getGuid().DIRECTORY_SEPARATOR,false);
            if($img==null)
                $img=$helper->directPic('genericfiles'.DIRECTORY_SEPARATOR,'toro.png',false);

            $remate->imgprincipal=$img;

        }

        return $this->render('gemaBundle:Page:remate.html.twig', array(
            'remates'=>$remaets

        ));
    }
}
