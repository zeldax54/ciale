<?php
/**
 * Created by PhpStorm.
 * User: AK0
 * Date: 26/07/2017
 * Time: 1:05
 */
namespace GEMA\gemaBundle\Controller;
use GEMA\gemaBundle\Entity\Correo;
use Swift_TransportException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use GEMA\gemaBundle\Helpers\MyHelper;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
class MailController extends Controller
{


    public function contactindexAction(){

        $em = $this->getDoctrine()->getManager();
        $razas = $em->getRepository('gemaBundle:Raza')->findAll();
        $datosof=$em->getRepository('gemaBundle:DatosOficina')->find(1);
        $helper=new MyHelper();
        $gife=$helper->directPic('genericfiles'.DIRECTORY_SEPARATOR,'paperplane.gif');
        $coordenadas = $em->getRepository('gemaBundle:Configuracion')->find(1)->getCoordenadas();
        $coordenadaslab = $em->getRepository('gemaBundle:Configuracion')->find(1)->getCoordenadaslab();
        $apikey= $this->getParameter('apikey');
        return $this->render('gemaBundle:Page:contacto.html.twig', array(
           'razas'=>$razas,
            'datosoficina'=>$datosof,
            'gife'=>$gife,
             'coordenadas'=>$coordenadas,
            'coordenadaslab'=>$coordenadaslab,
            'apikey'=>$apikey
        ));
    }

