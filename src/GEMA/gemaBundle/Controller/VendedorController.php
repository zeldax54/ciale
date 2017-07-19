<?php

namespace GEMA\gemaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use GEMA\gemaBundle\Entity\Vendedor;
use GEMA\gemaBundle\Form\VendedorType;
use Symfony\Component\HttpFoundation\JsonResponse;
use GEMA\gemaBundle\Helpers\MyHelper;


/**
 * Vendedor controller.
 *
 */
class VendedorController extends Controller
{

    /**
     * Lists all Vendedor entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = array();
        if ($request->isXmlHttpRequest()) {
            $repo = $em->getRepository('gemaBundle:Vendedor');
            $qb = $repo->filtrar($request);
            $result = $this->get("gema.utiles")->paginar($request->get("current"), $request->get("rowCount"), $qb->getQuery());
            return new JsonResponse($result);
        } else {
            $entities = $em->getRepository('gemaBundle:Vendedor')->findAll();
        }
        $accion = 'Listar Expedientes de Vendedor';
        $this->get("gema.utiles")->traza($accion);
        
        

        return $this->render('gemaBundle:Vendedor:index.html.twig', array(
                    'entities' => $entities,
        ));
    }
    /**
     * Creates a new Vendedor entity.
     *
     */
    public function createAction(Request $request)
    {
    
        $entity = new Vendedor();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $accion = '';
            $this->get("gema.utiles")->traza($accion);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_vendedor_show', array('id' => $entity->getId())));
        }



    
       return $this->render('gemaBundle:Vendedor:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a Vendedor entity.
    *
    * @param Vendedor $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Vendedor $entity)
    {
        $form = $this->createForm(new VendedorType(), $entity, array(
            'action' => $this->generateUrl('admin_vendedor_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Crear'));

        return $form;
    }

    /**
     * Displays a form to create a new Vendedor entity.
     *
     */
    public function newAction()
    {
        $entity = new Vendedor();
        $form   = $this->createCreateForm($entity);
        $helper=new MyHelper();

        return $this->render('gemaBundle:Vendedor:new.html.twig', array(
            'entity' => $entity,
            'guid'=> $helper->GUID(),
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Vendedor entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:Vendedor')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Vendedor entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $accion = ' ';
        $this->get("gema.utiles")->traza($accion);

        return $this->render('gemaBundle:Vendedor:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Vendedor entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:Vendedor')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Vendedor entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('gemaBundle:Vendedor:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Vendedor entity.
    *
    * @param Vendedor $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Vendedor $entity)
    {
        $form = $this->createForm(new VendedorType(), $entity, array(
            'action' => $this->generateUrl('admin_vendedor_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar'));

        return $form;
    }
/**
    * Edits an existing Vendedor entity.
*
    */
    public function updateAction(Request $request, $id)
{
    $em = $this->getDoctrine()->getManager();

    $entity = $em->getRepository('gemaBundle:Vendedor')->find($id);

    if (!$entity) {
    throw $this->createNotFoundException('Unable to find Vendedor entity.');
    }

    $deleteForm = $this->createDeleteForm($id);
    $editForm = $this->createEditForm($entity);
    $editForm->handleRequest($request);
    $accion = 'Editar Vendedor ';
    $this->get("gema.utiles")->traza($accion);
    $em->flush();

    
    return $this->redirect($this->generateUrl('gema_mixed', array('idprovincia' => $entity->getProvincia()->getId())));
  
}
    /**
     * Deletes a Vendedor entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('gemaBundle:Vendedor')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Vendedor entity.');
            }
            
        $accion = 'Vendedor Eliminado';
        $guid=$entity->getGuid();
        $webPath = $this->get('kernel')->getRootDir().'/../web/vendedor/'.$guid;
        $this->get("gema.utiles")->traza($accion);
        $this->get("gema.utiles")->traza($accion);
            $em->remove($entity);
            $em->flush();
        $helper=new MyHelper();
        $helper->RemoveFolder($webPath);

        return $this->redirect($this->generateUrl('admin_vendedor'));
    }

    /**
     * Creates a form to delete a Vendedor entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_vendedor_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Eliminar'))
            ->getForm()
        ;
    }
}
