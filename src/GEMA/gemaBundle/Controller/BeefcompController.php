<?php

namespace GEMA\gemaBundle\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use GEMA\gemaBundle\Entity\Compra;
use GEMA\gemaBundle\Form\CompraType;
use Symfony\Component\HttpFoundation\JsonResponse;
use GEMA\gemaBundle\Helpers\MyHelper;
use GEMA\gemaBundle\Entity\Pedidocompra;

/**
 * Compra controller.
 *
 */
class BeefcompController extends Controller
{

    

    



    
 public function indexAction(Request $request){      
    $em = $this->getDoctrine()->getManager();
   
     $helper=new MyHelper();
     $apikey= $this->getParameter('apikey');
     
     $gife=$helper->directPic('genericfiles'.DIRECTORY_SEPARATOR,'paperplane.gif');
    
     $beefcompLogo = $helper->directPic('beefcomp1'.DIRECTORY_SEPARATOR,'beefcompLogo.jpg'); 
     $imgwasapflot=$helper->directPic('calostro'.DIRECTORY_SEPARATOR.'BOTONES'.DIRECTORY_SEPARATOR,
     'whatsapp.png',false);
     $banner = $helper->directPic('beefcomp1'.DIRECTORY_SEPARATOR,'banner.jpg'); 
     $concentra1 = $helper->directPic('beefcomp1'.DIRECTORY_SEPARATOR,'1concentra.jpg'); 
     $practicidad2 = $helper->directPic('beefcomp1'.DIRECTORY_SEPARATOR,'2practicidad.jpg'); 
     $flexible3 = $helper->directPic('beefcomp1'.DIRECTORY_SEPARATOR,'3Flexible.jpg'); 
     $potencia4 = $helper->directPic('beefcomp1'.DIRECTORY_SEPARATOR,'4potencia.jpg'); 
     $sub1 = $helper->directPic('beefcomp1'.DIRECTORY_SEPARATOR,'sub1.jpg'); 
     $sub2 = $helper->directPic('beefcomp1'.DIRECTORY_SEPARATOR,'sub2.jpg');     
     $videos = $em->getRepository('gemaBundle:Calostrovideos')->BeefcompOrderedbyDesc();
     $btnimg = $helper->directPic('beefcomp1'.DIRECTORY_SEPARATOR,'btn.jpg');     
     
     $servername= $_SERVER['SERVER_NAME'];
    
     return $this->render('gemaBundle:Page:beefcomp.html.twig', array(
      'apikey'=>$apikey,
      'gife'=>$gife,
       'beefcompLogo'=>$beefcompLogo,    
       'servername'=>$servername,
       'imgwasapflot'=>$imgwasapflot,
       'banner'=>$banner,
       'concentra1'=>$concentra1,
        'practicidad2'=>$practicidad2,
        'flexible3'=>$flexible3,
        'potencia4'=>$potencia4,
        'sub1'=>$sub1,
        'sub2'=>$sub2,
        'videos'=>$videos,
        'btnimg'=>$btnimg



    ));
  } 

  public function sendAction(Request $request)  
  {
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

    $em = $this->getDoctrine()->getManager();
    $nombre=$request->request->get('nombre');
    $apellido=$request->request->get('apellido');   
    $localidad=$request->request->get('localidad'); 
    $provincia=$request->request->get('provincia'); 
    $pais=$request->request->get('pais');  

    $email = strtolower($request->request->get('email'));
    $telefono = $request->request->get('telefono'); 
    $tamanorodeo = $request->request->get('tamanorodeo'); 
    $tipoexplotacion = $request->request->get('tipoexplotacion'); 
    $idrodeo = $request->request->get('idrodeo'); 

     //Mail
     $message = \Swift_Message::newInstance()
     ->setSubject('Datos del formulario BEEFCOMP');
    $message->setFrom('info@ciale.com');
    $message->setContentType("text/html");   
 

    $body='<strong>Nombre:</strong><span> '.$nombre."</span><br>";
    $body.='<strong>Apellido:</strong><span> '.$apellido."</span><br>";
    $body.='<strong>Telefono:</strong><span> '.$telefono."</span><br>";
    $body.='<strong>Email:</strong><span> '.$email."</span><br>";
    $body.='<strong>Localidad:</strong><span> '.$localidad."</span><br>";
    $body.='<strong>Provincia:</strong><span> '.$provincia."</span><br>";
    $body.='<strong>Pais:</strong><span> '.$pais."</span><br>";
    $body.='<strong>Tamaño del Rodeo:</strong><span> '.$tamanorodeo."</span><br>";
    $body.='<strong>Tipo de Explotación:</strong><span> '.$tipoexplotacion."</span><br>";
    $body.='<strong>Identificación del Rodeo:</strong><span> '.$idrodeo."</span><br>";
 
    $body.='<div><span>CIALE Alta</span></div><br>';
    $body.="<br>";
    $message ->setTo('Matias.Machain@altagenetics.com');
	$message->setCc('joaquin.alvarez@altagenetics.com');
    $message->setBody(
     $body
    );
 
     $this->get('mailer')->send($message);
     return new JsonResponse(array(
      0=>'Enviado',
      1=>'Datos enviados con éxito!',
      2=>null

  ));


  }
  catch(\Exception  $e)
  {
    return new JsonResponse(array(
        0=>'No Enviado',
        1=>$e->getMessage(),
   
    ));
  }

  }
   
    
    

    
}
