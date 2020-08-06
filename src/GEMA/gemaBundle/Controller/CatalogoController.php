<?php

namespace GEMA\gemaBundle\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use GEMA\gemaBundle\Entity\Catalogo;
use GEMA\gemaBundle\Form\CatalogoType;
use Symfony\Component\HttpFoundation\JsonResponse;
use GEMA\gemaBundle\Helpers\MyHelper;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
/**
 * Catalogo controller.
 *
 */
class CatalogoController extends Controller
{

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
               
                if($hoja->getTipo()=="Untoro")
                   $html.=$this->untorotestAction($hoja,$cont,1); 
                if($hoja->getTipo()=="Dostoros")
                   $html.=$this->dostorostestAction($hoja,$cont,1); 
                if($hoja->getTipo()=="Imagen")
                   $html.=$this->imgtestcatAction($hoja);               
                if($hoja->getTipo()!="Imagen")
                $cont++;

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
               'margin-top'    => 0,
               'margin-right'  => 1,
               'margin-bottom' => 0,
               'margin-left'   => 1,     
                 //'dpi'=>2,
              // 'zoom'=>$zoompdf         
           )  ;                   
              
       
            foreach ($options as $margin => $value) 
                $pdfGenerator->setOption($margin, $value);        
            $pdfGenerator->generateFromHtml(
                $html,
                $webPath
            );
    
    
       if($urlvirtual==true)
            $path=DIRECTORY_SEPARATOR.'/pdfs/catalogs/'.$guid .'/'.$filename.'.pdf';
        else{
            $baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();
            $path=$baseurl.DIRECTORY_SEPARATOR.'/pdfs/catalogs/'.$guid .'/'.$filename.'.pdf';
            }            

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

   
    public function untorotestAction($hoja=null,$nropagina=2,$islocal=0){
        $em = $this->getDoctrine()->getManager();     
        if($hoja==null){           
            $hoja = $em->getRepository('gemaBundle:Catalogohojas')->find(3);            
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
            'nropagina'=>$nropagina
         )
       );
        else
         return $this->render($view, array(  
             'toro'=>$toro,
             'tabla'=>$tabla,
             'tablagenetica'=>$tablasflag,
             'nropagina'=>$nropagina
          )
        );
      }


      public function dostorostestAction($hoja=null,$nropagina=2,$islocal=0){
        $em = $this->getDoctrine()->getManager(); 
        $helper = new MyHelper();    
        if($hoja==null)        
            $hoja = $em->getRepository('gemaBundle:Catalogohojas')->find(1);  
            
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
           $tablaflag1=$this->tablaSet($toro1,$em)['tablaflag'];
           $tabla1 =$this->tablaSet($toro1,$em)['tabla'];
          
           $tablaflag2=$this->tablaSet($toro2,$em)['tablaflag'];
           $tabla2=$this->tablaSet($toro2,$em)['tabla'];



        ////////////////////////////////////////////////
      
        $view='gemaBundle:Maquetacatalogo:dosToros.html.twig';
        $valores=array(
            'toro1'=>$toro1,
            'toro2'=>$toro2,
            'tablaflag1'=>$tablaflag1,
            'tabla1'=>$tabla1,
            'tablaflag2'=>$tablaflag2,
            'tabla2'=>$tabla2,
            'nropagina'=>$nropagina

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


    private function tablaSet($toro,$em){
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
        return array(
            'tablaflag'=>$tablasflag,
            'tabla'=>$tabla,

        );
    }
}
