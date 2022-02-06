<?php

namespace GEMA\gemaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use GEMA\gemaBundle\Entity\Comerciables;
use GEMA\gemaBundle\Form\ComerciablesType;
use Symfony\Component\HttpFoundation\JsonResponse;
use GEMA\gemaBundle\Helpers\MyHelper;

/**
 * Comerciables controller.
 *
 */
class ComerciablesController extends Controller
{

    /**
     * Lists all Comerciables entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = array();
        if ($request->isXmlHttpRequest()) {
            $repo = $em->getRepository('gemaBundle:Comerciables');
            $qb = $repo->filtrar($request);
            $result = $this->get("gema.utiles")->paginar($request->get("current"), $request->get("rowCount"), $qb->getQuery());
            return new JsonResponse($result);
        } else {
            $entities = $em->getRepository('gemaBundle:Comerciables')->findAll();
        }
        $accion = 'Listar Expedientes de Comerciables';
        $this->get("gema.utiles")->traza($accion);
        
        

        return $this->render('gemaBundle:Comerciables:index.html.twig', array(
                    'entities' => $entities,
        ));
    }
    /**
     * Creates a new Comerciables entity.
     *
     */
    public function createAction(Request $request)
    {
    
        $entity = new Comerciables();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $accion = '';
            $this->get("gema.utiles")->traza($accion);
            $em->persist($entity);
            $em->flush();
            $helper=new MyHelper();
            $helper->CreateDir( $webPath=$this->get('kernel')->getRootDir().'/../web/','comerciables',$entity->getGuid());
            return $this->redirect($this->generateUrl('admin_comerciables'));
        }

        $helper=new MyHelper();

    
       return $this->render('gemaBundle:Comerciables:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                    'guid'=> $helper->GUID(),
        ));
    }

    /**
    * Creates a form to create a Comerciables entity.
    *
    * @param Comerciables $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Comerciables $entity)
    {
        $form = $this->createForm(new ComerciablesType(), $entity, array(
            'action' => $this->generateUrl('admin_comerciables_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Crear'));

        return $form;
    }

    /**
     * Displays a form to create a new Comerciables entity.
     *
     */
    public function newAction()
    {
        $entity = new Comerciables();
        $form   = $this->createCreateForm($entity);
        $helper=new MyHelper();
    
        return $this->render('gemaBundle:Comerciables:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'guid'=> $helper->GUID(),
        ));
    }

    /**
     * Finds and displays a Comerciables entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:Comerciables')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Comerciables entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $accion = ' ';
        $this->get("gema.utiles")->traza($accion);

        return $this->render('gemaBundle:Comerciables:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Comerciables entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();       
        $entity = $em->getRepository('gemaBundle:Comerciables')->find($id);
     
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Comerciables entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('gemaBundle:Comerciables:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'guid'=> $entity->getGUID(),
           
        ));
    }

    /**
    * Creates a form to edit a Comerciables entity.
    *
    * @param Comerciables $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Comerciables $entity)
    {
        $form = $this->createForm(new ComerciablesType(), $entity, array(
            'action' => $this->generateUrl('admin_comerciables_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar'));

        return $form;
    }
/**
    * Edits an existing Comerciables entity.
*
    */
    public function updateAction(Request $request, $id)
{
    $em = $this->getDoctrine()->getManager();

    $entity = $em->getRepository('gemaBundle:Comerciables')->find($id);

    if (!$entity) {
    throw $this->createNotFoundException('Unable to find Comerciables entity.');
    }

    $deleteForm = $this->createDeleteForm($id);
    $editForm = $this->createEditForm($entity);
    $editForm->handleRequest($request);
    $accion = ' ';
    $this->get("gema.utiles")->traza($accion);
    $em->flush();

    
 

    
    return $this->redirect($this->generateUrl('admin_comerciables_edit', array('id' => $id)));
  
}
    /**
     * Deletes a Comerciables entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('gemaBundle:Comerciables')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Comerciables entity.');
            }
            
        $accion = 'Coemrciable Eliminado';
        $guid=$entity->getGuid();
        $webPath = $this->get('kernel')->getRootDir().'/../web/comerciables/'.$guid;
        $webPath2 = $this->get('kernel')->getRootDir().'/../web/comerciables/'.$guid.'P';
         //Eliminando
         $helper=new MyHelper();
         $helper->RemoveFolder($webPath);
         $helper->RemoveFolder($webPath2);

        $this->get("gema.utiles")->traza($accion);
            $em->remove($entity);
            $em->flush();

        return $this->redirect($this->generateUrl('admin_comerciables'));
    }

    /**
     * Creates a form to delete a Comerciables entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_comerciables_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Eliminar'))
            ->getForm()
        ;
    }
}