    public function solicitudIndexAction(){

        $em = $this->getDoctrine()->getManager();
        $razas = $em->getRepository('gemaBundle:Raza')->findAll();
        $datosof=$em->getRepository('gemaBundle:DatosOficina')->find(1);
        $helper=new MyHelper();
        $gife=$helper->directPic('genericfiles'.DIRECTORY_SEPARATOR,'paperplane.gif');
        $coordenadas = $em->getRepository('gemaBundle:Configuracion')->find(1)->getCoordenadas();
        $coordenadaslab = $em->getRepository('gemaBundle:Configuracion')->find(1)->getCoordenadaslab();
        $apikey= $this->getParameter('apikey');
        return $this->render('gemaBundle:Page:solicitud.html.twig', array(
           'razas'=>$razas,
            'datosoficina'=>$datosof,
            'gife'=>$gife,
             'coordenadas'=>$coordenadas,
            'coordenadaslab'=>$coordenadaslab,
            'apikey'=>$apikey
        ));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public  function sendmailAction(Request $request){

        try
        {
            //Captcha
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
            //

            $em = $this->getDoctrine()->getManager();
            $datosof=$em->getRepository('gemaBundle:DatosOficina')->find(1);
            $direcciones=explode(';',$datosof->getEmail());

            $message = \Swift_Message::newInstance()
                ->setSubject('Contanto de Alta Ciale');
            $message->setFrom('info@ciale.com');

            $message->setContentType("text/html");
            $nombre=$request->request->get('nombre');
            $apellido=$request->request->get('apellido');
            $direccion=$request->request->get('direccion');
            $localidad=$request->request->get('localidad');
            $provincia=$request->request->get('provincia');
            $codigopstal=$request->request->get('codigopostal');
            $email=$request->request->get('email');
            $telefono=$request->request->get('telefono');
            $empresa=$request->request->get('empresa');
            $razas=$request->request->get('razas');
            $razasstr='';
            if(count($razas)>0)
                $razasstr.=implode(", ", $razas);
            $consulta=$request->request->get('consulta');
            $pais=$request->request->get('pais');
            $body='';
            $body.='<strong>Nombre:</strong> '.$nombre."<br>";
            $body.='<strong>Apellido:</strong> '.$apellido."<br>";
            $body.='<strong>Direccion:</strong> '.$direccion."<br>";
            $body.='<strong>Localidad:</strong> '.$localidad."<br>";
            $body.='<strong>Provincia:</strong> '.$provincia."<br>";
            $body.='<strong>Pais:</strong> '.$pais."<br>";
            $body.='<strong>Codigo Postal:</strong> '.$codigopstal."<br>";
            $body.='<strong>Email:</strong> '.$email."<br>";
            $body.='<strong>Teléfono:</strong> '.$telefono."<br>";
            $body.='<strong>Empresa:</strong> '.$empresa."<br>";
            $body.='<strong>Razas:</strong> '.$razasstr."<br>";
            $body.='<strong>Consulta:</strong> '.$consulta."<br>";
            $to=array(
                0 =>$email,
            );
            $enviarmail = $em->getRepository('gemaBundle:Configuracion')->find(1)->getEnviarmaildestinos();
            if($enviarmail==true)
            {

                foreach($direcciones as $d)
                    $to[]=$d;
            }
            $message ->setTo($to);
            $message->setBody(
                $body
            );
//
            $this->get('mailer')->send($message);




            //MailChimp
            $result='Configuracion Mail_Chimp desactivada';
            if($coordenadas = $em->getRepository('gemaBundle:Configuracion')->find(1)->getRegisterMailChimp()==true){
                $contantoNombre = $em->getRepository('gemaBundle:Configuracion')->find(1)->getNombreContacto();
                $keyContacto=$em->getRepository('gemaBundle:Configuracion')->find(1)->getKeyContacto();
                $postData = array(
                    "Email Address" => "$email",
                    "email_address" => "$email",
                    'status_if_new' => 'subscribed',
                    "status" => "subscribed",
                    'Last Name'=>$apellido,
                    'Interest'=>'Elija las razas de su interés',
                    'Subscribe'=>'Contacto WEB',
                    'Telefono'=>$telefono,
                    'Direccion'=>$direccion,
                    'Localidad'=>$localidad,
                    'Provincia'=>$provincia,
                    'Pais'=>$pais,
                    'Compania'=>$empresa,
                    'Cod-postal'=>$codigopstal,


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


           //  print_r($result);die();


            $correo=new Correo();
            $correo->setNombre($nombre);
            $correo->setApellido($apellido);
            $correo->setDireccion($direccion);
            $correo->setLocalidad($localidad);
            $correo->setProvincia($provincia);
            $correo->setPais($pais);
            $correo->setCodigopostal($codigopstal);
            $correo->setEmail($email);
            $correo->setTelefono($telefono);
            $correo->setEmpresa($empresa);
            $correo->setRazas($razasstr);
            $correo->setConsulta($consulta);
            $correo->setMailChimpResponse($result);
            date_default_timezone_set('America/Argentina/Buenos_Aires');
            $date = date('d-m-Y H:i:s');
            $correo->setFechahora($date);
            $ema = $this->getDoctrine()->getManager();
            $ema->persist($correo);
            $ema->flush();



            return new JsonResponse(array(
                0=>'Enviado',
                1=>'Mensaje enviado con exito! Se ha copiado el mensaje a su dirección de correo.',

            ));
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

     /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function sendSolicitudMailAction(Request $request){
       //MailChimp
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
        //

        $nombre=$request->request->get('nombre');
        $apellido=$request->request->get('apellido');   
        $localidad=$request->request->get('localidad');
        $provincia=$request->request->get('provincia');       
        $email= strtolower($request->request->get('email'));
        $telefono=$request->request->get('telefono');   
        $pais=$request->request->get('pais');
        $em = $this->getDoctrine()->getManager();
         
        //Email
        $message = \Swift_Message::newInstance()
        ->setSubject('Formulario de solicitud');
        $message->setFrom('info@ciale.com');
    $message->setContentType("text/html");   
    $body='';
    $body.='<strong>Nombre:</strong> '.$nombre."<br>";
    $body.='<strong>Apellido:</strong> '.$apellido."<br>";
    $body.='<strong>Direccion:</strong> '.$direccion."<br>";
    $body.='<strong>Localidad:</strong> '.$localidad."<br>";
    $body.='<strong>Provincia:</strong> '.$provincia."<br>";
    $body.='<strong>Pais:</strong> '.$pais."<br>";
    $body.='<strong>Email:</strong> '.$email."<br>";
    $body.='<strong>Teléfono:</strong> '.$telefono."<br>";   
    $to=array(
        0 =>$email,
    );
    $enviarmail = $em->getRepository('gemaBundle:Configuracion')->find(1)->getEnviarmaildestinos();
    $datosof=$em->getRepository('gemaBundle:DatosOficina')->find(1);
    $direcciones=explode(';',$datosof->getEmail());
    if($enviarmail==true)
    {

        foreach($direcciones as $d)
            $to[]=$d;
    }
    $message ->setTo($to);
    $message->setBody(
        $body
    );

    $this->get('mailer')->send($message);

        // MailCHimp
        $result='Configuracion Mail_Chimp desactivada';
        if( $em->getRepository('gemaBundle:Configuracion')->find(1)->getRegisterMailChimp()==true){
            $contantoNombre = $em->getRepository('gemaBundle:Configuracion')->find(1)->getNombreContacto();
            $keyContacto=$em->getRepository('gemaBundle:Configuracion')->find(1)->getKeyContacto();         

            $postData = array(              
                "email_address" => "$email",
                'status_if_new' => 'subscribed',
                "status" => "subscribed",                
                 'merge_fields'  => [
                    "FNAME"=> $nombre,
                    'LNAME'=>$apellido,
                    'MMERGE8'=>'Formulario de Solicitud',
                    'Subscribe'=>'Contacto WEB',
                    'MMERGE12'=>$telefono,
                    'MMERGE4'=>$localidad,
                    'MMERGE5'=>$provincia,
                    'MMERGE10'=>$pais,
                    'MMERGE9'=>'',
                    'MMERGE3'=>'',
                    'MMERGE16'=>'Formulario de solicitud',
                 ]
                );



              // Setup cURL
              $url = 'https://us6.api.mailchimp.com/3.0/lists/a03f2c9901/members/';
              $json_data = json_encode($postData);
              $auth = base64_encode( 'user:41bcb93a80723f3ba69ddc3b75af6005-us6');
 
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
        }
        return new JsonResponse(array(
            0=>'Enviado',
            1=>'Mensaje enviado con exito! Se ha copiado el mensaje a su dirección de correo.',
            2=>$result

        ));
       
       }
       catch(\Swift_TransportException  $e){
        return new JsonResponse(array(
            0=>'No Enviado',
            1=>$e->getMessage(),

        ));
      }
      catch (\Swift_RfcComplianceException $e){
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

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function sellermailAction(Request $request){
        try{


            $message = \Swift_Message::newInstance()
                ->setSubject('Contanto de Alta Ciale');
            $message->setFrom('info@ciale.com');

            $em = $this->getDoctrine()->getManager();
            $datosof=$em->getRepository('gemaBundle:DatosOficina')->find(1);
            $direcciones=explode(';',$datosof->getEmail());

            $message->setContentType("text/html");
            $nombre=$request->request->get('nombre');
            $apellido=$request->request->get('apellido');
            $direccion=$request->request->get('direccion');
            $localidad=$request->request->get('localidad');
            $provincia=$request->request->get('provincia');
            $codigopstal=$request->request->get('codigopostal');
            $email=$request->request->get('email');
            $telefono=$request->request->get('telefono');
            $empresa=$request->request->get('empresa');
            $consulta=$request->request->get('consulta');
            $destino=$request->request->get('destino');
            $body='';
            $body.='<strong>Nombre:</strong> '.$nombre."<br>";
            $body.='<strong>Apellido:</strong> '.$apellido."<br>";
            $body.='<strong>Direccion:</strong> '.$direccion."<br>";
            $body.='<strong>Localidad:</strong> '.$localidad."<br>";
            $body.='<strong>Provincia:</strong> '.$provincia."<br>";
            $body.='<strong>Codigo Postal:</strong> '.$codigopstal."<br>";
            $body.='<strong>Email:</strong> '.$email."<br>";
            $body.='<strong>Teléfono:</strong> '.$telefono."<br>";
            $body.='<strong>Empresa:</strong> '.$empresa."<br>";
            $body.='<strong>Consulta:</strong> '.$consulta."<br>";
            $to=explode(';',$destino);
            $to[]=$email;
            $enviarmail = $em->getRepository('gemaBundle:Configuracion')->find(1)->getEnviarmaildestinos();
            if($enviarmail==true)
            {
                foreach($direcciones as $d){
                    $to[]=$d;
                }
            }
            $message ->setTo($to);
            $message->setBody(
                $body
            );
//
            $this->get('mailer')->send($message);
            $correo=new Correo();
            $correo->setNombre($nombre);
            $correo->setApellido($apellido);
            $correo->setDireccion($direccion);
            $correo->setLocalidad($localidad);
            $correo->setProvincia($provincia);
            $correo->setCodigopostal($codigopstal);
            $correo->setEmail($email);
            $correo->setTelefono($telefono);
            $correo->setTelefono($telefono);
            $correo->setEmpresa($empresa);
            $correo->setConsulta($consulta);
            date_default_timezone_set('America/Argentina/Buenos_Aires');
            $date = date('d-m-Y H:i:s');
            $correo->setFechahora($date);
            $ema = $this->getDoctrine()->getManager();
            $ema->persist($correo);
            $ema->flush();

            return new JsonResponse(array(
                0=>'Enviado',
                1=>'Mensaje enviado con exito!.',

            ));
        }
        catch(Swift_TransportException  $e){

            return new JsonResponse(array(
                0=>'No Enviado',
                1=>$e->getMessage(),

            ));
        }

        catch (\Swift_RfcComplianceException $e){
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

}