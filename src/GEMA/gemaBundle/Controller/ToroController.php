<?php

namespace GEMA\gemaBundle\Controller;




use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use GEMA\gemaBundle\Entity\Toro;
use GEMA\gemaBundle\Form\ToroType;
use Symfony\Component\HttpFoundation\JsonResponse;
use GEMA\gemaBundle\Helpers\MyHelper;

/**
 * Toro controller.
 *
 */
class ToroController extends Controller
{

    /**
     * Lists all Toro entities.
     * @param Request $request
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entities = array();
        if ($request->isXmlHttpRequest()) {
            $repo = $em->getRepository('gemaBundle:Toro');
            $qb = $repo->filtrar($request);
            $result = $this->get("gema.utiles")->paginar($request->get("current"), $request->get("rowCount"), $qb->getQuery());
            return new JsonResponse($result);
        } else {
            $entities = $em->getRepository('gemaBundle:Toro')->findAll();
        }
        $accion = 'Listar Expedientes de Toro';
        $this->get("gema.utiles")->traza($accion);
        $razas=$em->getRepository('gemaBundle:Raza')->findAll();
        return $this->render('gemaBundle:Toro:index.html.twig', array(
                    'entities' => $entities,
            'razas'=>$razas
        ));


    }
    /**
     * Creates a new Toro entity.
     *
     */
    public function createAction(Request $request)
    {
       $idraza=$request->request->get('gema_gemabundle_toro')['raza'];
        $em = $this->getDoctrine()->getManager();
        $ismocho=$em->getRepository('gemaBundle:Raza')->find($idraza)->getMocho();
        $entity = new Toro();
        $form = $this->createCreateForm($entity,$idraza,$ismocho);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $accion = 'Toro Creado';
            $this->get("gema.utiles")->traza($accion);
            $em->persist($entity);
            $em->flush();
            $helper=new MyHelper();
            $helper->CreateDir( $webPath=$this->get('kernel')->getRootDir().'/../web/','toro',$entity->getGuid());
            return $this->redirect($this->generateUrl('admin_toro_'));
        }
        $helper=new MyHelper();
       return $this->render('gemaBundle:Toro:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
           'guid'=> $helper->GUID(),
           'raza'=>$this->getDoctrine()->getManager()->getRepository('gemaBundle:Raza')->find($idraza)
        ));
    }

    /**
    * Creates a form to create a Toro entity.
    *
    * @param Toro $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Toro $entity,$idraza,$Ismocho)
    {

        $form = $this->createForm(new ToroType($idraza,$Ismocho), $entity, array(
            'action' => $this->generateUrl('admin_toro__create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Crear'));

        return $form;
    }

    /**
     * Displays a form to create a new Toro entity.
     *
     */
    public function newAction($idraza)
    {
        $entity = new Toro();
        $em = $this->getDoctrine()->getManager();
        $raza=$em->getRepository('gemaBundle:Raza')->find($idraza);
        $ismocho=$raza->getMocho();
      //  print_r($raza->getNombre());die();

        $form   = $this->createCreateForm($entity,$idraza,$ismocho);
        $helper=new MyHelper();
    
        return $this->render('gemaBundle:Toro:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'guid'=> $helper->GUID(),
            'raza'=>$raza
        ));
    }

    /**
     * Finds and displays a Toro entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:Toro')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Toro entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $accion = ' ';
        $this->get("gema.utiles")->traza($accion);

        return $this->render('gemaBundle:Toro:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Toro entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:Toro')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Toro entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);
        $array=json_decode($entity->getTablagenetica(),true);
        $helper=new MyHelper();
        $helper->CreateDir( $webPath=$this->get('kernel')->getRootDir().'/../web/','toro',$entity->getGuid());
        return $this->render('gemaBundle:Toro:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'raza'=>$entity->getRaza(),
            'tablagen'=>$array
        ));
    }

    /**
    * Creates a form to edit a Toro entity.
    *
    * @param Toro $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Toro $entity)
    {
        $form = $this->createForm(new ToroType($entity->getRaza()->getId(),$entity->getRaza()->getMocho()), $entity, array(
            'action' => $this->generateUrl('admin_toro__update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar'));

        return $form;
    }
/**
    * Edits an existing Toro entity.
*
    */
    public function updateAction(Request $request, $id)
{
    $em = $this->getDoctrine()->getManager();

    $entity = $em->getRepository('gemaBundle:Toro')->find($id);

    if (!$entity) {
    throw $this->createNotFoundException('Unable to find Toro entity.');
    }
    //Actualizando Videos

    //Actualizando Dptos
    $originalvideos=new ArrayCollection();
    foreach($entity->getYoutubes() as $youtub)
    {

        $originalvideos->add($youtub);
    }


    $deleteForm = $this->createDeleteForm($id);
    $editForm = $this->createEditForm($entity);
    $editForm->handleRequest($request);

    //Continue Update Youtubes

    foreach ($originalvideos as $video) {

        if (false === $entity->getYoutubes()->contains($video)) {


            // remove the Task from the Tag
            $entity->getYoutubes()->removeElement($video);
            // if it was a many-to-one relationship, remove the relationship  like this
            $em->remove($video);
            // $em->persist($dpto);

            // if you wanted to delete the Tag entirely, you can also do that
            // $em->remove($tag);
        }
    }

    $em->persist($entity);
    $accion = ' ';
    $this->get("gema.utiles")->traza($accion);
    $em->flush();
    
    return $this->redirect($this->generateUrl('admin_toro_'));
  
}
    /**
     * Deletes a Toro entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('gemaBundle:Toro')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Toro entity.');
            }
            
        $accion = 'Toro Eliminado';
        $guid=$entity->getGuid();
        $webPath = $this->get('kernel')->getRootDir().'/../web/toro/'.$guid;
        $webPath2 = $this->get('kernel')->getRootDir().'/../web/toro/'.$guid.'P';
        $this->get("gema.utiles")->traza($accion);
        foreach($entity->getYoutubes() as $y){
            $em->remove($y);
        }
            $em->remove($entity);
            $em->flush();
      //Eliminando
        $helper=new MyHelper();
        $helper->RemoveFolder($webPath);
        $helper->RemoveFolder($webPath2);
        return $this->redirect($this->generateUrl('admin_toro_'));
    }

    /**
     * Creates a form to delete a Toro entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_toro__delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Eliminar'))
            ->getForm()
        ;
    }

    public function changepublicAction($id){

        try{

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('gemaBundle:Toro')->find($id);

            $pubstate=$entity->getPublico();
        if($pubstate==1){
            $entity->setPublico(0);
        }else{
            $entity->setPublico(1);
        }
            $em->flush();
            $arr=array(0=>1);
            return new JsonResponse($arr);

        }catch(\Exception $e){
            $arr=array(0=>0);
            return new JsonResponse($arr);
        }
    }



    public function fpFilterAction($fp,$isfp){

        try{


            $em = $this->getDoctrine()->getManager();
            if($isfp==='true'){
                $entities = $em->getRepository('gemaBundle:Toro')->findBy(array(
                    'facilidadparto'=>$fp
                ));
            }

            else{

                $entities = $em->getRepository('gemaBundle:Toro')->findBy(array(
                    'CP'=>true
                ));
            }

            $arr=array();
            if(count($entities)==0)
                return -1;
            foreach($entities as $toro)
                $arr[]=$toro->getId();

            return new JsonResponse($arr);

        }
        catch(\Exception $e){
            return $e->getMessage();
        }

    }
}
