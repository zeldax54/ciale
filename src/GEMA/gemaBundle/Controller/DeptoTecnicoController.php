<?php

namespace GEMA\gemaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use GEMA\gemaBundle\Entity\DeptoTecnico;
use GEMA\gemaBundle\Form\DeptoTecnicoType;
use Symfony\Component\HttpFoundation\JsonResponse;
use GEMA\gemaBundle\Helpers\MyHelper;

/**
 * DeptoTecnico controller.
 *
 */
class DeptoTecnicoController extends Controller
{

    /**
     * Lists all DeptoTecnico entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = array();
        if ($request->isXmlHttpRequest()) {
            $repo = $em->getRepository('gemaBundle:DeptoTecnico');
            $qb = $repo->filtrar($request);
            $result = $this->get("gema.utiles")->paginar($request->get("current"), $request->get("rowCount"), $qb->getQuery());
            return new JsonResponse($result);
        } else {
            $entities = $em->getRepository('gemaBundle:DeptoTecnico')->findAll();
        }
        $accion = 'Listar Expedientes de DeptoTecnico';
        $this->get("gema.utiles")->traza($accion);
        $helper=new MyHelper();
        foreach($entities as $e){
            $img=$helper->randomPic('deptotecnico'.DIRECTORY_SEPARATOR.$e->getGuid().DIRECTORY_SEPARATOR);
            if($img==null)
                $img=$helper->directPic('genericfiles'.DIRECTORY_SEPARATOR,'user.png');
            $e->foto=$img;
        }
        

        return $this->render('gemaBundle:DeptoTecnico:index.html.twig', array(
                    'entities' => $entities,
        ));
    }
    /**
     * Creates a new DeptoTecnico entity.
     *
     */
    public function createAction(Request $request)
    {
    
        $entity = new DeptoTecnico();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $accion = 'Miembro del Departamento tecnico borrado';
            $guid=$entity->getGuid();
            $webPath = $this->get('kernel')->getRootDir().'/../web/deptotecnico/'.$guid;
            $this->get("gema.utiles")->traza($accion);
            $em->persist($entity);
            $em->flush();
            $helper=new MyHelper();
            $helper->RemoveFolder($webPath);
            return $this->redirect($this->generateUrl('admin_deptotecnico'));
        }



        $helper=new MyHelper();
       return $this->render('gemaBundle:DeptoTecnico:new.html.twig', array(
                    'entity' => $entity,
                     'guid'=> $helper->GUID(),
                    'form' => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a DeptoTecnico entity.
    *
    * @param DeptoTecnico $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(DeptoTecnico $entity)
    {
        $form = $this->createForm(new DeptoTecnicoType(), $entity, array(
            'action' => $this->generateUrl('admin_deptotecnico_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Crear'));

        return $form;
    }

    /**
     * Displays a form to create a new DeptoTecnico entity.
     *
     */
    public function newAction()
    {
        $entity = new DeptoTecnico();
        $form   = $this->createCreateForm($entity);
        $helper=new MyHelper();
    
        return $this->render('gemaBundle:DeptoTecnico:new.html.twig', array(
            'entity' => $entity,
            'guid'=> $helper->GUID(),
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a DeptoTecnico entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:DeptoTecnico')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find DeptoTecnico entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $accion = ' ';
        $this->get("gema.utiles")->traza($accion);

        return $this->render('gemaBundle:DeptoTecnico:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing DeptoTecnico entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:DeptoTecnico')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find DeptoTecnico entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('gemaBundle:DeptoTecnico:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a DeptoTecnico entity.
    *
    * @param DeptoTecnico $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(DeptoTecnico $entity)
    {
        $form = $this->createForm(new DeptoTecnicoType(), $entity, array(
            'action' => $this->generateUrl('admin_deptotecnico_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar'));

        return $form;
    }
/**
    * Edits an existing DeptoTecnico entity.
*
    */
    public function updateAction(Request $request, $id)
{
    $em = $this->getDoctrine()->getManager();

    $entity = $em->getRepository('gemaBundle:DeptoTecnico')->find($id);

    if (!$entity) {
    throw $this->createNotFoundException('Unable to find DeptoTecnico entity.');
    }

    $deleteForm = $this->createDeleteForm($id);
    $editForm = $this->createEditForm($entity);
    $editForm->handleRequest($request);
    $accion = ' ';
    $this->get("gema.utiles")->traza($accion);
    $em->flush();





    return $this->redirect($this->generateUrl('admin_deptotecnico'));
  
}
    /**
     * Deletes a DeptoTecnico entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('gemaBundle:DeptoTecnico')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find DeptoTecnico entity.');
            }
            
        $accion = ' ';
        $this->get("gema.utiles")->traza($accion);
            $em->remove($entity);
            $em->flush();

        return $this->redirect($this->generateUrl('admin_deptotecnico'));
    }

    /**
     * Creates a form to delete a DeptoTecnico entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_deptotecnico_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Eliminar'))
            ->getForm()
        ;
    }
}
