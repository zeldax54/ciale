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
class CalostroController extends Controller
{

    
    public function indexAction(Request $request)
    {
        $helper=new MyHelper();
        $em = $this->getDoctrine()->getManager();
        $logo=$helper->directPic('calostro'.DIRECTORY_SEPARATOR.'LOGO'.DIRECTORY_SEPARATOR,
        'Logo-Calostro.jpg',false);
        $calostro100 = $helper->directPic('calostro'.DIRECTORY_SEPARATOR.'LOGO'.DIRECTORY_SEPARATOR,
        'calostro100porc.jpg',false);
        $queescalostro = $helper->directPic('calostro'.DIRECTORY_SEPARATOR.'FOTOS PRINCIPALES'.DIRECTORY_SEPARATOR,
        'foto1.jpg',false);
        $comoprepararlo = $helper->directPic('calostro'.DIRECTORY_SEPARATOR.'FOTOS PRINCIPALES'.DIRECTORY_SEPARATOR,
        'foto2.jpg',false);
        $preguntasfec = $helper->directPic('calostro'.DIRECTORY_SEPARATOR.'FOTOS PRINCIPALES'.DIRECTORY_SEPARATOR,
        'foto3.jpg',false);
        $ventajas1 = $helper->directPic('calostro'.DIRECTORY_SEPARATOR.'INFOGRAFIA'.DIRECTORY_SEPARATOR,
        'paquetecalostro.jpg',false);
        $calostroboliviano = $helper->directPic('calostro'.DIRECTORY_SEPARATOR.'INFOGRAFIA'.DIRECTORY_SEPARATOR,
        'calostroboliviano.jpg',false);
        $seguroyefectivo = $helper->directPic('calostro'.DIRECTORY_SEPARATOR.'INFOGRAFIA'.DIRECTORY_SEPARATOR,
        'seguroyefectivo.jpg',false);
        $altatransferencia = $helper->directPic('calostro'.DIRECTORY_SEPARATOR.'INFOGRAFIA'.DIRECTORY_SEPARATOR,
        'altatransferencia.jpg',false);
        $altoengrasas = $helper->directPic('calostro'.DIRECTORY_SEPARATOR.'INFOGRAFIA'.DIRECTORY_SEPARATOR,
        'altoengrasas.jpg',false);
        $polvofacil = $helper->directPic('calostro'.DIRECTORY_SEPARATOR.'INFOGRAFIA'.DIRECTORY_SEPARATOR,
        'polvofacil.jpg',false);
        $puedealmacenarse = $helper->directPic('calostro'.DIRECTORY_SEPARATOR.'INFOGRAFIA'.DIRECTORY_SEPARATOR,
        'puedealmacenarse.jpg',false);
        $cuadrodepasos = $helper->directPic('calostro'.DIRECTORY_SEPARATOR.'INFOGRAFIA'.DIRECTORY_SEPARATOR,
        'cuadrodepasos.jpg',false);
        $cuadropasosdos = $helper->directPic('calostro'.DIRECTORY_SEPARATOR.'INFOGRAFIA'.DIRECTORY_SEPARATOR,
        'cuadropasosdos.jpg',false);
        $btndescargarinstructivos = $helper->directPic('calostro'.DIRECTORY_SEPARATOR.'BOTONES'.DIRECTORY_SEPARATOR,
        'btndescargarinstructivos.jpg',false);
        $celulares = $helper->directPic('calostro'.DIRECTORY_SEPARATOR.'APP'.DIRECTORY_SEPARATOR,
        'celular.jpg',false);
        $apple = $helper->directPic('calostro'.DIRECTORY_SEPARATOR.'APP'.DIRECTORY_SEPARATOR,
        'apple.jpg',false);
        $android = $helper->directPic('calostro'.DIRECTORY_SEPARATOR.'APP'.DIRECTORY_SEPARATOR,
        'android.jpg',false);
        $videos = $em->getRepository('gemaBundle:Calostrovideos')->findAll();
        $noticias =  $em->getRepository('gemaBundle:Noticia')->findBy([
            'calostro' => 1,            
        ]);      
        foreach($noticias as $noticia)
         $noticia->portada= $helper->randomPic('noticia'.DIRECTORY_SEPARATOR.$noticia->getGuid().DIRECTORY_SEPARATOR);
        
         $imaghabla = $helper->directPic('calostro'.DIRECTORY_SEPARATOR.'CONTACTO'.DIRECTORY_SEPARATOR,
         'habla.jpg',false);
         $chatonline = $helper->directPic('calostro'.DIRECTORY_SEPARATOR.'BOTONES'.DIRECTORY_SEPARATOR,
         'chatonline.jpg',false);       
         $instructivo = 'calostro\INSTRUCTIVO\instructivo.pdf';
         $gife=$helper->directPic('genericfiles'.DIRECTORY_SEPARATOR,'paperplane.gif');
         $helpgif=$helper->directPic('genericfiles'.DIRECTORY_SEPARATOR,'helpgif.gif');
        return $this->render('gemaBundle:Page:calostro.html.twig', array(
           'logo' => $logo,
           'calostro100' => $calostro100,
           'queescalostro' => $queescalostro,
           'comoprepararlo' => $comoprepararlo,
           'preguntasfec' => $preguntasfec,
           'ventajas1' => $ventajas1,
           'calostroboliviano' => $calostroboliviano,
           'seguroyefectivo' => $seguroyefectivo,
           'altatransferencia' => $altatransferencia,
           'altoengrasas' => $altoengrasas,
           'polvofacil' => $polvofacil,
           'puedealmacenarse' => $puedealmacenarse,
           'cuadrodepasos' => $cuadrodepasos,
           'cuadropasosdos' => $cuadropasosdos,
           'btndescargarinstructivos' => $btndescargarinstructivos,
           'celulares' => $celulares,
           'apple' => $apple,
           'android' => $android,
           'videos' => $videos,
           'noticias'=>$noticias,
           'imaghabla'=>$imaghabla,
           'chatonline'=>$chatonline,
           'instructivo'=>$instructivo,
           'gife'=>$gife,
           'helpgif'=>$helpgif
         ));
    
    }


    public function calostromsgAction(Request $request){

        $nombre=str_replace('"','',$request->request->get('nombre'));  
        $apellido=str_replace('"','',$request->request->get('apellido'));  
        $email= str_replace('"','',$request->request->get('email'));  
        $telefono=str_replace('"','',$request->request->get('telefono'));  
        $consulta=str_replace('"','',$request->request->get('consulta'));  
        $message = \Swift_Message::newInstance()
        ->setSubject('Mensaje calostro');
    $message->setFrom('info@ciale.com');
    $message->setContentType("text/html");   
    $body='';
    $body.='<strong>Nombre:</strong> '.$nombre."<br>";
    $body.='<strong>Apellido:</strong> '.$apellido."<br>";
    $body.='<strong>Email:</strong> '.$email."<br>";    
    $body.='<strong>Teléfono:</strong> '.$telefono."<br>";
    $body.='<strong>Consulta:</strong> '.$consulta."<br>";
    $body.="<br>";
    $to=array(
        0 => $email,
        1 =>'Leonardo.Mian@sccl.com'     
    );
    $message ->setTo($to);
    $message->setBody(
        $body
    );
    $this->get('mailer')->send($message);
    return new JsonResponse(array(
        0=>'Enviado',
        1=>'',
        2=>'None'
    ));
    }
   
    
    

    
}
