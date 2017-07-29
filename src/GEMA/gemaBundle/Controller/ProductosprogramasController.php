<?php

namespace GEMA\gemaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use GEMA\gemaBundle\Entity\Productosprogramas;
use GEMA\gemaBundle\Form\ProductosprogramasType;
use Symfony\Component\HttpFoundation\JsonResponse;
use GEMA\gemaBundle\Helpers\MyHelper;

/**
 * Productosprogramas controller.
 *
 */
class ProductosprogramasController extends Controller
{

    /**
     * Lists all Productosprogramas entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = array();
        if ($request->isXmlHttpRequest()) {
            $repo = $em->getRepository('gemaBundle:Productosprogramas');
            $qb = $repo->filtrar($request);
            $result = $this->get("gema.utiles")->paginar($request->get("current"), $request->get("rowCount"), $qb->getQuery());
            return new JsonResponse($result);
        } else {
            $entities = $em->getRepository('gemaBundle:Productosprogramas')->findAll();
        }
        $accion = 'Listar Expedientes de Productosprogramas';
        $this->get("gema.utiles")->traza($accion);
        
        

        return $this->render('gemaBundle:Productosprogramas:index.html.twig', array(
                    'entities' => $entities,
        ));
    }
    /**
     * Creates a new Productosprogramas entity.
     *
     */
    public function createAction(Request $request)
    {
    
        $entity = new Productosprogramas();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $accion = '';
            $this->get("gema.utiles")->traza($accion);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_productosprogramas'));
        }



       $helper=new MyHelper();
       return $this->render('gemaBundle:Productosprogramas:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                     'guid'=>$helper->GUID()
        ));
    }

    /**
    * Creates a form to create a Productosprogramas entity.
    *
    * @param Productosprogramas $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Productosprogramas $entity)
    {
        $form = $this->createForm(new ProductosprogramasType(), $entity, array(
            'action' => $this->generateUrl('admin_productosprogramas_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Crear'));

        return $form;
    }

    /**
     * Displays a form to create a new Productosprogramas entity.
     *
     */
    public function newAction()
    {
        $entity = new Productosprogramas();
        $form   = $this->createCreateForm($entity);

        $helper=new MyHelper();
        return $this->render('gemaBundle:Productosprogramas:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'guid'=> $helper->GUID(),
        ));
    }

    /**
     * Finds and displays a Productosprogramas entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:Productosprogramas')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Productosprogramas entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $accion = ' ';
        $this->get("gema.utiles")->traza($accion);

        return $this->render('gemaBundle:Productosprogramas:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Productosprogramas entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:Productosprogramas')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Productosprogramas entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('gemaBundle:Productosprogramas:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Productosprogramas entity.
    *
    * @param Productosprogramas $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Productosprogramas $entity)
    {
        $form = $this->createForm(new ProductosprogramasType(), $entity, array(
            'action' => $this->generateUrl('admin_productosprogramas_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar'));

        return $form;
    }
/**
    * Edits an existing Productosprogramas entity.
*
    */
    public function updateAction(Request $request, $id)
{
    $em = $this->getDoctrine()->getManager();

    $entity = $em->getRepository('gemaBundle:Productosprogramas')->find($id);

    if (!$entity) {
    throw $this->createNotFoundException('Unable to find Productosprogramas entity.');
    }

    $deleteForm = $this->createDeleteForm($id);
    $editForm = $this->createEditForm($entity);
    $editForm->handleRequest($request);
    $accion = ' ';
    $this->get("gema.utiles")->traza($accion);
    $em->flush();

    
 

    
    return $this->redirect($this->generateUrl('admin_productosprogramas_edit', array('id' => $id)));
  
}
    /**
     * Deletes a Productosprogramas entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('gemaBundle:Productosprogramas')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Productosprogramas entity.');
            }
            
        $accion = 'Producto o Programa eliminado';
        $guid=$entity->getGuid();
        $webPath = $this->get('kernel')->getRootDir().'/../web/productoprograma/'.$guid;
        $webPathl = $this->get('kernel')->getRootDir().'/../web/productoprograma/'.$guid.DIRECTORY_SEPARATOR.'l';
        $this->get("gema.utiles")->traza($accion);
            $em->remove($entity);
            $em->flush();
        $helper=new MyHelper();
        $helper->RemoveFolder($webPath);
        $helper->RemoveFolder($webPathl);

        return $this->redirect($this->generateUrl('admin_productosprogramas'));
    }

    /**
     * Creates a form to delete a Productosprogramas entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_productosprogramas_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Eliminar'))
            ->getForm()
        ;
    }
}
