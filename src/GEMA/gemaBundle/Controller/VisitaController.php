<?php

namespace GEMA\gemaBundle\Controller;

use Exception;
use GEMA\gemaBundle\Helpers\MyHelper;
use Swift_TransportException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use GEMA\gemaBundle\Entity\Visita;
use GEMA\gemaBundle\Form\VisitaType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * Visita controller.
 *
 */
class VisitaController extends Controller
{

    #region backend
    /**
     * Lists all Visita entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = array();
        if ($request->isXmlHttpRequest()) {
            $repo = $em->getRepository('gemaBundle:Visita');
            $qb = $repo->filtrar($request);
            $result = $this->get("gema.utiles")->paginar($request->get("current"), $request->get("rowCount"), $qb->getQuery());
            return new JsonResponse($result);
        } else {
            $entities = $em->getRepository('gemaBundle:Visita')->findAll();
        }
        $accion = 'Listar Expedientes de Visita';
        $this->get("gema.utiles")->traza($accion);



        return $this->render('gemaBundle:Visita:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Visita entity.
     *
     */
    public function createAction(Request $request)
    {

        $entity = new Visita();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $accion = '';
            $this->get("gema.utiles")->traza($accion);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('visita', array('id' => $entity->getId())));
        }




        return $this->render('gemaBundle:Visita:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Visita entity.
     *
     * @param Visita $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Visita $entity)
    {
        $form = $this->createForm(new VisitaType(), $entity, array(
            'action' => $this->generateUrl('visita_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Crear'));

        return $form;
    }

    /**
     * Displays a form to create a new Visita entity.
     *
     */
    public function newAction()
    {
        $entity = new Visita();
        $form   = $this->createCreateForm($entity);


        return $this->render('gemaBundle:Visita:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Visita entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:Visita')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Visita entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $accion = ' ';
        $this->get("gema.utiles")->traza($accion);

        return $this->render('gemaBundle:Visita:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Visita entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:Visita')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Visita entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('gemaBundle:Visita:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Visita entity.
     *
     * @param Visita $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Visita $entity)
    {
        $form = $this->createForm(new VisitaType(), $entity, array(
            'action' => $this->generateUrl('visita_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar'));

        return $form;
    }
    /**
     * Edits an existing Visita entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:Visita')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Visita entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        $accion = ' ';
        $this->get("gema.utiles")->traza($accion);
        $em->flush();





        return $this->redirect($this->generateUrl('visita_edit', array('id' => $id)));

    }
    /**
     * Deletes a Visita entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('gemaBundle:Visita')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Visita entity.');
        }

        $accion = ' ';
        $this->get("gema.utiles")->traza($accion);
        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('visita'));
    }

    /**
     * Creates a form to delete a Visita entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('visita_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Eliminar'))
            ->getForm()
            ;
    }



    #endregion



    #region frontEnd

    public function clientVisitaIndexAction(){
        $em = $this->getDoctrine()->getManager();
        $apikey= $this->getParameter('apikey');
        $razas = $em->getRepository('gemaBundle:Raza')->findAll();
        $html= $em->getRepository('gemaBundle:Singlehtml')->findOneBy(
            array(
                'nombre'=>'termycond'
            )
        )->getHtml();
        return $this->render('gemaBundle:Page:visita.html.twig', array(
            'apikey'=>$apikey,
            'razas'=>$razas,
            'mensaje'=>$html
        ));

      }

      public function clienteVisitaPostAction(Request $request){
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
                 return new JsonResponse(array(
                     0=>'No Enviado',
                     1=>'Mensaje no enviado usted es un robot...',

                 ));
             }

             $visita=new Visita();
             $visita->setNombre($nombre=$request->request->get('name'));
             $visita->setApellido($apellido=$request->request->get('apellido'));
             $visita->setEmail($email=$request->request->get('email'));
             $visita->setFecha(new \DateTime());
             $visita->setLocalidad($localidad=$request->request->get('localidad'));
             $visita->setProvincia($provincia=$request->request->get('provincia'));
             $visita->setPais($pais=$request->request->get('pais'));
             $visita->setOcupacion($request->request->get('ocupacion'));
             $visita->setEmpresa($empresa=$request->request->get('empresa'));
             $visita->setTelefono($telefono=$request->request->get('phone'));

             $razasstr='';
             if(count($request->request->get('razas'))>0)
                 $razasstr.=implode(", ", $request->request->get('razas'));
             $visita->setRazas($razasstr);

             $visita->setCalificacion($request->request->get('rating'));
             $visita->setSugerencias($request->request->get('sugerencias'));

             $file= $_FILES['imagen'];
             $helper=new MyHelper();
             $guid=$helper->GUID();
             $visita->setSugerencias($request->request->get('sugerencias'));
             $weppath= $this->get('kernel')->getRootDir() . '/../web/visitas/' . $guid . '/';
             $copiado=$helper->CopyFile($weppath,$file);

             $result='Registro en Mail_Chimp desactivado';
             if($copiado!=false) {
                 $visita->setArchivo($copiado);
                 $ema = $this->getDoctrine()->getManager();
                 //MailChimp

                 if($coordenadas = $ema->getRepository('gemaBundle:Configuracion')->find(1)->getRegisterMailChimp()==true){
                     $contantoNombre = $ema->getRepository('gemaBundle:Configuracion')->find(1)->getNombreVisita();
                     $keyContacto=$ema->getRepository('gemaBundle:Configuracion')->find(1)->getKeyVisita();
                     $postData = array(
                         "Email Address" => "$email",
                         "email_address" => "$email",
                         'status_if_new' => 'subscribed',
                         "status" => "subscribed",
                         'Last Name'=>$apellido,
                         'Interest'=>'Elija las razas de su interés',
                         'Subscribe'=>'Contacto WEB',
                         'Telefono'=>$telefono,
                         'Localidad'=>$localidad,
                         'Provincia'=>$provincia,
                         'Pais'=>$pais,
                         'MMERGE3'=>$empresa,
                         'MMERGE16'=>'Visita CIALE',



                         "merge_fields" => array(
                             "First Name"=> $nombre,
                             "Email Address"=>$email)
                     );

                     // Setup cURL
                     $url = 'https://us6.api.mailchimp.com/3.0/lists/'.$contantoNombre.'/members/';
                     $json_data = json_encode($postData);
                     $auth = base64_encode( 'user:'.$keyContacto );

                     $ch = curl_init();
                     curl_setopt($ch, CURLOPT_URL, $url);
                     curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
                         'Authorization: Basic '.$auth));
                     curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
                     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                     curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                     curl_setopt($ch, CURLOPT_POST, true);
                     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                     curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);

                     $result = curl_exec($ch);
                     // $result= json_encode ( $result,0 ,512 ) ;
                 }


                 $visita->setMailChimpResponse($result);

                 $ema->persist($visita);
                 $ema->flush();
                 //Correo
                 $message = \Swift_Message::newInstance()
                     ->setSubject('Visita a Alta Ciale');
                 $message->setContentType("text/html");
                 $message->setFrom('info@ciale.com');
                 $message ->setTo($request->request->get('email'));

                 $html= $ema->getRepository('gemaBundle:Singlehtml')->findOneBy(
                     array(
                         'nombre'=>'encuestavisita'
                     )
                 );
                 $message->setBody(
                     $html->getHtml()
                 );
                 $this->get('mailer')->send($message);
                 //
                 return new JsonResponse(array(
                     0=>'Enviado',
                     1=>'Vista Registrada!!!',

                 ));


             }

             else{

                 return new JsonResponse(array(
                     0=>'No Enviado',
                     1=>'No se ha podido copiar la foto',

                 ));

             }
         }
         catch(Swift_TransportException  $e){

             return new JsonResponse(array(
                 0=>'No Enviado',
                 1=>$e->getMessage(),

             ));
         }
         catch(Exception  $e){

             return new JsonResponse(array(
                 0=>'No Enviado',
                 1=>$e->getMessage(),

             ));
         }

      }


    public function ImagenDownloadAction($id){

        $ema = $this->getDoctrine()->getManager();
        $file=$ema->getRepository('gemaBundle:Visita')->find($id);
        $response = new BinaryFileResponse($file->getArchivo());
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT);

        return $response;
    }
     #endregion
}
