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
class CompraController extends Controller
{

    /**
     * Lists all Compra entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = array();
        if ($request->isXmlHttpRequest()) {
            $repo = $em->getRepository('gemaBundle:Compra');
            $qb = $repo->filtrar($request);
            $result = $this->get("gema.utiles")->paginar($request->get("current"), $request->get("rowCount"), $qb->getQuery());
            return new JsonResponse($result);
        } else {
            $entities = $em->getRepository('gemaBundle:Compra')->findAll();
        }
        $accion = 'Listar Expedientes de Compra';
        $this->get("gema.utiles")->traza($accion);
        
        

        return $this->render('gemaBundle:Compra:index.html.twig', array(
                    'entities' => $entities,
        ));
    }
    /**
     * Creates a new Compra entity.
     *
     */
    public function createAction(Request $request)
    {
    
        $entity = new Compra();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $accion = '';
            $this->get("gema.utiles")->traza($accion);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('compra_show', array('id' => $entity->getId())));
        }



    
       return $this->render('gemaBundle:Compra:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a Compra entity.
    *
    * @param Compra $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Compra $entity)
    {
        $form = $this->createForm(new CompraType(), $entity, array(
            'action' => $this->generateUrl('compra_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Crear'));

        return $form;
    }

    /**
     * Displays a form to create a new Compra entity.
     *
     */
    public function newAction()
    {
        $entity = new Compra();
        $form   = $this->createCreateForm($entity);

    
        return $this->render('gemaBundle:Compra:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Compra entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:Compra')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Compra entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $accion = ' ';
        $this->get("gema.utiles")->traza($accion);

        return $this->render('gemaBundle:Compra:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Compra entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:Compra')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Compra entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('gemaBundle:Compra:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Compra entity.
    *
    * @param Compra $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Compra $entity)
    {
        $form = $this->createForm(new CompraType(), $entity, array(
            'action' => $this->generateUrl('compra_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar'));

        return $form;
    }
/**
    * Edits an existing Compra entity.
*
    */
    public function updateAction(Request $request, $id)
{
    $em = $this->getDoctrine()->getManager();

    $entity = $em->getRepository('gemaBundle:Compra')->find($id);

    if (!$entity) {
    throw $this->createNotFoundException('Unable to find Compra entity.');
    }

    $deleteForm = $this->createDeleteForm($id);
    $editForm = $this->createEditForm($entity);
    $editForm->handleRequest($request);
    $accion = ' ';
    $this->get("gema.utiles")->traza($accion);
    $em->flush();

    
 

    
    return $this->redirect($this->generateUrl('compra_edit', array('id' => $id)));
  
}
    /**
     * Deletes a Compra entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('gemaBundle:Compra')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Compra entity.');
            }
            
        $accion = ' ';
        $this->get("gema.utiles")->traza($accion);
            $em->remove($entity);
            $em->flush();

        return $this->redirect($this->generateUrl('compra'));
    }

    /**
     * Creates a form to delete a Compra entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('compra_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Eliminar'))
            ->getForm()
        ;
    }

    public function comprasindexAction(){      
      $em = $this->getDoctrine()->getManager();
      $hav = $em->getRepository('gemaBundle:Configuracion')->find(1)->getActivarCompras();
      if($hav==false){
        print('NO habilitado');die();
       }
       $helper=new MyHelper();
       $apikey= $this->getParameter('apikey');
       $gife=$helper->directPic('genericfiles'.DIRECTORY_SEPARATOR,'paperplane.gif');
       $helpgif=$helper->directPic('genericfiles'.DIRECTORY_SEPARATOR,'helpgif.gif');

       $vendedores= $em->getRepository('gemaBundle:VendedorCompra')->findBy([
        'deshabilitado' => false,
        
    ]);     
       $pedisosbase = $em->getRepository('gemaBundle:Pedidobase')->findAll();
       $serializer = $this->container->get('jms_serializer');       
     
       $baserializada = $serializer->serialize($pedisosbase, 'json');
       $baserializada=json_decode($baserializada,true);
     
       $metodoPago = $em->getRepository('gemaBundle:Metodopago')->findAll();
       $metodoPago = $serializer->serialize($metodoPago, 'json');
       $metodoPago=json_decode($metodoPago,true);
     
       $ruletas = $em->getRepository('gemaBundle:Ruleta')->findAll();
       $ruletas = $serializer->serialize($ruletas, 'json');
       $ruletas=json_decode($ruletas,true);       
      
       $wheelaback = $helper->randomPic('mediainpage'.DIRECTORY_SEPARATOR.'wheel'.DIRECTORY_SEPARATOR);    
       $servername= $_SERVER['SERVER_NAME'];
       return $this->render('gemaBundle:Page:compras.html.twig', array(
        'apikey'=>$apikey,
        'gife'=>$gife,
         'vendedores'=>$vendedores,
         'pedisosbase'=>$pedisosbase,
         'baserializada'=>$baserializada,
         'metodopago'=>$metodoPago,
         'ruletas' => $ruletas,
         'wheelaback'=>$wheelaback,
         'servername'=>$servername,
         'helpgif'=>$helpgif,


      ));
    }

    public function comprasDoAction(Request $request){
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
                1=>'Compra no enviado usted es un robot...',

            ));
        }
        ////////
        $em = $this->getDoctrine()->getManager();
        $nombre=$request->request->get('nombre');
        $apellido=$request->request->get('apellido');   
        $empresa=$request->request->get('empresa');   
        $localidad=$request->request->get('localidad');
        $provincia=$request->request->get('provincia');       
        $email= strtolower($request->request->get('email'));
        $telefono=$request->request->get('telefono');   
        $pais=$request->request->get('pais');
        $vendedor = $request->request->get('vendedor');
        $metodopagoid = $request->request->get('metodopagoid');
        $pedidocompra = json_decode($request->request->get('pedidocompra'),true);
        $ruletaid = $request->request->get('ruletaid');
        $premiotext = $request->request->get('premiotext');
        $totlaahorro = $request->request->get('totlaahorro');
        $descuento = $request->request->get('descuento');
        if(is_numeric($descuento) == false)
           $descuento = 0;

       //Create Objects
       $compra=new Compra();
       $compra->setNombre($nombre);
       $compra->setApellido($apellido);
       $compra->setEmpresa($empresa);
       $compra->setLocalidad($empresa);
       $compra->setProvincia($provincia);
       $compra->setTelefono($telefono);
       $compra->setEmail($email);
       $compra->setVendedor($em->getRepository('gemaBundle:VendedorCompra')->find($vendedor));
       $compra->setFecha(new \DateTime());
       $compra->setMetodopago($em->getRepository('gemaBundle:Metodopago')->find($metodopagoid));
       if($ruletaid!=-1){
           $premiobd = $em->getRepository('gemaBundle:Premio')->findOneBy([
            'ruleta' => $ruletaid,
            'nombre' => $premiotext,
        ]);     
        $compra->setPremio($premiobd);
       }

       foreach($pedidocompra as $pedido){
           if (isset($pedido) && $pedido!=null){              
               $pC=new Pedidocompra();
               $pC->setCantidad($pedido['cantidad']);
               $pedidobase=$em->getRepository('gemaBundle:Pedidobase')->find($pedido['pedidobaseid']);
               $pC->setPedidobase($pedidobase);
               $pC->setPrecio($pedidobase->getPreciopromo());
               $pC->setSubtotal($pedidobase->getPreciopromo() * $pedido['cantidad']);
               $pC->setCompra($compra);
               $compra->addPedido($pC);               
           }
       }

       $compra->setDescuento($descuento);
       $em->persist($compra);
       $em->flush();
       $idcompra = $compra->getId();
      
       $compraReloades = $em->getRepository('gemaBundle:Compra')->find($idcompra);
       //Mail
       $message = \Swift_Message::newInstance()
        ->setSubject('Resumen de Compra');
    $message->setFrom('info@ciale.com');
    $message->setContentType("text/html");   
    $body='';
    $body.='<strong>Nombre:</strong> '.$nombre."<br>";
    $body.='<strong>Apellido:</strong> '.$apellido."<br>";
    $body.='<strong>Email:</strong> '.$email."<br>";
    $body.='<strong>Empresa:</strong> '.$empresa."<br>";
    $body.='<strong>Localidad:</strong> '.$localidad."<br>";
    $body.='<strong>Provincia:</strong> '.$provincia."<br>";
    $body.='<strong>Pais:</strong> '.$pais."<br>";   
    $body.='<strong>Teléfono:</strong> '.$telefono."<br>";
    $body.='<strong>Pedido:</strong><br>';
    $totalPagado=0;
    $body.='--------------------------------------------------------';
    $body.="<br>";
    foreach($compraReloades->getPedidos() as $p){       
      
        $body.='<strong>Toro:</strong> '.$p->getPedidobase()->getToro()->getApodo()."<br>";
        $body.='<strong>Cantidad:</strong> '.$p->getCantidad()."<br>";
        $body.='<strong>Precio Lista:</strong> '.$p->getPedidobase()->getPreciolista()."<br>";
        $body.='<strong>PrecioPromo:</strong> '.$p->getPedidobase()->getPreciopromo()."<br>";
        $body.='<strong>Total:</strong> '.$p->getSubtotal()."<br>";
        $body.='--------------------------------------------------------';
        $body.="<br>";
        $totalPagado+=$p->getSubtotal();

    }
   
    $body.='<strong>Forma de pago:</strong> '.$compraReloades->getMetodopago()->getTipo()."<br>";
    $body.='<strong>Forma de pago % descuento:</strong> '.$compraReloades->getMetodopago()->getDescuentoporc()."%<br>";
    $body.='<strong>Forma de pago descripcion:</strong> '.$compraReloades->getMetodopago()->getDescrip1()."<br>";
    $body.='<strong>Forma de pago descripcion:</strong> '.$compraReloades->getMetodopago()->getDescrip2()."<br>";
    $body.='<strong>Descuento:</strong> '.$descuento."<br>";
    $body.='<strong>Total Ahorrado:</strong> '.$totlaahorro."<br>";   
    $body.='<strong>Total Pagado:</strong> '.($totalPagado- $descuento)."<br>";    

    if($compraReloades->getPremio()!=null)
    $body.='<strong>Premio:</strong> '.$compraReloades->getPremio()->getNombre()."<br>";
    $to=array(
        0 =>$email,
        1 =>$compraReloades->getVendedor()->getEmail(),
        2 =>'pgodoy@centromultimedia.com.ar',
        3=>'cialealta@gmail.com'
    );
    $message ->setTo($to);
    $message->setBody(
        $body
    );

    $this->get('mailer')->send($message);
    return new JsonResponse(array(
        0=>'Enviado',
        1=>'Se ha enviado un resumen de su compra al correo proporcionado.',
        2=>'None'

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
    
    
    

    
}
