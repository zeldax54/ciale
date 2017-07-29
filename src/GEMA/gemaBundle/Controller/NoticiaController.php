<?php

namespace GEMA\gemaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use GEMA\gemaBundle\Entity\Noticia;
use GEMA\gemaBundle\Form\NoticiaType;
use Symfony\Component\HttpFoundation\JsonResponse;
use GEMA\gemaBundle\Helpers\MyHelper;

/**
 * Noticia controller.
 *
 */
class NoticiaController extends Controller
{

    /**
     * Lists all Noticia entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = array();
        if ($request->isXmlHttpRequest()) {
            $repo = $em->getRepository('gemaBundle:Noticia');
            $qb = $repo->filtrar($request);
            $result = $this->get("gema.utiles")->paginar($request->get("current"), $request->get("rowCount"), $qb->getQuery());
            return new JsonResponse($result);
        } else {
            $entities = $em->getRepository('gemaBundle:Noticia')->findAll();
        }
        $accion = 'Listar Expedientes de Noticia';
        $this->get("gema.utiles")->traza($accion);
        
        

        return $this->render('gemaBundle:Noticia:index.html.twig', array(
                    'entities' => $entities,
        ));
    }
    /**
     * Creates a new Noticia entity.
     *
     */
    public function createAction(Request $request)
    {
    
        $entity = new Noticia();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        $helper=new MyHelper();
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $accion = '';
            $this->get("gema.utiles")->traza($accion);
            $em->persist($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('admin_noticias'));
        }



    
       return $this->render('gemaBundle:Noticia:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                    'guid'=>$helper->GUID()
        ));
    }

    /**
    * Creates a form to create a Noticia entity.
    *
    * @param Noticia $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Noticia $entity)
    {
        $form = $this->createForm(new NoticiaType(), $entity, array(
            'action' => $this->generateUrl('admin_noticias_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Crear'));

        return $form;
    }

    /**
     * Displays a form to create a new Noticia entity.
     *
     */
    public function newAction()
    {
        $entity = new Noticia();
        $form   = $this->createCreateForm($entity);
        $helper=new MyHelper();
    
        return $this->render('gemaBundle:Noticia:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'guid'=> $helper->GUID(),
        ));
    }

    /**
     * Finds and displays a Noticia entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:Noticia')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Noticia entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $accion = ' ';
        $this->get("gema.utiles")->traza($accion);

        return $this->render('gemaBundle:Noticia:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Noticia entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:Noticia')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Noticia entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('gemaBundle:Noticia:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Noticia entity.
    *
    * @param Noticia $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Noticia $entity)
    {
        $form = $this->createForm(new NoticiaType(), $entity, array(
            'action' => $this->generateUrl('admin_noticias_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar'));

        return $form;
    }
/**
    * Edits an existing Noticia entity.
*
    */
    public function updateAction(Request $request, $id)
{
    $em = $this->getDoctrine()->getManager();

    $entity = $em->getRepository('gemaBundle:Noticia')->find($id);

    if (!$entity) {
    throw $this->createNotFoundException('Unable to find Noticia entity.');
    }

    $deleteForm = $this->createDeleteForm($id);
    $editForm = $this->createEditForm($entity);
    $editForm->handleRequest($request);
    $accion = ' ';
    $this->get("gema.utiles")->traza($accion);
    $em->flush();

    
 

    
    return $this->redirect($this->generateUrl('admin_noticias_edit', array('id' => $id)));
  
}
    /**
     * Deletes a Noticia entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('gemaBundle:Noticia')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Noticia entity.');
            }
            
        $accion = ' Noticia Eliminada';
        $guid=$entity->getGuid();
        $webPath = $this->get('kernel')->getRootDir().'/../web/noticia/'.$guid;
        $webPathg = $this->get('kernel')->getRootDir().'/../web/noticia/'.$guid.'G';
        $this->get("gema.utiles")->traza($accion);
            $em->remove($entity);
            $em->flush();
        $helper=new MyHelper();
        $helper->RemoveFolder($webPath);
        $helper->RemoveFolder($webPathg);
        return $this->redirect($this->generateUrl('admin_noticias'));
    }

    /**
     * Creates a form to delete a Noticia entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_noticias_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Eliminar'))
            ->getForm()
        ;
    }
}
