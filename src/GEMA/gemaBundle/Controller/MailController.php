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
            $correo->setRazas($razasstr);
            $correo->setConsulta($consulta);
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