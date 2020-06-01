<?php

namespace GEMA\gemaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use GEMA\gemaBundle\Entity\VendedorCompra;
use GEMA\gemaBundle\Form\VendedorCompraType;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * VendedorCompra controller.
 *
 */
class VendedorCompraController extends Controller
{

    /**
     * Lists all VendedorCompra entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = array();
        if ($request->isXmlHttpRequest()) {
            $repo = $em->getRepository('gemaBundle:VendedorCompra');
            $qb = $repo->filtrar($request);
            $result = $this->get("gema.utiles")->paginar($request->get("current"), $request->get("rowCount"), $qb->getQuery());
            return new JsonResponse($result);
        } else {
            $entities = $em->getRepository('gemaBundle:VendedorCompra')->findAll();
        }
        $accion = 'Listar Expedientes de VendedorCompra';
        $this->get("gema.utiles")->traza($accion);
        
        

        return $this->render('gemaBundle:VendedorCompra:index.html.twig', array(
                    'entities' => $entities,
        ));
    }
    /**
     * Creates a new VendedorCompra entity.
     *
     */
    public function createAction(Request $request)
    {
    
        $entity = new VendedorCompra();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $accion = '';
            $this->get("gema.utiles")->traza($accion);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('vendedorcompra_show', array('id' => $entity->getId())));
        }



    
       return $this->render('gemaBundle:VendedorCompra:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a VendedorCompra entity.
    *
    * @param VendedorCompra $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(VendedorCompra $entity)
    {
        $form = $this->createForm(new VendedorCompraType(), $entity, array(
            'action' => $this->generateUrl('vendedorcompra_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Crear'));

        return $form;
    }

    /**
     * Displays a form to create a new VendedorCompra entity.
     *
     */
    public function newAction()
    {
        $entity = new VendedorCompra();
        $form   = $this->createCreateForm($entity);

    
        return $this->render('gemaBundle:VendedorCompra:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a VendedorCompra entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:VendedorCompra')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find VendedorCompra entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $accion = ' ';
        $this->get("gema.utiles")->traza($accion);

        return $this->render('gemaBundle:VendedorCompra:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing VendedorCompra entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:VendedorCompra')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find VendedorCompra entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('gemaBundle:VendedorCompra:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a VendedorCompra entity.
    *
    * @param VendedorCompra $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(VendedorCompra $entity)
    {
        $form = $this->createForm(new VendedorCompraType(), $entity, array(
            'action' => $this->generateUrl('vendedorcompra_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar'));

        return $form;
    }
/**
    * Edits an existing VendedorCompra entity.
*
    */
    public function updateAction(Request $request, $id)
{
    $em = $this->getDoctrine()->getManager();

    $entity = $em->getRepository('gemaBundle:VendedorCompra')->find($id);

    if (!$entity) {
    throw $this->createNotFoundException('Unable to find VendedorCompra entity.');
    }

    $deleteForm = $this->createDeleteForm($id);
    $editForm = $this->createEditForm($entity);
    $editForm->handleRequest($request);
    $accion = ' ';
    $this->get("gema.utiles")->traza($accion);
    $em->flush();

    
 

    
    return $this->redirect($this->generateUrl('vendedorcompra_edit', array('id' => $id)));
  
}
    /**
     * Deletes a VendedorCompra entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('gemaBundle:VendedorCompra')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find VendedorCompra entity.');
            }
            
        $accion = ' ';
        $this->get("gema.utiles")->traza($accion);
            $em->remove($entity);
            $em->flush();

        return $this->redirect($this->generateUrl('vendedorcompra'));
    }

    /**
     * Creates a form to delete a VendedorCompra entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('vendedorcompra_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Eliminar'))
            ->getForm()
        ;
    }
}