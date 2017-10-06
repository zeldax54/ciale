<?php

namespace GEMA\gemaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use GEMA\gemaBundle\Entity\Staff;
use GEMA\gemaBundle\Form\StaffType;
use Symfony\Component\HttpFoundation\JsonResponse;
use GEMA\gemaBundle\Helpers\MyHelper;

/**
 * Staff controller.
 *
 */
class StaffController extends Controller
{

    /**
     * Lists all Staff entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $helper=new MyHelper();
        $entities = array();
        if ($request->isXmlHttpRequest()) {
            $repo = $em->getRepository('gemaBundle:Staff');
            $qb = $repo->filtrar($request);
            $result = $this->get("gema.utiles")->paginar($request->get("current"), $request->get("rowCount"), $qb->getQuery());
            return new JsonResponse($result);
        } else {
            $entities = $em->getRepository('gemaBundle:Staff')->findBy(array(), array('orden' => 'ASC'));
        }
        $accion = 'Listar Expedientes de Staff';
        $this->get("gema.utiles")->traza($accion);
        
        foreach($entities as $e){
            $img=$helper->randomPic('staff'.DIRECTORY_SEPARATOR.$e->getGuid().DIRECTORY_SEPARATOR);
            if($img==null)
                $img=$helper->directPic('genericfiles'.DIRECTORY_SEPARATOR,'user.png');
            $e->foto=$img;
        }
        $proseleccionada=1;
        $provincias = $em->getRepository('gemaBundle:Provincia')->findAll();
        return $this->render('gemaBundle:Staff:index.html.twig', array(
                    'entities' => $entities,
                    'provincias'=>$provincias,
                    'proseleccionada'=>$proseleccionada,
            'isvendedor'=>0,
            'param'=>'staff'
        ));
    }


    public function mixedAction($idprovincia){


        $em = $this->getDoctrine()->getManager();
        $helper=new MyHelper();

     if($idprovincia==1)
     {
         $imagenin='staff';
         $isvendedor=0;
         $entities = $em->getRepository('gemaBundle:Staff')->findAll();
         $provincianame=null;
         $distribuidores=null;
     }
      else
     {
       $entities = $em->getRepository('gemaBundle:Vendedor')->findByprovincia($idprovincia);
       $imagenin='vendedor';
       $isvendedor=1;
         $provincianame = $em->getRepository('gemaBundle:Provincia')->find($idprovincia);
         $distribuidores=$em->getRepository('gemaBundle:Distribuidorlocal')->findByprovincia($idprovincia);
     }

        foreach($entities as $e){
            $img=$helper->randomPic($imagenin.DIRECTORY_SEPARATOR.$e->getGuid().DIRECTORY_SEPARATOR);
            if($img==null)
                $img=$helper->directPic('genericfiles'.DIRECTORY_SEPARATOR,'user.png');
            $e->foto=$img;
        }

        $provincias = $em->getRepository('gemaBundle:Provincia')->findAll();
        return $this->render('gemaBundle:Staff:index.html.twig', array(
            'entities' => $entities,
            'provincias'=>$provincias,
            'proseleccionada'=>$idprovincia,
            'isvendedor'=>$isvendedor,
            'provincianame'=>$provincianame,
            'param'=>$imagenin,
            'distribuidores'=>$distribuidores
        ));
    }




    /**
     * Creates a new Staff entity.
     *
     */
    public function createAction(Request $request)
    {
    
        $entity = new Staff();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $accion = '';
            $this->get("gema.utiles")->traza($accion);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_staff'));
        }
        $helper=new MyHelper();
        return $this->render('gemaBundle:Staff:new.html.twig', array(
                    'entity' => $entity,
                    'guid'=> $helper->GUID(),
                    'form' => $form->createView(),

        ));
    }

    /**
    * Creates a form to create a Staff entity.
    *
    * @param Staff $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Staff $entity)
    {
        $form = $this->createForm(new StaffType(), $entity, array(
            'action' => $this->generateUrl('admin_staff_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Crear'));

        return $form;
    }

    /**
     * Displays a form to create a new Staff entity.
     *
     */
    public function newAction()
    {
        $entity = new Staff();
        $form   = $this->createCreateForm($entity);
        $helper=new MyHelper();
    
        return $this->render('gemaBundle:Staff:new.html.twig', array(
            'entity' => $entity,
            'guid'=> $helper->GUID(),
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Staff entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:Staff')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Staff entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $accion = ' ';
        $this->get("gema.utiles")->traza($accion);

        return $this->render('gemaBundle:Staff:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Staff entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:Staff')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Staff entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('gemaBundle:Staff:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Staff entity.
    *
    * @param Staff $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Staff $entity)
    {
        $form = $this->createForm(new StaffType(), $entity, array(
            'action' => $this->generateUrl('admin_staff_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar'));

        return $form;
    }
/**
    * Edits an existing Staff entity.
*
    */
    public function updateAction(Request $request, $id)
{
    $em = $this->getDoctrine()->getManager();

    $entity = $em->getRepository('gemaBundle:Staff')->find($id);

    if (!$entity) {
    throw $this->createNotFoundException('Unable to find Staff entity.');
    }

    $deleteForm = $this->createDeleteForm($id);
    $editForm = $this->createEditForm($entity);
    $editForm->handleRequest($request);
    $accion = ' ';
    $this->get("gema.utiles")->traza($accion);
    $em->flush();

    
 

    
    return $this->redirect($this->generateUrl('admin_staff_edit', array('id' => $id)));
  
}
    /**
     * Deletes a Staff entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('gemaBundle:Staff')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Staff entity.');
            }
            
        $accion = 'Miembro de Staff Eliminado';
        $guid=$entity->getGuid();
        $webPath = $this->get('kernel')->getRootDir().'/../web/staff/'.$guid;
        $this->get("gema.utiles")->traza($accion);
            $em->remove($entity);
            $em->flush();
        $helper=new MyHelper();
        $helper->RemoveFolder($webPath);
        return $this->redirect($this->generateUrl('admin_staff'));
    }

    /**
     * Creates a form to delete a Staff entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_staff_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Eliminar'))
            ->getForm()
        ;
    }

    public function reorderAction(){

      $value=$_POST["value"];
        $em = $this->getDoctrine()->getManager();
        $repo= $em->getRepository('gemaBundle:Staff');
       foreach($value as $staff){


     $entity=$repo   ->findOneBy(
               array('nombre'=>$staff[1])
           );
           $entity->setOrden($staff[0]);
          // print($entity->getNombre());
        //   print($entity->getId());
           $em->persist($entity);

       }
        $em->flush();
        return new JsonResponse(array(
            0=>'yes'
        ));
    }

}
