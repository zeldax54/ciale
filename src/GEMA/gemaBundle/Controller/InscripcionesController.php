<?php

namespace GEMA\gemaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use GEMA\gemaBundle\Helpers\MyHelper;
use GEMA\gemaBundle\Entity\Area;
use GEMA\gemaBundle\Form\AreaType;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Area controller.
 *
 */
class InscripcionesController extends Controller
{

    
   
    public function inscripcionesindexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
       $hav = $em->getRepository('gemaBundle:Configuracion')->find(1)->getHabilitarinteres();
     if($hav==false){
     print('NO habilitado');die();
     }
        $helper=new MyHelper();
        $encabezadoimg = $helper->directPic('genericfiles'.DIRECTORY_SEPARATOR,'encabezado.jpg');
        $apikey= $this->getParameter('apikey');
        $gife=$helper->directPic('genericfiles'.DIRECTORY_SEPARATOR,'paperplane.gif');
                return $this->render('gemaBundle:Page:inscripciones.html.twig', array(
                    'apikey'=>$apikey,
                     'gife'=>$gife,
                     'encabezadoimg'=>$encabezadoimg
            
        ));
    }

   public function inscripcionessendAction(Request $request){

    try
    {
      //Captcha
    $recaptcha = $request->request->get('g-recaptcha-response');
    print_r($recaptcha);die();
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
            1=>'Inscripcion no enviado usted es un robot...',

        ));
    }
    ////////
    $em = $this->getDoctrine()->getManager();
    $nombre=$request->request->get('nombre');
    $apellido=$request->request->get('apellido');   
    $localidad=$request->request->get('localidad'); 
    $provincia=$request->request->get('provincia'); 
    $pais=$request->request->get('pais');  

    $email= strtolower($request->request->get('email'));
    $interes=$request->request->get('interes');

    // MailCHimp
    $result='Configuracion Mail_Chimp desactivada';
    if( $em->getRepository('gemaBundle:Configuracion')->find(1)->getRegisterMailChimp()==true){        
        $eventomarker = $em->getRepository('gemaBundle:Configuracion')->find(1)->getEventomarker();         
        $postData = array(              
            "email_address" => "$email",
            'status_if_new' => 'subscribed',
            "status" => "subscribed",                
             'merge_fields'  => [
                "FNAME"=> $nombre,
                'LNAME'=>$apellido,                
                'Subscribe'=>'Contacto WEB',
                'MMERGE13'=>$interes,
                'MMERGE7'=>$localidad,            
                'MMERGE16'=>$eventomarker,
                'MMERGE6'=>$provincia,
                'MMERGE8'=>$pais,
             ]
            );
      
        $result=$this->sendCurl($em,$postData);
        $casted=json_decode($result, true);
         if($casted['status']==400){
             //try updating
             $result=$this->sendCurl($em,$postData,true,$email);
              $casted=json_decode($result, true);
              if($casted['status']==400 || $casted['status']==405)
               return new JsonResponse(array(
                0=>'No Enviado',
                1=>$casted['detail'],
           
            ));
            
         }
    }
    //Mail
    $message = \Swift_Message::newInstance()
    ->setSubject('Gracias por inscribirte!');
$message->setFrom('info@ciale.com');
$message->setContentType("text/html");   

$body='<img style="witdh:100%" src="https://www.ciale.com/genericfiles/encabezado.jpg"><br><br>';
$body.='<span>Hola </span><strong> '.$nombre."!</strong><br>";
$body.='<div><p>Agradecemos tu inscripción a nuestra próxima edición del CIALE TV el miércoles 9 de diciembre a las 19.30 hs. Podrás acceder a la transmisión desde el siguiente link <a href="https://youtu.be/03vfJi7VH-M"> https://youtu.be/03vfJi7VH-M</a>,</p></div>';
$body.='<div><span>Muchas gracias!</span></div>';
$body.='<div><span>CIALE Alta</span></div><br>';
$body.="<br>";
$message ->setTo($email);
$message->setBody(
    $body
);

$this->get('mailer')->send($message);
    //
    return new JsonResponse(array(
        0=>'Enviado',
        1=>'Inscripción exitosa!',
        2=>$result

    ));

    }
    catch(\Exception  $e){
     return new JsonResponse(array(
         0=>'No Enviado',
         1=>$e->getMessage(),
    
     ));
   }
}

  private function sendCurl($em,$postdata,$isupdate=false,$email=''){
    $eventomarker = $em->getRepository('gemaBundle:Configuracion')->find(1)->getEventomarker();   
    $contantoNombre = $em->getRepository('gemaBundle:Configuracion')->find(1)->getNombreVisita();
    $keyContacto=$em->getRepository('gemaBundle:Configuracion')->find(1)->getKeyVisita();
    
    // Setup cURL
    $emailHash = md5($email);
    $url = 'https://us6.api.mailchimp.com/3.0/lists/'.$contantoNombre.'/members/';     
    $json_data = json_encode($postdata);
    $auth = base64_encode( 'user:'.$keyContacto );

      $ch = curl_init();
      if($isupdate==true){
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        $url = 'https://us6.api.mailchimp.com/3.0/lists/'.$contantoNombre.'/members/'.$emailHash;
      }
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
      return $result;     

  }
 
}