<?php

namespace GEMA\gemaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use GEMA\gemaBundle\Entity\Catalogohojas;
use GEMA\gemaBundle\Form\CatalogohojasType;
use Symfony\Component\HttpFoundation\JsonResponse;
use GEMA\gemaBundle\Helpers\MyHelper;

/**
 * Catalogohojas controller.
 *
 */
class CatalogohojasController extends Controller
{

    /**
     * Lists all Catalogohojas entities.
     *
     */


    public function hojasbyparentAction($id){

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('gemaBundle:Catalogohojas');
        $entities = $em->getRepository('gemaBundle:Catalogohojas')->OrderedbyParent($id);
        $helper=new MyHelper();
        foreach($entities as $e){
            if(trim($e->getTipo())=='Imagen'){
                $img=$helper->randomPic('catalogohojas'.DIRECTORY_SEPARATOR.$e->getGuid().DIRECTORY_SEPARATOR,true);            
                $e->foto=$img;
            }
            else{
                $toros = $e->getToros();
                $apodos=[];
                foreach($toros as $t)
                  $apodos[] = $t->getApodo();
                $e->foto= implode(",", $apodos);
            }            
        }
        return $this->render('gemaBundle:Catalogohojas:index.html.twig', array(
            'entities' => $entities,
         ));
    }

    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $helper=new MyHelper();
        $entities = array();
        if ($request->isXmlHttpRequest()) {
            $repo = $em->getRepository('gemaBundle:Catalogohojas');
            $qb = $repo->filtrar($request);
            $result = $this->get("gema.utiles")->paginar($request->get("current"), $request->get("rowCount"), $qb->getQuery());
            return new JsonResponse($result);
        } else {
            $entities = $em->getRepository('gemaBundle:Catalogohojas')->Ordered();
        }
        $accion = 'Listar Expedientes de Catalogohojas';
        $this->get("gema.utiles")->traza($accion);
        foreach($entities as $e){
            if(trim($e->getTipo())=='Imagen'){
                $img=$helper->randomPic('catalogohojas'.DIRECTORY_SEPARATOR.$e->getGuid().DIRECTORY_SEPARATOR,true);            
                $e->foto=$img;
            }
            else{
                $toros = $e->getToros();
                $apodos=[];
                foreach($toros as $t)
                  $apodos[] = $t->getApodo();
                $e->foto= implode(",", $apodos);
            }
            
        }        

        return $this->render('gemaBundle:Catalogohojas:index.html.twig', array(
                    'entities' => $entities,
        ));
    }
    /**
     * Creates a new Catalogohojas entity.
     *
     */
    public function createAction(Request $request)
    {
    
        $entity = new Catalogohojas();
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
             $helper->CreateDir( $webPath=$this->get('kernel')->getRootDir().'/../web/','catalogohojas',$entity->getGuid());       

            return $this->redirect($this->generateUrl('catalogohojas', array('id' => $entity->getId())));
        }



    
       return $this->render('gemaBundle:Catalogohojas:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a Catalogohojas entity.
    *
    * @param Catalogohojas $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Catalogohojas $entity)
    {
        $form = $this->createForm(new CatalogohojasType(), $entity, array(
            'action' => $this->generateUrl('catalogohojas_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Crear'));

        return $form;
    }

    /**
     * Displays a form to create a new Catalogohojas entity.
     *
     */
    public function newAction()
    {
        $entity = new Catalogohojas();
        $form   = $this->createCreateForm($entity);
        $helper=new MyHelper();
    
        return $this->render('gemaBundle:Catalogohojas:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'guid'=> $helper->GUID()
        ));
    }

    /**
     * Finds and displays a Catalogohojas entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:Catalogohojas')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Catalogohojas entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $accion = ' ';
        $this->get("gema.utiles")->traza($accion);

        return $this->render('gemaBundle:Catalogohojas:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Catalogohojas entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:Catalogohojas')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Catalogohojas entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('gemaBundle:Catalogohojas:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Catalogohojas entity.
    *
    * @param Catalogohojas $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Catalogohojas $entity)
    {
        $form = $this->createForm(new CatalogohojasType(), $entity, array(
            'action' => $this->generateUrl('catalogohojas_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar'));

        return $form;
    }
/**
    * Edits an existing Catalogohojas entity.
*
    */
    public function updateAction(Request $request, $id)
{
    $em = $this->getDoctrine()->getManager();

    $entity = $em->getRepository('gemaBundle:Catalogohojas')->find($id);

    if (!$entity) {
    throw $this->createNotFoundException('Unable to find Catalogohojas entity.');
    }

    $deleteForm = $this->createDeleteForm($id);
    $editForm = $this->createEditForm($entity);
    $editForm->handleRequest($request);
    $accion = ' ';
    $this->get("gema.utiles")->traza($accion);
    $em->flush();

    
 

    
    return $this->redirect($this->generateUrl('catalogohojas_edit', array('id' => $id)));
  
}
    /**
     * Deletes a Catalogohojas entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('gemaBundle:Catalogohojas')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Catalogohojas entity.');
            }
            
        $accion = ' ';
        $this->get("gema.utiles")->traza($accion);
        $guid=$entity->getGuid();
        $webPath = $this->get('kernel')->getRootDir().'/../web/catalogohojas/'.$guid;
            $em->remove($entity);
            $em->flush();
         //Eliminando
        $helper=new MyHelper();
        $helper->RemoveFolder($webPath);
        return $this->redirect($this->generateUrl('catalogohojas'));
    }

    /**
     * Creates a form to delete a Catalogohojas entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('catalogohojas_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Eliminar'))
            ->getForm()
        ;
    }


    public function reorderhojasAction(){

        $neworder=$_POST['ordered'];
        $em = $this->getDoctrine()->getManager();
        $repo= $em->getRepository('gemaBundle:Catalogohojas');
        foreach($neworder as $o){
            $hoja = $repo->find($o[1]);
            $hoja->setNumero($o[0]);  
        }
        $em->flush();
        return new JsonResponse(array(
            0=>'yes'
        ));
    }
}
