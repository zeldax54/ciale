<?php

namespace GEMA\gemaBundle\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use GEMA\gemaBundle\Entity\Catalogo;
use GEMA\gemaBundle\Form\CatalogoType;
use Symfony\Component\HttpFoundation\JsonResponse;
use GEMA\gemaBundle\Helpers\MyHelper;
use GEMA\gemaBundle\Helpers\PdfHelper;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use GEMA\gemaBundle\Entity\Tabla;
use GEMA\gemaBundle\Entity\TablaDatos;
use \stdClass;

/**
 * Catalogo controller.
 *
 */
class CatalogoController extends Controller
{
	public static $razasdata = array(15,21,22,23,24);

    /**
     * Lists all Catalogo entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = array();
        if ($request->isXmlHttpRequest()) {
            $repo = $em->getRepository('gemaBundle:Catalogo');
            $qb = $repo->filtrar($request);
            $result = $this->get("gema.utiles")->paginar($request->get("current"), $request->get("rowCount"), $qb->getQuery());
            return new JsonResponse($result);
        } else {
            $entities = $em->getRepository('gemaBundle:Catalogo')->findAll();
        }
        $accion = 'Listar Expedientes de Catalogo';
        $this->get("gema.utiles")->traza($accion);
        
        

        return $this->render('gemaBundle:Catalogo:index.html.twig', array(
                    'entities' => $entities,
        ));
    }
    /**
     * Creates a new Catalogo entity.
     *
     */
    public function createAction(Request $request)
    {
    
        $entity = new Catalogo();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $accion = '';
            $this->get("gema.utiles")->traza($accion);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('catalogo_show', array('id' => $entity->getId())));
        }
    
       return $this->render('gemaBundle:Catalogo:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a Catalogo entity.
    *
    * @param Catalogo $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Catalogo $entity)
    {
        $form = $this->createForm(new CatalogoType(), $entity, array(
            'action' => $this->generateUrl('catalogo_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Crear'));

        return $form;
    }

    /**
     * Displays a form to create a new Catalogo entity.
     *
     */
    public function newAction()
    {
        $entity = new Catalogo();
        $form   = $this->createCreateForm($entity);

    
        return $this->render('gemaBundle:Catalogo:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Catalogo entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:Catalogo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Catalogo entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $accion = ' ';
        $this->get("gema.utiles")->traza($accion);

        return $this->render('gemaBundle:Catalogo:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Catalogo entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:Catalogo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Catalogo entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('gemaBundle:Catalogo:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Catalogo entity.
    *
    * @param Catalogo $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Catalogo $entity)
    {
        $form = $this->createForm(new CatalogoType(), $entity, array(
            'action' => $this->generateUrl('catalogo_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar'));

        return $form;
    }
/**
    * Edits an existing Catalogo entity.
*
    */
    public function updateAction(Request $request, $id)
{
    $em = $this->getDoctrine()->getManager();

    $entity = $em->getRepository('gemaBundle:Catalogo')->find($id);

    if (!$entity) {
    throw $this->createNotFoundException('Unable to find Catalogo entity.');
    }

    $deleteForm = $this->createDeleteForm($id);
    $editForm = $this->createEditForm($entity);
    $editForm->handleRequest($request);
    $accion = ' ';
    $this->get("gema.utiles")->traza($accion);
    $em->flush();  
 

    
    return $this->redirect($this->generateUrl('catalogo_edit', array('id' => $id)));
  
}
    /**
     * Deletes a Catalogo entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('gemaBundle:Catalogo')->find($id);
            $hojas = $entity->getHojas();
            foreach($hojas as $hoja)
              $em->remove($hoja);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Catalogo entity.');
            }
            
        $accion = ' ';
        $this->get("gema.utiles")->traza($accion);
            $em->remove($entity);
            $em->flush();

        return $this->redirect($this->generateUrl('catalogo'));
    }

    /**
     * Creates a form to delete a Catalogo entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('catalogo_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Eliminar'))
            ->getForm()
        ;
    }


    public function unificadoscatalogAction(){

        ignore_user_abort(true);
        set_time_limit(0);
        $request = $this->getRequest();
         $id = $source=$_POST["id"];       

            $em = $this->getDoctrine()->getManager();
            $conf=$em->getRepository('gemaBundle:Configuracion')->find(1);
            $zoompdf=$conf->getZoompdf();
            $urlvirtual=$conf->getVirtualurl();
            $catalogo = $em->getRepository('gemaBundle:Catalogo')->find($id);
            $hojas=$em->getRepository('gemaBundle:Catalogohojas')->OrderedbyParent($id);
            $baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();           
           
            $basedet = $urlvirtual == true ? DIRECTORY_SEPARATOR.'toro/': $baseurl.DIRECTORY_SEPARATOR.'toro/';
        
         $html = $this->unificadostestAction(true,$id,$basedet );
        //print($html);die();   
        $helper=new MyHelper();
        $guid=$helper->GUID();
        $filename = $helper->remove_accents($catalogo->getTitulo());
        $filename=str_replace(' ','',$filename);
        $webPath=$this->get('kernel')->getRootDir().'/../web/pdfs/catalogs/'.$guid .'/';
        if (!file_exists($webPath)) {
            mkdir($webPath, 0777, true);
        }
        $webPath=$webPath.$filename.'.pdf';    
        $pdfGenerator = $this->get('knp_snappy.pdf');
        $pdfGenerator->setTimeout(10000);

        $options = array(
           'margin-top'    => 2,
           'margin-right'  => 1,
           'margin-bottom' => 16,
           'margin-left'   => 1,               
            //'dpi'=>2, 
          // 'zoom'=>$zoompdf         
       )  ;     
     
       $path = $urlvirtual ==true ? DIRECTORY_SEPARATOR.'/pdfs/catalogs/'.$guid .'/'.$filename.'.pdf': $baseurl.DIRECTORY_SEPARATOR.'/pdfs/catalogs/'.$guid .'/'.$filename.'.pdf';
  
        foreach ($options as $margin => $value) 
            $pdfGenerator->setOption($margin, $value);                 
            $urlfooter = $this->generateUrl(
                'gema_footertest'              
            );     
         $pdfGenerator->setOption('footer-html', $request->getScheme() . '://' . $request->getHttpHost() .$urlfooter);                    
      
            $pdfGenerator->generateFromHtml(
            $html,
            $webPath
        );


             

    return new JsonResponse(array(
        0=>'1',
        1=>$path,
        2=>$filename.'.pdf'
    ));


    }


    public function unificadostestAction($forprinter=false,$idparam=null,$basedet = null){
      	
            ignore_user_abort(true);
            set_time_limit(0);
            $request = $this->getRequest();
            if($idparam==null)
              $id = 2;
            else
              $id =$idparam;        
            $html='';
            $em = $this->getDoctrine()->getManager();
            $conf=$em->getRepository('gemaBundle:Configuracion')->find(1);
            $zoompdf=$conf->getZoompdf();
            $urlvirtual=$conf->getVirtualurl();
            $catalogo = $em->getRepository('gemaBundle:Catalogo')->find($id);
            $hojas=$em->getRepository('gemaBundle:Catalogohojas')->OrderedbyParent($id);
            $html='';      
            $cont=1;
            $unificados = array();			
            foreach($hojas as $hoja){
				
			try
				{
				
						
				if($hoja->getTipo()=="Untoro")
				{
                        $u = new stdClass();
                        $u->tipo=1;
                        $u->data= $this->untoroPure($hoja,$basedet );          
                        $unificados[] = $u;            
                }
                    
                 if($hoja->getTipo()=="Dostoros")
				 {
                    $u = new stdClass();
                    $u->tipo=2;
                    $u->data = $this->dostorospure($hoja,$basedet );           
                    $unificados[] = $u;   
                 }
              
                           
                 if($hoja->getTipo()!="Imagen")
                 $cont++;
					
		
                

                }
                catch(\Exception $inner)
				{

                    return new JsonResponse(array(
                        0=>'0',
                        1=>'Hoja:'.$hoja->getNumero().' id:'.$hoja->getId().' tipo:'.$hoja->getTipo() .'. error:'.$inner->getMessage()
                    ));
                }
			}

            if($forprinter==true)
            return $this->renderView('gemaBundle:Maquetacatalogo:unificado.html.twig', array(  
                'unificados'=>$unificados,
                
             )
           );

            return $this->render('gemaBundle:Maquetacatalogo:unificado.html.twig', array(  
                'unificados'=>$unificados,
                
             )
           );
        
    }


    /* #region  pdfgenerateAction*/
    public function pdfgenerateAction(){

        try{         
            
            ignore_user_abort(true);
            set_time_limit(0);
            $request = $this->getRequest();
            $id = $source=$_POST["id"];            
            $html='';
            $em = $this->getDoctrine()->getManager();
            $conf=$em->getRepository('gemaBundle:Configuracion')->find(1);
            $zoompdf=$conf->getZoompdf();
            $urlvirtual=$conf->getVirtualurl();
            $catalogo = $em->getRepository('gemaBundle:Catalogo')->find($id);
            $hojas=$em->getRepository('gemaBundle:Catalogohojas')->OrderedbyParent($id);
            $html='';      
            $cont=1;
            foreach($hojas as $hoja){

                try{
                    if($hoja->getTipo()=="Untoro")
                    $html.=$this->untorotestAction($hoja,$cont,1); 
                 if($hoja->getTipo()=="Dostoros")
                    $html.=$this->dostorostestAction($hoja,$cont,1); 
                 if($hoja->getTipo()=="Imagen")
                    $html.=$this->imgtestcatAction($hoja);               
                 if($hoja->getTipo()!="Imagen")
                 $cont++;

                }
                catch(\Exception $inner){

                    return new JsonResponse(array(
                        0=>'0',
                        1=>'Hoja:'.$hoja->getNumero().' id:'.$hoja->getId().' tipo:'.$hoja->getTipo() .'. error:'.$inner->getMessage()
                    ));
                }              
                

            }
			//print($html);die();   
            $helper=new MyHelper();
            $guid=$helper->GUID();
            $filename = $helper->remove_accents($catalogo->getTitulo());
            $filename=str_replace(' ','',$filename);
            $webPath=$this->get('kernel')->getRootDir().'/../web/pdfs/catalogs/'.$guid .'/';
            if (!file_exists($webPath)) {
                mkdir($webPath, 0777, true);
            }
            $webPath=$webPath.$filename.'.pdf';    
            $pdfGenerator = $this->get('knp_snappy.pdf');
            $pdfGenerator->setTimeout(10000);
    
            $options = array(
               'margin-top'    => 2,
               'margin-right'  => 1,
               'margin-bottom' => 16,
               'margin-left'   => 1,     
                //'dpi'=>2, 
              // 'zoom'=>$zoompdf         
           )  ;                   
           if($urlvirtual==true)
           $path=DIRECTORY_SEPARATOR.'/pdfs/catalogs/'.$guid .'/'.$filename.'.pdf';
       else{
           $baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();
           $path=$baseurl.DIRECTORY_SEPARATOR.'/pdfs/catalogs/'.$guid .'/'.$filename.'.pdf';
           }
       //$htmlfooter = $this->footerCAtAction(2);
            foreach ($options as $margin => $value) 
                $pdfGenerator->setOption($margin, $value);                 
                $urlfooter = $this->generateUrl(
                    'gema_footertest'              
                );
          // print($request->getScheme() . '://' . $request->getHttpHost() .$urlfooter);die();
           //  $pdfGenerator->setOption('header-html', 'https://www.google.com/'); 
             $pdfGenerator->setOption('footer-html', $request->getScheme() . '://' . $request->getHttpHost() .$urlfooter);    
                     
          
                $pdfGenerator->generateFromHtml(
                $html,
                $webPath
            );

          /*  $pdfGenerator->setOption('header-html', 'http://www.yahoo.com')
            ->setOption('footer-html', 'http://www.msn.com')
            ->generate('http://www.google.fr', $path);*/     
    
    
                 

        return new JsonResponse(array(
            0=>'1',
            1=>$path,
            2=>$filename.'.pdf'
        ));
       
     } 
        catch(\Exception $e){

            return new JsonResponse(array(
                0=>'0',
                1=>$e->getMessage()
            ));
        }
       
    }

    /* #endregion */

    private function untoroPure($hoja=null,$basedet=null){
        $pdfHelper = new PdfHelper();
        $em = $this->getDoctrine()->getManager();
        return $pdfHelper->untoroPure($em,$hoja,$basedet);
    }

      private function dostorospure($hoja=null,$basedet=null){
        $pdfHelper = new PdfHelper();
        $em = $this->getDoctrine()->getManager(); 
        return $pdfHelper->dostorospure($em,$hoja,$basedet);
      }

      private function silueta($toro){
        $helper = new MyHelper(); 
        if (in_array($toro->getRaza()->getId(), array(15,21,22,23,24)))
            return  $helper->directPic('bundles/gema/catalogresources/img/','silueta_2.jpg');
       return  $helper->directPic('bundles/gema/catalogresources/img/','toro-size.jpg');

      }

      
   
     public function untorotestAction($hoja=null,$nropagina=2,$islocal=0){
        $em = $this->getDoctrine()->getManager();     
        if($hoja==null){           
            $hoja = $em->getRepository('gemaBundle:Catalogohojas')->find(308);            
        }       
        ////////////////////////////////////////////////
        $view='gemaBundle:Maquetacatalogo:unToro.html.twig';
        $helper=new MyHelper();     
        $toro = $hoja->getToros()[0];
        $toro->foto = $helper->randomPic('toro'.DIRECTORY_SEPARATOR.$toro->getGuid().'P'.DIRECTORY_SEPARATOR);
        $toro->nacionalidadflag=$helper->Nacionalidad($toro->getNacionalidad());
        $toro->facilidadglag=$helper->imgFacilidadParto($toro->getFacilidadparto());
        if(count($toro->getYoutubes())>0)
        $toro->video=$toro->getYoutubes()[0]->getUrl();
        else
        $toro->video='#';
        //table
        if($toro->getRaza()->getTablasmanual()!=true)
        {
            $tablasflag=json_decode($toro->getTablagenetica(),true);
            $tablarname=$em->getRepository('gemaBundle:Tabla')->find($toro->getTipotablaselected());
            if(isset($tablasflag[$tablarname->getNombre()])){
                $tablasflag=$tablasflag[$tablarname->getNombre()];
                $tabla=$tablarname;
            }
           else{
               $nkey='';
             if($tablasflag!=null)
               foreach($tablasflag as $key=>$tabla){
                   $tablasflag=$tablasflag[$key];$nkey=$key;break;
               }
               $tabla=$em->getRepository('gemaBundle:Tabla')->findOneBy(array(
                  'nombre'=>$nkey
               ));
           }

            $tablagennombre=$tablarname->getNombre();
        }
        else{
            $tablasflag=null;
            $tablagennombre=null;
            $tabla=null;

            if($toro->getTablagenetica()!=null){
                $datos=json_decode($toro->getTablagenetica(),true);
                $tablaname=array_keys($datos);
                $tablarname=$tablaname[0];
                $columnas= array_keys($datos[$tablarname][0]);
                $tablasflag=$datos[$tablarname];


                $tabla=new Tabla();
                foreach($columnas as $col){
                    if($col!='rowhead'){
                        $td=new TablaDatos();
                        $td->setNombre($col);
                        $tabla->addTabladato($td);
                    }
                }
            }
        }
        //
		
        $view='gemaBundle:Maquetacatalogo:unToro.html.twig';
        if($islocal==1)
        return $this->renderView($view, array(  
            'toro'=>$toro,
            'tabla'=>$tabla,
            'tablagenetica'=>$tablasflag,
            'nropagina'=>$nropagina,
			'razasdata'=>self::$razasdata
         )
       );
        else
         return $this->render($view, array(  
             'toro'=>$toro,
             'tabla'=>$tabla,
             'tablagenetica'=>$tablasflag,
             'nropagina'=>$nropagina,
			 'razasdata'=>self::$razasdata
          )
        );
      }


     

      public function dostorostestAction($hoja=null,$nropagina=2,$islocal=0){
        $em = $this->getDoctrine()->getManager(); 
        $helper = new MyHelper();    
        if($hoja==null)        
            $hoja = $em->getRepository('gemaBundle:Catalogohojas')->find(5);  
            
            $toro1 = $hoja->getToros()[0];
            $toro2 = $hoja->getToros()[1];    
        
         $toro1->foto = $helper->randomPic('toro'.DIRECTORY_SEPARATOR.$toro1->getGuid().'P'.DIRECTORY_SEPARATOR);
         $toro2->foto = $helper->randomPic('toro'.DIRECTORY_SEPARATOR.$toro2->getGuid().'P'.DIRECTORY_SEPARATOR);

         $toro1->nacionalidadflag=$helper->Nacionalidad($toro1->getNacionalidad());
         $toro2->nacionalidadflag=$helper->Nacionalidad($toro2->getNacionalidad());

         $toro1->facilidadglag=$helper->imgFacilidadParto($toro1->getFacilidadparto());
         $toro2->facilidadglag=$helper->imgFacilidadParto($toro2->getFacilidadparto());        

         if(count($toro1->getYoutubes())>0)
           $toro1->video=$toro1->getYoutubes()[0]->getUrl();
         else
           $toro1->video='#';          
        if(count($toro2->getYoutubes())>0)
           $toro2->video=$toro2->getYoutubes()[0]->getUrl();
         else
           $toro2->video='#';  
        
           $tab1 = $this->tablaSet($toro1,$em);
           $tablaflag1=$tab1['tablaflag'];
           $tabla1 =$tab1['tabla'];
          
           $tab2 = $this->tablaSet($toro2,$em);
           $tablaflag2 = $tab2['tablaflag'];
           $tabla2 = $tab2['tabla'];



        ////////////////////////////////////////////////
      
        $view='gemaBundle:Maquetacatalogo:dosToros.html.twig';
        $valores=array(
            'toro1'=>$toro1,
            'toro2'=>$toro2,
            'tablaflag1'=>$tablaflag1,
            'tabla1'=>$tabla1,
            'tablaflag2'=>$tablaflag2,
            'tabla2'=>$tabla2,
            'nropagina'=>$nropagina,
			'razasdata'=>self::$razasdata
			

        );
        if($islocal==1)
        return $this->renderView($view, $valores
       );
        else
         return $this->render($view, $valores
        );       

      }
      

      public function imgtestcatAction($hoja = null){       
        $rettest = true;  
       if($hoja==null){
            $em = $this->getDoctrine()->getManager();     
            $hoja = $em->getRepository('gemaBundle:Catalogohojas')->find(4); 
        }else
        $rettest = false;             
        $helper=new MyHelper();
        $imghoja = $helper->randomPic('catalogohojas'.DIRECTORY_SEPARATOR.$hoja->getGuid().DIRECTORY_SEPARATOR);
        $view='gemaBundle:Maquetacatalogo:img.html.twig';
        
        if( $rettest == true)
           return $this->render($view, array(   
            'imghoja'=>$imghoja ) ); 
        return $this->renderView($view, array(   
            'imghoja'=>$imghoja 
        )
       ); 
    }

    

    public function footerCAtAction($numero = null)
    {      
        $test=false;
        if($numero==null){
            $numero=2;
            $test=true;
        }
        $view='gemaBundle:Maquetacatalogo:footer.html.twig';    
        if($test==false)
        return $this->renderView($view, array(   
            'nropagina'=>$numero));
       else 
       return $this->render($view, array(   
        'nropagina'=>$numero));
    }

    private function tablaSet($toro,$em){
        $helper = new MyHelper();
        return $helper->tablaSet($toro,$em);
    }
}
