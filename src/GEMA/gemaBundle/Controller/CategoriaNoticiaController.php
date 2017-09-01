<?php

namespace GEMA\gemaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use GEMA\gemaBundle\Entity\CategoriaNoticia;
use GEMA\gemaBundle\Form\CategoriaNoticiaType;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * CategoriaNoticia controller.
 *
 */
class CategoriaNoticiaController extends Controller
{

    /**
     * Lists all CategoriaNoticia entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = array();
        if ($request->isXmlHttpRequest()) {
            $repo = $em->getRepository('gemaBundle:CategoriaNoticia');
            $qb = $repo->filtrar($request);
            $result = $this->get("gema.utiles")->paginar($request->get("current"), $request->get("rowCount"), $qb->getQuery());
            return new JsonResponse($result);
        } else {
            $entities = $em->getRepository('gemaBundle:CategoriaNoticia')->findAll();
        }
        $accion = 'Listar Expedientes de CategoriaNoticia';
        $this->get("gema.utiles")->traza($accion);
        
        

        return $this->render('gemaBundle:CategoriaNoticia:index.html.twig', array(
                    'entities' => $entities,
        ));
    }
    /**
     * Creates a new CategoriaNoticia entity.
     *
     */
    public function createAction(Request $request)
    {
    
        $entity = new CategoriaNoticia();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $accion = '';
            $this->get("gema.utiles")->traza($accion);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_categorianoticia'));
        }



    
       return $this->render('gemaBundle:CategoriaNoticia:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a CategoriaNoticia entity.
    *
    * @param CategoriaNoticia $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(CategoriaNoticia $entity)
    {
        $form = $this->createForm(new CategoriaNoticiaType(), $entity, array(
            'action' => $this->generateUrl('admin_categorianoticia_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Crear'));

        return $form;
    }

    /**
     * Displays a form to create a new CategoriaNoticia entity.
     *
     */
    public function newAction()
    {
        $entity = new CategoriaNoticia();
        $form   = $this->createCreateForm($entity);

    
        return $this->render('gemaBundle:CategoriaNoticia:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a CategoriaNoticia entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:CategoriaNoticia')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CategoriaNoticia entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $accion = ' ';
        $this->get("gema.utiles")->traza($accion);

        return $this->render('gemaBundle:CategoriaNoticia:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing CategoriaNoticia entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:CategoriaNoticia')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CategoriaNoticia entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('gemaBundle:CategoriaNoticia:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a CategoriaNoticia entity.
    *
    * @param CategoriaNoticia $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(CategoriaNoticia $entity)
    {
        $form = $this->createForm(new CategoriaNoticiaType(), $entity, array(
            'action' => $this->generateUrl('admin_categorianoticia_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar'));

        return $form;
    }
/**
    * Edits an existing CategoriaNoticia entity.
*
    */
    public function updateAction(Request $request, $id)
{
    $em = $this->getDoctrine()->getManager();

    $entity = $em->getRepository('gemaBundle:CategoriaNoticia')->find($id);

    if (!$entity) {
    throw $this->createNotFoundException('Unable to find CategoriaNoticia entity.');
    }

    $deleteForm = $this->createDeleteForm($id);
    $editForm = $this->createEditForm($entity);
    $editForm->handleRequest($request);
    $accion = ' ';
    $this->get("gema.utiles")->traza($accion);
    $em->flush();





    return $this->redirect($this->generateUrl('admin_categorianoticia'));
  
}
    /**
     * Deletes a CategoriaNoticia entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('gemaBundle:CategoriaNoticia')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find CategoriaNoticia entity.');
            }
            
        $accion = ' ';
        $this->get("gema.utiles")->traza($accion);
            $em->remove($entity);
            $em->flush();

        return $this->redirect($this->generateUrl('admin_categorianoticia'));
    }

    /**
     * Creates a form to delete a CategoriaNoticia entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_categorianoticia_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Eliminar'))
            ->getForm()
        ;
    }
}
