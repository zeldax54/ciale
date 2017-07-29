<?php

namespace GEMA\gemaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use GEMA\gemaBundle\Entity\Raza;
use GEMA\gemaBundle\Form\RazaType;
use Symfony\Component\HttpFoundation\JsonResponse;
use GEMA\gemaBundle\Helpers\MyHelper;

/**
 * Raza controller.
 *
 */
class RazaController extends Controller
{

    /**
     * Lists all Raza entities.
     *
     */


    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = array();
        if ($request->isXmlHttpRequest()) {
            $repo = $em->getRepository('gemaBundle:Raza');
            $qb = $repo->filtrar($request);
            $result = $this->get("gema.utiles")->paginar($request->get("current"), $request->get("rowCount"), $qb->getQuery());
            return new JsonResponse($result);
        } else {
            $entities = $em->getRepository('gemaBundle:Raza')->findAll();
        }
        $accion = 'Listar Expedientes de Raza';
        $this->get("gema.utiles")->traza($accion);



        return $this->render('gemaBundle:Raza:index.html.twig', array(
                    'entities' => $entities,
        ));
    }
    /**
     * Creates a new Raza entity.
     *
     */
    public function createAction(Request $request)
    {

        $entity = new Raza();
        $form = $this->createCreateForm($entity);
            $form->handleRequest($request);
        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $accion = '';
            $this->get("gema.utiles")->traza($accion);
            $em->persist($entity);
            $em->flush();
            //
            $helper=new MyHelper();
            $helper->CreateDir( $webPath=$this->get('kernel')->getRootDir().'/../web/','raza',$entity->getGuid());
            return $this->redirect($this->generateUrl('admin_raza'));
        }


        $helper=new MyHelper();
    
       return $this->render('gemaBundle:Raza:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                     'guid'=> $helper->GUID()

        ));
    }

    /**
    * Creates a form to create a Raza entity.
    *
    * @param Raza $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Raza $entity)
    {
        $form = $this->createForm(new RazaType(), $entity, array(
            'action' => $this->generateUrl('admin_raza_create'),
            'method' => 'POST'

        ));

        $form->add('submit', 'submit', array('label' => 'Crear'

        ));

        return $form;
    }



    /**
     * Displays a form to create a new Raza entity.
     *
     */
    public function newAction()
    {
        $entity = new Raza();
        $form   = $this->createCreateForm($entity);
        $helper=new MyHelper();

    
        return $this->render('gemaBundle:Raza:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'guid'=> $helper->GUID()
        ));
    }

    /**
     * Finds and displays a Raza entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:Raza')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Raza entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $accion = ' ';
        $this->get("gema.utiles")->traza($accion);

        return $this->render('gemaBundle:Raza:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Raza entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:Raza')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Raza entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('gemaBundle:Raza:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Raza entity.
    *
    * @param Raza $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Raza $entity)
    {
        $form = $this->createForm(new RazaType(), $entity, array(
            'action' => $this->generateUrl('admin_raza_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar'));

        return $form;
    }
/**
    * Edits an existing Raza entity.
*
    */
    public function updateAction(Request $request, $id)
{
    $em = $this->getDoctrine()->getManager();

    $entity = $em->getRepository('gemaBundle:Raza')->find($id);

    if (!$entity) {
    throw $this->createNotFoundException('Unable to find Raza entity.');
    }

    $deleteForm = $this->createDeleteForm($id);
    $editForm = $this->createEditForm($entity);
    $editForm->handleRequest($request);
    $accion = ' ';
    $this->get("gema.utiles")->traza($accion);
    $em->flush();

    
 

    
    return $this->redirect($this->generateUrl('admin_raza'));
  
}
    /**
     * Deletes a Raza entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('gemaBundle:Raza')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Raza entity.');
            }
            
        $accion = 'Raza Eliminada';
        $guid=$entity->getGuid();
        $webPath = $this->get('kernel')->getRootDir().'/../web/raza/'.$guid;
        $this->get("gema.utiles")->traza($accion);
            $em->remove($entity);
            $em->flush();
        //Eliminando
        $helper=new MyHelper();
        $helper->RemoveFolder($webPath);
        return $this->redirect($this->generateUrl('admin_raza'));
    }



    /**
     * Creates a form to delete a Raza entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_raza_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Eliminar'))
            ->getForm()
        ;
    }
}
