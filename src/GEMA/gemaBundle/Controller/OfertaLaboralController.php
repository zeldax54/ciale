<?php

namespace GEMA\gemaBundle\Controller;

use Exception;
use GEMA\gemaBundle\Entity\Postulaciones;
use GEMA\gemaBundle\Helpers\MyHelper;
use Swift_TransportException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use GEMA\gemaBundle\Entity\OfertaLaboral;
use GEMA\gemaBundle\Form\OfertaLaboralType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
/**
 * OfertaLaboral controller.
 *
 */
class OfertaLaboralController extends Controller
{


     #region backend
    /**
     * Lists all OfertaLaboral entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = array();
        if ($request->isXmlHttpRequest()) {
            $repo = $em->getRepository('gemaBundle:OfertaLaboral');
            $qb = $repo->filtrar($request);
            $result = $this->get("gema.utiles")->paginar($request->get("current"), $request->get("rowCount"), $qb->getQuery());
            return new JsonResponse($result);
        } else {
            $entities = $em->getRepository('gemaBundle:OfertaLaboral')->findAll();
        }
        $accion = 'Listar Expedientes de OfertaLaboral';
        $this->get("gema.utiles")->traza($accion);
        
        

        return $this->render('gemaBundle:OfertaLaboral:index.html.twig', array(
                    'entities' => $entities,
        ));
    }
    /**
     * Creates a new OfertaLaboral entity.
     *
     */
    public function createAction(Request $request)
    {
    
        $entity = new OfertaLaboral();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $accion = '';
            $this->get("gema.utiles")->traza($accion);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('ofertalaboral_show', array('id' => $entity->getId())));
        }



    
       return $this->render('gemaBundle:OfertaLaboral:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a OfertaLaboral entity.
    *
    * @param OfertaLaboral $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(OfertaLaboral $entity)
    {
        $form = $this->createForm(new OfertaLaboralType(), $entity, array(
            'action' => $this->generateUrl('ofertalaboral_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Crear'));

        return $form;
    }

    /**
     * Displays a form to create a new OfertaLaboral entity.
     *
     */
    public function newAction()
    {
        $entity = new OfertaLaboral();
        $form   = $this->createCreateForm($entity);

    
        return $this->render('gemaBundle:OfertaLaboral:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a OfertaLaboral entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:OfertaLaboral')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find OfertaLaboral entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $accion = ' ';
        $this->get("gema.utiles")->traza($accion);

        return $this->render('gemaBundle:OfertaLaboral:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing OfertaLaboral entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:OfertaLaboral')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find OfertaLaboral entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('gemaBundle:OfertaLaboral:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a OfertaLaboral entity.
    *
    * @param OfertaLaboral $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(OfertaLaboral $entity)
    {
        $form = $this->createForm(new OfertaLaboralType(), $entity, array(
            'action' => $this->generateUrl('ofertalaboral_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar'));

        return $form;
    }
/**
    * Edits an existing OfertaLaboral entity.
*
    */
    public function updateAction(Request $request, $id)
{
    $em = $this->getDoctrine()->getManager();

    $entity = $em->getRepository('gemaBundle:OfertaLaboral')->find($id);

    if (!$entity) {
    throw $this->createNotFoundException('Unable to find OfertaLaboral entity.');
    }

    $deleteForm = $this->createDeleteForm($id);
    $editForm = $this->createEditForm($entity);
    $editForm->handleRequest($request);
    $accion = ' ';
    $this->get("gema.utiles")->traza($accion);
    $em->flush();

    
 

    
    return $this->redirect($this->generateUrl('ofertalaboral_edit', array('id' => $id)));
  
}
    /**
     * Deletes a OfertaLaboral entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('gemaBundle:OfertaLaboral')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find OfertaLaboral entity.');
            }
            
        $accion = ' ';
        $this->get("gema.utiles")->traza($accion);
            $em->remove($entity);
            $em->flush();

        return $this->redirect($this->generateUrl('ofertalaboral'));
    }

    /**
     * Creates a form to delete a OfertaLaboral entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('ofertalaboral_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Eliminar'))
            ->getForm()
        ;
    }
    #endregion

    #region frontend

    public function OfertasIndexAction(){

        $ema = $this->getDoctrine()->getManager();
        $ofertas= $ema->getRepository('gemaBundle:OfertaLaboral')->findBy(
            array(
                'activa'=>true
            )
        );
        $helper=new MyHelper();
        $imgoferta=$helper->directPic('ofertas'.DIRECTORY_SEPARATOR,'img_list.jpg');
        return $this->render('gemaBundle:Page:ofertas.html.twig', array(
            'ofertas'=>$ofertas,
             'imgofertas'=>$imgoferta
        ));

    }

    public function PostulacionIndexAction($idoferta){

        $ema = $this->getDoctrine()->getManager();
        $apikey= $this->getParameter('apikey');
        $actividades=$ema->getRepository('gemaBundle:Actividad')->findAll();
        $areas=$ema->getRepository('gemaBundle:Area')->findAll();
        $oferta= $ema->getRepository('gemaBundle:OfertaLaboral')->find(
           $idoferta
        );
        $helper=new MyHelper();
        $imgpostulacion=$helper->directPic('ofertas'.DIRECTORY_SEPARATOR,'img_psotulacion.jpg');

        return $this->render('gemaBundle:Page:postulacion.html.twig', array(
            'oferta'=>$oferta,
            'apikey'=>$apikey,
             'actividades'=>$actividades,
             'areas'=>$areas,
              'imgpostulacion'=>$imgpostulacion

        ));
    }

        public function PostulacionPostAction(Request $request){

            try{
                $recaptcha = $request->request->get('g-recaptcha-response');
                $url = 'https://www.google.com/recaptcha/api/siteverify';
                $data = array(
                    'secret' => $this->getParameter('apisecret'),
                    'response' => $recaptcha
                );
                $query=http_build_query($data);
                $options = array(
                    'http' => array (
                        'header' => "Content-Type: application/x-www-form-urlencoded\r\n".
                            "Content-Length: ".strlen($query)."\r\n".
                            "User-Agent:MyAgent/1.0\r\n",
                        'method' => 'POST',
                        'content' => $query
                    )
                );
                $context  = stream_context_create($options);
                $verify = file_get_contents($url, false, $context);
                $captcha_success=json_decode($verify,true);
                if ($captcha_success['success']==false) {

                    $this->addFlash(
                        'error',
                        'Captcha no completado.'
                    );
                    return $this->redirect($this->generateUrl('gema_ofertapostulacion',array(

                        'idoferta'=>$request->request->get('oferta')
                    )));

                }
                $ema = $this->getDoctrine()->getManager();
                $oferta=$ema->getRepository('gemaBundle:OfertaLaboral')->find($request->request->get('oferta'));
                $postulacion=new Postulaciones();
                $postulacion->setNombre($request->request->get('name'));
                $postulacion->setApellido($request->request->get('apellido'));
                $postulacion->setEmail($request->request->get('email'));
                $postulacion->setTelefono($request->request->get('phone'));
                $postulacion->setOferta($oferta );
                $postulacion->setFecha(new \DateTime());
                $postulacion->setNacionalidad($request->request->get('nacionalidad'));
                $postulacion->setProvincia($request->request->get('provincia'));
                $postulacion->setLocalidad($request->request->get('localidad'));
                $postulacion->setFechanacimiento($request->request->get('fechanacimiento'));
                $postulacion->setSexo($request->request->get('sexo'));
                $postulacion->setEstadocivil($request->request->get('estadocivil'));
                $postulacion->setHijos($request->request->get('hijos'));

                $postulacion->setActividad($ema->getRepository('gemaBundle:Actividad')->find(
                $request->request->get('actividad'))->getTitulo());

                $postulacion->setArea($ema->getRepository('gemaBundle:Area')->find(
                    $request->request->get('area'))->getTitulo());
                $postulacion->setTrabajo($request->request->get('trabajo'));



                $helper=new MyHelper();

                $guid=$helper->GUID();
                $postulacion->setGuid($guid);
                $weppath= $this->get('kernel')->getRootDir() . '/../web/postulaciones/' . $guid . '/';


                $file= $_FILES['curriculum'];
                $copiado=$helper->CopyFile($weppath,$file);

                if($copiado!=false)
                {
                    $postulacion->setArchivo($copiado);
                    $ema->persist($postulacion);
                    $ema->flush();
                    //Correo
                    $message = \Swift_Message::newInstance()
                        ->setSubject('Ofertas Laborales en Alta Ciale');
                    $message->setContentType("text/html");
                    $message->setFrom('info@ciale.com');
                    $message ->setTo($request->request->get('email'));
                    $message->setBcc('Diego.Peralta@altagenetics.com');

                    $html= 'Hemos recibido tu solicitud. ¡Muchas gracias!.Se ha postulado a '.$oferta->getTitulo();
                    $message->setBody(
                        $html
                    );
                    $this->get('mailer')->send($message);


                    $this->addFlash(
                        'error',
                        'Postulacion exitosa!!!. Le hemos enviado un correo de confirmación.'
                    );
                    return $this->redirect($this->generateUrl('gema_ofertapostulacion',array(
                        'idoferta'=>$request->request->get('oferta')
                    )));

                }
                else{
                    $this->addFlash(
                        'error',
                        'No se ha podido completar la postulacion. Informe al administrador del siito'
                    );
                    return $this->redirect($this->generateUrl('gema_ofertapostulacion',array(

                        'idoferta'=>$request->request->get('oferta')
                    )));
                }




       } catch(Swift_TransportException  $e){

                 $this->addFlash(
                    'error',
                    $e->getMessage()
                );
                    return $this->redirect($this->generateUrl('gema_ofertapostulacion',array(

                        'idoferta'=>$request->request->get('oferta')
                    )));
            }
            catch(Exception  $e){

                $this->addFlash(
                    'error',
                    $e->getMessage()
                );
                return $this->redirect($this->generateUrl('gema_ofertapostulacion',array(

                    'idoferta'=>$request->request->get('oferta')
                )));
            }
    }


    public function PostulacionDownloadAction($id){

        $ema = $this->getDoctrine()->getManager();
        $file=$ema->getRepository('gemaBundle:Postulaciones')->find($id);
        $response = new BinaryFileResponse($file->getArchivo());
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT);

        return $response;
    }

    #endregion
}
