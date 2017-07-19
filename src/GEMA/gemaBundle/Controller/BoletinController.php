<?php

namespace GEMA\gemaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use GEMA\gemaBundle\Entity\Boletin;
use GEMA\gemaBundle\Form\BoletinType;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Boletin controller.
 *
 */
class BoletinController extends Controller
{

    /**
     * Lists all Boletin entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = array();
        if ($request->isXmlHttpRequest()) {
            $repo = $em->getRepository('gemaBundle:Boletin');
            $qb = $repo->filtrar($request);
            $result = $this->get("gema.utiles")->paginar($request->get("current"), $request->get("rowCount"), $qb->getQuery());
            return new JsonResponse($result);
        } else {
            $entities = $em->getRepository('gemaBundle:Boletin')->findAll();
        }
        $accion = 'Listar Expedientes de Boletin';
        $this->get("gema.utiles")->traza($accion);
        
        

        return $this->render('gemaBundle:Boletin:index.html.twig', array(
                    'entities' => $entities,
        ));
    }
    /**
     * Creates a new Boletin entity.
     *
     */
    public function createAction(Request $request)
    {


        $entity = new Boletin();

        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $accion = '';
            $this->get("gema.utiles")->traza($accion);
            $em->persist($entity);
            $em->flush();
            //
            $archivos= $request->request->get('textareas');
            $nombres= $request->request->get('nombresarray');

            for($i=0;$i<count($archivos);$i++)
            {
                $webPath = $this->get('kernel')->getRootDir().'/../web/boletines/'.$entity->getId();
                $foto=$archivos[$i];
                $nombrefoto=$nombres[$i];
                $baseFromJavascript=$foto;
                $base_to_php = explode(',', $baseFromJavascript);
                $data = base64_decode($base_to_php[1]);
                if (!file_exists($webPath)) {
                    mkdir($webPath, 0777, true);
                }

                $webPath=$webPath.'/'.$nombrefoto;
                file_put_contents($webPath,$data);
            }

            //
            return $this->redirect($this->generateUrl('boletin'));
        }



    
       return $this->render('gemaBundle:Boletin:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a Boletin entity.
    *
    * @param Boletin $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Boletin $entity)
    {
        $form = $this->createForm(new BoletinType(), $entity, array(
            'action' => $this->generateUrl('boletin_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Crear'));

        return $form;
    }

    /**
     * Displays a form to create a new Boletin entity.
     *
     */
    public function newAction()
    {
        $entity = new Boletin();

        $form   = $this->createCreateForm($entity);

    
        return $this->render('gemaBundle:Boletin:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Boletin entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:Boletin')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Boletin entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $accion = ' ';
        $this->get("gema.utiles")->traza($accion);

        return $this->render('gemaBundle:Boletin:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Boletin entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:Boletin')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Boletin entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('gemaBundle:Boletin:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'idboletin'=>$entity->getId()
        ));
    }

    /**
    * Creates a form to edit a Boletin entity.
    *
    * @param Boletin $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Boletin $entity)
    {
        $form = $this->createForm(new BoletinType(), $entity, array(
            'action' => $this->generateUrl('boletin_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar'));

        return $form;
    }
/**
    * Edits an existing Boletin entity.
*
    */
    public function updateAction(Request $request, $id)
{
    $em = $this->getDoctrine()->getManager();

    $entity = $em->getRepository('gemaBundle:Boletin')->find($id);

    if (!$entity) {
    throw $this->createNotFoundException('Unable to find Boletin entity.');
    }

    $deleteForm = $this->createDeleteForm($id);
    $editForm = $this->createEditForm($entity);
    $editForm->handleRequest($request);
    $accion = ' ';
    $this->get("gema.utiles")->traza($accion);
    $em->flush();
    return $this->redirect($this->generateUrl('boletin_edit', array('id' => $id)));
  
}
    /**
     * Deletes a Boletin entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('gemaBundle:Boletin')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Boletin entity.');
            }
            
        $accion = 'Boletin Borrado';
        $this->get("gema.utiles")->traza($accion);
        $ident=$entity->getId();

        $webPath = $this->get('kernel')->getRootDir().'/../web/boletines/'.$ident;
            $em->remove($entity);
            $em->flush();
if(file_exists($webPath))
        $this->_remove_path($webPath);

        return $this->redirect($this->generateUrl('boletin'));
    }

    function _remove_path($folder){
        $files = glob( $folder . DIRECTORY_SEPARATOR . '*');
        foreach( $files as $file ){
            if($file == '.' || $file == '..'){continue;}
            if(is_dir($file)){
                $this->_remove_path( $file );
            }else{
                unlink( $file );
            }
        }
        rmdir( $folder );
    }

    /**
     * Creates a form to delete a Boletin entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('boletin_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Eliminar'))
            ->getForm()
        ;
    }
}
