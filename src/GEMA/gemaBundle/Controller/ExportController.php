<?php
/**
 * Created by PhpStorm.
 * User: AK0
 * Date: 08/07/2017
 * Time: 15:17
 */

namespace GEMA\gemaBundle\Controller;
use DirectoryIterator;
use GEMA\gemaBundle\Entity\Tabla;
use GEMA\gemaBundle\Entity\TablaDatos;
use InvalidArgumentException;
use PHPExcel_Cell_DataType;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use GEMA\gemaBundle\Helpers\MyHelper;
use GEMA\gemaBundle\Helpers\PdfHelper;
use PHPExcel;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Filesystem\Filesystem;
use \stdClass;


class ExportController extends Controller
{

    function getNext($numero){
        $iter=0;
        for($x = 'A'; $x < 'ZZ'; $x++)
        {
            if($numero==$iter)
                return $x;
            $iter++;
        }
           return null;
    }

    function getiterfromLetra($letra){
        $iter=0;
        for($x = 'A'; $x < 'ZZ'; $x++)
        {
            if($letra==$x)
                return $iter;
            $iter++;
        }
        return null;
    }

    function random_color_part() {
        return str_pad( dechex( mt_rand( 150, 255 ) ), 2, '0', STR_PAD_LEFT);
    }

    function rand_color() {
        return $this->random_color_part() .$this->random_color_part() .$this->random_color_part();
    }

    function SetBold($objPHPExcel,$columnas,$rownumber){
        $style = array(
            'alignment' => array(
                'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            )
        );

        foreach($columnas as $l){
            $objPHPExcel->getActiveSheet()->getStyle($l.$rownumber)->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle($l.$rownumber)->applyFromArray($style);
        }


    }
    function Colorear($objPHPExcel,$columnas,$rownumber){
        $color=$this->rand_color();
        foreach($columnas as $c){
            $objPHPExcel->getActiveSheet()->getStyle($c.$rownumber)->getFill()->applyFromArray(array(
                'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                'startcolor' => array(
                    'rgb' => $color
                )
            ));
        }
    }

    function ColorearSingle($objPHPExcel,$columna,$rownumber,$color){
        $style = array(
            'alignment' => array(
                'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            )
        );
        $objPHPExcel->getActiveSheet()->getStyle($columna.$rownumber)->getFill()->applyFromArray(array(
            'type' => \PHPExcel_Style_Fill::FILL_SOLID,
            'startcolor' => array(
                'rgb' => $color
            )
        ));
        $objPHPExcel->getActiveSheet()->getStyle($columna.$rownumber)->applyFromArray($style);
    }

    function centerCell($objPHPExcel,$columna,$rownumber){
        $style = array(
            'alignment' => array(
                'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            )
        );
        $objPHPExcel->getActiveSheet()->getStyle($columna.$rownumber)->applyFromArray($style);
    }


  function excelExportAction(){

     if(isset($_POST['razaid'])){


         $em = $this->getDoctrine()->getManager();
         $raza=$em->getRepository('gemaBundle:Raza')->find($_POST['razaid']);
         $toros=$raza->getToros();
         $torosid=array();
         $alltoros=$_POST['alltoros'];
         foreach ($toros as $t){
             $torosid[]=$t->getId();
         }


     }else{

         $toros=$_POST['toros'];
         $alltoros=$_POST['alltoros'];

         $torosid=explode('|',$toros);
     }



    $objPHPExcel = new \PHPExcel();


    $objPHPExcel->setActiveSheetIndex(0);

    $em = $this->getDoctrine()->getManager();
    $raza=$em->getRepository('gemaBundle:Raza')->find($em->getRepository('gemaBundle:Toro')->find($torosid[0])->getRaza()->getId());

     //Cabeceras
      $mapadatos=$raza->getMapa()->getMapadatos();
      $iter=0;
      $letras=array();
      foreach($mapadatos as $dato){
          $letra=$this->getNext($iter);

          $letras[]=$letra;
          $objPHPExcel->getActiveSheet()->SetCellValue($letra.'2',$dato->getComentario());
          $iter++;


//          if($dato->getComentario()=='Peso') {
//
//              $letra=$this->getNext($iter);
//              $objPHPExcel->getActiveSheet()->SetCellValue($letra.'2','Paises');
//              $iter++;
//          }
//
//
//          if($dato->getComentario()=='Precio'){
//              $letra=$this->getNext($iter);
//              $iter++;
//              $objPHPExcel->getActiveSheet()->SetCellValue($letra.'2','Frame');
//              $letra=$this->getNext($iter);
//              $objPHPExcel->getActiveSheet()->SetCellValue($letra.'2','Tabla de EG');
//              $iter++;
//          }
      }
      $this->SetBold($objPHPExcel,$letras,2);
      $this->Colorear($objPHPExcel,$letras,1);
      $tablas=array();

      if($alltoros==='true' && $raza->getfather()!=null){
          $nombexcel=substr($raza->getfather()->getNombre(),0,30);

          $razas=$raza->getFather()->getRazas();

          foreach($razas as $rr){

              $ttablas=$rr->getTipotabla()->getTablas();
              foreach($ttablas as $tt){
                  $tablas[]=$tt;
              }

          }
      }
      else{


          if($raza->getTipotabla()!=null){
              $tablas=$raza->getTipotabla()->getTablas();//Here
          }

          $nombexcel=substr($raza->getNombre(),0,30);
      }

      $generalcolstable=array();
      foreach($tablas as $t){
          $color=$this->rand_color();
          $letras=array();
          foreach($t->getTablaDatos() as $d){
              $letra=$this->getNext($iter);
              $letras[]=$letra;
                  $objPHPExcel->getActiveSheet()->SetCellValue($letra.'2',$d->getNombre());
              $this->ColorearSingle($objPHPExcel,$letra,2,$color);

              $iter++;
              foreach($t->getTablaBody() as $b){
                  if($b->getRowName()!='DEP')
                  {
                      $letra=$this->getNext($iter);
                      $letras[]=$letra;
                      $objPHPExcel->getActiveSheet()->SetCellValue($letra.'2',$b->getRowName());
                      $iter++;
                  }

              }
          }
        //  $generalcolstable[]=$t->getNombre();
          $generalcolstable[$t->getNombre()][]=$letras[0];


          $objPHPExcel->getActiveSheet()->SetCellValue($letras[0].'1',$t->getNombre());
          $this->centerCell($objPHPExcel,$letras[0],1);
          $objPHPExcel->getActiveSheet()->mergeCells($letras[0].'1:'.$letras[count($letras)-1].'1');
          $this->Colorear($objPHPExcel,$letras,1,$this->rand_color());
      }
      $rowsiter=3;

      $serializer = $this->container->get('jms_serializer');

      foreach($torosid as $id){
          $iter=0;
          $toro=$em->getRepository('gemaBundle:Toro')->find($id);
          $reports = $serializer->serialize($toro, 'json');
          $toroarr=json_decode($reports,true);

          foreach($mapadatos as $dato){
              $letra=$this->getNext($iter);
              $head=strtolower($dato->getNombre());
              if(!isset($toroarr[$head]))
                  $objPHPExcel->getActiveSheet()->SetCellValue($letra.$rowsiter," ");
              else{
                  $valor=$toroarr[$head];
                  if($head=='nuevo' || $head=='cp'){
                      $valor= $this->convertBool($valor);
                  }
                  if($head=='fechanacimiento'){
                      if($valor!=null && $valor!=''){
                          $valor= substr($valor, 0, 10);
                          $ex=explode('-',$valor);
                          $valor=$ex[2].'/'.$ex[1].'/'.$ex[0];
                          $objPHPExcel->getActiveSheet()->setCellValue($letra.$rowsiter, $valor );
                          $objPHPExcel->getActiveSheet()->getStyle($letra.$rowsiter)
                              ->getNumberFormat()->setFormatCode('dd/mm/yyyy');
                      }

                  }else
                  $objPHPExcel->getActiveSheet()->SetCellValue($letra.$rowsiter,$valor);
                  $this->centerCell($objPHPExcel,$letra,$rowsiter);
              }
               $iter++;
          }


          if(isset($toroarr['tablagenetica']))
          {
              $tablasdata=json_decode($toroarr['tablagenetica'],true);

              foreach($tablas as $t){
                  $iter=$this->getiterfromLetra($generalcolstable[$t->getNombre()][0]);
                  foreach($t->getTablaDatos() as $d){

                      // print($iter);die();

                      if(isset($tablasdata[$t->getNombre()])){//Para todas las Tablas
                          $tabd=$tablasdata[$t->getNombre()];
                          foreach($t->getTablaBody() as $b){
                              $myrow=$this->findInTab($b->getRowName(),$tabd);
                              $value=$myrow[$d->getNombre()];
                              $letra=$this->getNext($iter);

                              $objPHPExcel->getActiveSheet()->SetCellValue($letra.$rowsiter,$value);
                              $this->centerCell($objPHPExcel,$letra,$rowsiter);
                              $iter++;
                          }
                      }

                      //    print_r($tabd);die();

                  }

              }
          }
          $rowsiter++;
      }
      header('Content-type: application/vnd.ms-excel');
      header('Content-Disposition: attachment; filename="'.$nombexcel.'.xls"');
      header('Cache-Control: max-age=0');


      $objPHPExcel->getActiveSheet()->setTitle($nombexcel);
    $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

      ob_start();

      try {
          $objWriter->save('php://output');
      } catch (\PHPExcel_Writer_Exception $e) {
          $response =  array(
              'op' => 'ok',
              'file' => ''
          );
          die(json_encode($response));
      }

      $xlsData = ob_get_contents();


      ob_end_clean();

      $response =  array(
          'op' => 'ok',
          'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData)
      );
      die(json_encode($response));
  }



    function findInTab($key,$tab){

        foreach($tab as $t){
            if($t['rowhead']==$key)
                return $t;
        }
        return -1;
    }

function convertBool($valor){
   // print ($valor);die();
    if($valor=='VERDADERO')
        return 'si';
    return 'no';

}



public function exceladminAction($razaid){
    $em = $this->getDoctrine()->getManager();
    $raza=$em->getRepository('gemaBundle:Raza')->find($razaid);
    $toros=$raza->getToros();
    $ids='';
    $iter=0;
    foreach($toros as $t){
        $iter++;
        if($iter>=count($toros))
        $ids.=$t->getId();
        else
            $ids.=$t->getId().'|';
     }
    $this->excelExportAction($ids,'false');

   }


   

    function todopdfAction($toroId){

        $em = $this->getDoctrine()->getManager();
        $toro=$em->getRepository('gemaBundle:Toro')->find($toroId);
        $helper=new MyHelper();
        global $kernel;
        $basepath= $kernel->getRootDir() . DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'web'.DIRECTORY_SEPARATOR;
        $dompdf = $this->get('slik_dompdf');

        $img=$helper->randomPic('toro'.DIRECTORY_SEPARATOR.$toro->getGuid().'P'.DIRECTORY_SEPARATOR);
        if($img==null)
            $img=$helper->directPic('genericfiles'.DIRECTORY_SEPARATOR,'toro.png');
        $img=$basepath.$img;
      //  print ($img);die();

        $html='<html style="overflow: hidden;" lang="es"><head><meta charset="UTF-8">';

        $html.='<div style="width: 100%;background-color: #C06000" class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <div style="text-align: center;margin: 0 auto"><span style="font-family:Fago-Bold;font-weight: bold ;color:#f0ecf7">'.$toro->getRaza()->getNombre().'</span></div>

         </div>
         <br>
         <div class="row">
        <div class="col-md-6" style="width:50%">
        <img id="imagen-principal" src="'.$img.'" class="img-responsive toroimg" width="95%" height="300" style="border-radius: 7px 7px 7px 7px;">

        </div>

        <div class="col-md-6">
        AAAAAAAAAAAAAAAAAAAAAA
        </div>


        </div>


         '

        ;




        $dompdf->getpdf($html);

        $pdfoutput = $dompdf->output();
       // print($pdfoutput);die();
        $dompdf->stream($toro->getApodo() . ".pdf");




    }


   //Catalogo

   public function catalogbaseinfoAction(){

    $helper=new MyHelper();
    $em = $this->getDoctrine()->getManager();
    $myRepo = $em->getRepository('gemaBundle:Configuracion');
    $request = $this->getRequest();
    $baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();

    $miniaturacrarpdf=$helper->randomPic('pdfresources/miniaturacrearpdf/',true);
    $miniaturaimprimirtoros=$helper->randomPic('pdfresources/miniaturaimprimirtoros/',true);
    $capas=$helper->filesInFolder('pdfresources/tapas/',true);
    $imgIntrod=$helper->randomPic('pdfresources/backgroundtitulo/',true);
    $imgIntrodName=$imgIntrod;

    $imglistaprec=$helper->randomPic('pdfresources/backgroundlistaprecios/',true);
    $imgMsjintrod=$helper->randomPic('pdfresources/backgroundintroductorio/',true);
    $imgpreload=$helper->randomPic('pdfresources/preload/',false);





    $urlvirtual=$myRepo->find(1)->getVirtualurl();
    $toreplace = array('pdfresources/tapas/','.','_','\\','small','(',')');
    $torosInfo=array();

    $ids=$_POST['torosid'];

    foreach($ids as $id){

        $torosInfo[]=array(
            0=>$id,
            1=>$em->getRepository('gemaBundle:Toro')->find($id)->getApodo()
        );

     }

    if($urlvirtual==true){
        $miniaturacrarpdf= DIRECTORY_SEPARATOR.$miniaturacrarpdf;
        $miniaturaimprimirtoros= DIRECTORY_SEPARATOR.$miniaturaimprimirtoros;
        $imgIntrod=DIRECTORY_SEPARATOR.$imgIntrod;
        $imglistaprec=DIRECTORY_SEPARATOR.$imglistaprec;
        $imgMsjintrod=DIRECTORY_SEPARATOR.$imgMsjintrod;
        $imgpreload=DIRECTORY_SEPARATOR.$imgpreload;

        foreach($capas as $c){
            $capasfin[]= array(0=>DIRECTORY_SEPARATOR.$c,1=>DIRECTORY_SEPARATOR.$c,2=> str_replace($toreplace,'',$c));

        }
    }
    else{
        $miniaturacrarpdf=$baseurl.DIRECTORY_SEPARATOR.$miniaturacrarpdf;
        $miniaturaimprimirtoros=$baseurl.DIRECTORY_SEPARATOR.$miniaturaimprimirtoros;
        $imgIntrod=$baseurl.DIRECTORY_SEPARATOR.$imgIntrod;
        $imglistaprec=$baseurl.DIRECTORY_SEPARATOR.$imglistaprec;
        $imgMsjintrod=$baseurl.DIRECTORY_SEPARATOR.$imgMsjintrod;
        $imgpreload=$baseurl.DIRECTORY_SEPARATOR.$imgpreload;

        foreach($capas as $c){
            $capasfin[]= array(0=>$baseurl.DIRECTORY_SEPARATOR.$c,1=>DIRECTORY_SEPARATOR.$c,2=>str_replace($toreplace,'',$c));

        }

    }


    $datos[]=array(
        'miniaturacrarpdf'=>$miniaturacrarpdf,
        'miniaturaimprimirtoros'=>$miniaturaimprimirtoros,
        'capas'=>$capasfin,
        'imgIntrod'=>$imgIntrod,
        'imgIntrodName'=>$imgIntrodName,
        'torosInfo'=>$torosInfo,
        'imglistaprec'=>$imglistaprec,
        'imgmsjintrod'=>$imgMsjintrod,
        'imgpreload'=>$imgpreload

    );
    return new JsonResponse(
        $datos
    );

}


     public function pdfgenerateformovileAction()
     {
       try{
           ignore_user_abort(true);
           set_time_limit(0);
           $torosIds=$_POST["ids"];
           $filename=$_POST["filename"];
           $emails=explode(',',$_POST["emails"]);
           $impresion=$_POST["impresion"];           
           $data=$this->pdfSimple($torosIds,$filename,$impresion);
           $message = \Swift_Message::newInstance()
               ->setSubject('Su pdf esta listo!!!');
           $message->setContentType("text/html");
           $message->setFrom('info@ciale.com');

           $message ->setTo($emails);
           $message->setBody(
               '<span>Este enlace solo se mantendrá por 7 dias .Puede descargarlo desde el siguiente link <a href="'.$data[1].'">'.$filename.'</a> </span>'
           );
           $this->get('mailer')->send($message);
           return new JsonResponse(array(
               0=>'1',
               1=>$data[1],
               2=>$filename.'.pdf'
           ));

       }
       catch ( \Exception $exception){
           $message = \Swift_Message::newInstance()
               ->setSubject('error generando pdf');
           $message->setContentType("text/html");
           $message->setFrom('info@ciale.com');

           $message ->setTo('pgodoy@centromultimedia.com.ar');
           $message->setBody(
               $exception->getMessage()
           );
           $this->get('mailer')->send($message);


       }
     }

    public function pdfgenerateAction(){
         try
         {

             set_time_limit(0);
             $torosIds=$_POST["ids"];
             $filename=$_POST["filename"];
             $impresion=$_POST["impresion"];
             $data=$this->pdfSimple($torosIds,$filename,$impresion);
             return new JsonResponse(array(
                 0=>'1',
                 1=>$data[1],
                 2=>$filename.'.pdf'
             ));

         }catch (\Exception $e){

             return new JsonResponse(array(
                 0=>0,
                 1=>$e->getMessage()
             ));
         }
     }



     /* #region   pdfSimple*/
       private function pdfSimple($torosIds, $filename,$impresion)
       {      
        	
        $pdfhelper = new PdfHelper();
        $helper = new MyHelper();
        $em = $this->getDoctrine()->getManager();
        $myRepo = $em->getRepository('gemaBundle:Configuracion');      
        $request = $this->getRequest();  
        $baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();
        $urlvirtual=$myRepo->find(1)->getVirtualurl();    
        $basedet = $urlvirtual == true ? DIRECTORY_SEPARATOR.'/toro/': $baseurl.DIRECTORY_SEPARATOR.'/toro/'; 
        $unificados=$pdfhelper->UnificadosDataFormed($torosIds,$impresion,$em,$basedet);
        //Create object and options
        $pdfGenerator = $this->get('knp_snappy.pdf');
        $pdfGenerator->setTimeout(10000);
        $options = array(
            'margin-top'    => 2,
            'margin-right'  => 1,
            'margin-bottom' => 16,
            'margin-left'   => 1,                  
        );  
        foreach ($options as $margin => $value) {
            $pdfGenerator->setOption($margin, $value);
        }
        $urlfooter = $this->generateUrl(
            'gema_footertest'              
        );
        $pdfGenerator->setOption('footer-html', $request->getScheme() . '://' . $request->getHttpHost() .$urlfooter); 
         /////////////////////////////////////
        
         //File Dir and creation
         $guid=$helper->GUID();
         $webPath=$this->get('kernel')->getRootDir().'/../web/pdfs/'.$guid .'/';
         if (!file_exists($webPath)) 
             mkdir($webPath, 0777, true);         
         $webPath=$webPath.$filename.'.pdf';
         /////////////////
        $html = $this->renderView('gemaBundle:Maquetacatalogo:unificado.html.twig', array( 'unificados'=>$unificados,));
        $pdfGenerator->generateFromHtml(
            $html,
            $webPath
        );
        ///////////////////////
       
        //Web Dir and returning            
        $path = $urlvirtual ==true ? DIRECTORY_SEPARATOR.'/pdfs/'.$guid .'/'.$filename.'.pdf': $baseurl.DIRECTORY_SEPARATOR.'/pdfs/'.$guid .'/'.$filename.'.pdf';         
        return  array(
            0=>$filename,
            1=>$path,
        );
        
     }
     /* #endregion */


  /* #region   pdfSimpleOld*/
    /**
     * @param $torosIds
     * @param $filename
     * @return array
     */
    private function pdfSimpleOld($torosIds, $filename,$impresion)
    {

         $html='';
         $em = $this->getDoctrine()->getManager();
         $zoompdf=$em->getRepository('gemaBundle:Configuracion')->find(1)->getZoompdf();
         foreach($torosIds as $id){


             $toro=$em->getRepository('gemaBundle:Toro')->find($id);

             $helper=new MyHelper();
             $img=$helper->randomPic('toro'.DIRECTORY_SEPARATOR.$toro->getGuid().'P'.DIRECTORY_SEPARATOR);

             if($img==null){
                 $img=$helper->directPic('genericfiles'.DIRECTORY_SEPARATOR,'toro.png');
                 $pricimgdesc='';
             }

             else{
                 $vowels = array("_small","_large","_medium");
                 $img=str_replace($vowels,"",$img);
                 $data= explode(DIRECTORY_SEPARATOR,$img);
                 $descripcionprinc=$em->getRepository('gemaBundle:MediaDescription')-> findOneBy(
                     array(

                         'nombre'=>$data[2],
                         'folder'=>$data[0],
                         'subforlder'=>$data[1]

                     )

                 );
                 if($descripcionprinc!=null)
                     $pricimgdesc=$descripcionprinc->getDescripcion();
                 else
                     $pricimgdesc='';
             }

             $imgfp=$this->imgFacilidadParto($helper,$toro->getFacilidadparto());
             $imgcp=$this->ConceptPlus($helper,$toro->getCP());
             $silueta=$helper->directPic('genericfiles'.DIRECTORY_SEPARATOR,$toro->getRaza()->getSilueta().'.jpg');
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


             $view='gemaBundle:Page:toropdf.html.twig';


             $html.= $this->renderView($view, array(
                     'toro'=>$toro,
                     'princimg'=>$img,
                     'imgfp'=>$imgfp,
                     'imgcp'=>$imgcp,
                     'silueta'=>$silueta,
                     'tablagenetica'=>$tablasflag,
                     'tabla'=>$tabla,
                     'tablagennombre'=>$tablagennombre,
                     'razaname'=>   $toro->getRaza()->getNombre(),
                     'pricimgdesc'=>$pricimgdesc,


                 )
             );

         }
         $helper=new MyHelper();
         $guid=$helper->GUID();
         $webPath=$this->get('kernel')->getRootDir().'/../web/pdfs/'.$guid .'/';
         if (!file_exists($webPath)) {
             mkdir($webPath, 0777, true);
         }
         $webPath=$webPath.$filename.'.pdf';

         $pdfGenerator = $this->get('knp_snappy.pdf');
         $pdfGenerator->setTimeout(10000);

         $options = array(
            'margin-top'    => 4,
            'margin-right'  => 1,
            'margin-bottom' => 1,
            'margin-left'   => 1,
//                'dpi'=>1.33,
            'zoom'=>$zoompdf
        );
         foreach ($options as $margin => $value) {
             $pdfGenerator->setOption($margin, $value);
         }
         $pdfGenerator->generateFromHtml(
             $html,
             $webPath
         );

         $myRepo = $em->getRepository('gemaBundle:Configuracion');
         $request = $this->getRequest();
         $baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();

         $urlvirtual=$myRepo->find(1)->getVirtualurl();
         if($urlvirtual==true){
             $path=DIRECTORY_SEPARATOR.'/pdfs/'.$guid .'/'.$filename.'.pdf';

         }
         else{
             $path=$baseurl.DIRECTORY_SEPARATOR.'/pdfs/'.$guid .'/'.$filename.'.pdf';;

         }

         return array(
             0=>$filename,
             1=>$path
         );
     }
  /* #endregion */

   /* #region   toropdfAction*/
    public function  toropdfAction($toroid)
    {

        $em = $this->getDoctrine()->getManager();
        $toro=$em->getRepository('gemaBundle:Toro')->find($toroid);
        $helper=new MyHelper();
        $img=$helper->randomPic('toro'.DIRECTORY_SEPARATOR.$toro->getGuid().'P'.DIRECTORY_SEPARATOR);

        if($img==null){
            $img=$helper->directPic('genericfiles'.DIRECTORY_SEPARATOR,'toro.png');
            $pricimgdesc='';
        }

        else{
            $vowels = array("_small","_large","_medium");
            $img=str_replace($vowels,"",$img);
            $data= explode(DIRECTORY_SEPARATOR,$img);
            $descripcionprinc=$em->getRepository('gemaBundle:MediaDescription')-> findOneBy(
                array(

                    'nombre'=>$data[2],
                    'folder'=>$data[0],
                    'subforlder'=>$data[1]

                )

            );
            if($descripcionprinc!=null)
                $pricimgdesc=$descripcionprinc->getDescripcion();
            else
                $pricimgdesc='';
        }

        $imgfp=$this->imgFacilidadParto($helper,$toro->getFacilidadparto());
        $imgcp=$this->ConceptPlus($helper,$toro->getCP());
        $silueta=$helper->directPic('genericfiles'.DIRECTORY_SEPARATOR,$toro->getRaza()->getSilueta().'.jpg');
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


        return $this->render('gemaBundle:Page:toropdf.html.twig', array(
                'toro'=>$toro,
                'princimg'=>$img,
                'imgfp'=>$imgfp,
                'imgcp'=>$imgcp,
                'silueta'=>$silueta,
                'tablagenetica'=>$tablasflag,
                'tabla'=>$tabla,
                'tablagennombre'=>$tablagennombre,
                'razaname'=>   $toro->getRaza()->getNombre(),
                'pricimgdesc'=>$pricimgdesc

            )
        );
    }
   /* #endregion */


  



   /* #region  generatecatalogformovilAction */
    public function generatecatalogformovilAction(){

          try  {
              ignore_user_abort(true);
              set_time_limit(0);
              $source=$_POST["source"];
              $data=$this->gencatalogo($source);
              $path=$data[1];
              $filename=$data[0];            
              //Correo
              $message = \Swift_Message::newInstance()
                  ->setSubject('Su catálogo está listo!!!');
              $message->setContentType("text/html");
              $message->setFrom('info@ciale.com');
              $emails=explode(',',$source['email']);

                $message ->setTo($emails);

              $message->setBody(
                  '<span>Este enlace solo se mantendrá por 7 dias. Puede descargarlo desde el siguiente link <a href="'.$path.'">'.$filename.'.</a> </span>'
              );
              $this->get('mailer')->send($message);
              return new JsonResponse(array(
                  0=>'1',
                  1=>'2'
              ));

          }
          catch(\Exception $e){
              $message = \Swift_Message::newInstance()
                  ->setSubject('error generando catalogo');
              $message->setContentType("text/html");
              $message->setFrom('info@ciale.com');

              $message ->setTo('pgodoy@centromultimedia.com.ar');
              $message->setBody(
                  $e->getMessage()
              );
              $this->get('mailer')->send($message);
              return new JsonResponse(array(
                  0=>'0',
                  1=>$e->getMessage()
              ));

          }

    }
   /* #endregion */
   
   /* #region   generatecatalogoAction*/
    public function generatecatalogoAction()
    {

        try{
            set_time_limit(0);
            $source=$_POST["source"];

            $data=$this->gencatalogo($source);
            $path=$data[1];
            $filename=$data[0];            
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

    
    private function gencatalogo($source){

        $html='';
        if(!isset($source['titulopdf']) || $source['titulopdf']==null || $source['titulopdf']=='undefined')
            $filename='catalogo';
        else
            $filename=$source['titulopdf'];

        $em = $this->getDoctrine()->getManager();
        $zoompdf=$em->getRepository('gemaBundle:Configuracion')->find(1)->getZoompdf();
        if(isset($source['capas']) && $source['capas']=='on'){

            $capaimg=$source['capaName'];
            $capaimg=explode('/',$capaimg);
            // print_r($capaimg);die();

            $capaimg=$capaimg[count($capaimg)-1];
            $capaimg='pdfresources/tapas/'.$capaimg;


            $html.=$this->renderView('gemaBundle:Page:pdfCapa.html.twig', array(
                    'capaimg'=>$capaimg

                )
            );
        }



        $imgIntrodName=str_replace('_small','',$source['imgIntrodName']) ;
        $titulo=$source['titulo'];
        $subtitulo=$source['subtitulo'];
        $contacto=$source['contacto'];
        $nombre=$source['nombre'];
        $direccion=$source['direccion'];
        $telefono=$source['telefono'];
        $email=$source['email'];
        $titulopdf=$source['titulopdf'];
        $impresion = $source['impresion'];

        $band=true;
        if($titulo==''&&$subtitulo==''&& $contacto==''&&$nombre==''&&$direccion==''&&$telefono==''&&$email==''&&$titulopdf=='')
        {
            $band=false;
        }

        if($band){

            $html.=$this->renderView('gemaBundle:Page:pdfPortada.html.twig', array(
                    'imgIntrodName'=>  $imgIntrodName,
                    'titulo'=>$titulo,
                    'subtitulo'=>$subtitulo,
                    'contacto'=>$contacto,
                    'nombre'=>$nombre,
                    'direccion'=>$direccion,
                    'telefono'=>$telefono,
                    'email'=>$email,
                    'titulopdf'=>$titulopdf
                )
            );

        }




        if(isset($source['tablacontenidos']) && $source['tablacontenidos']=='on'){

            $html.=$this->tablacontenidos($source);

        }

        if( isset($source['mensajeintroducTitulo']) && $source['mensajeintroducTitulo']!='' && $source['mensajeintroducTitulo']!='undefined' && $source['mensajeintroducTitulo']!=null){

            $helper=new MyHelper();
            $img=str_replace('_small','', $helper->randomPic('pdfresources/backgroundintroductorio/'));

            $html.=$this->renderView('gemaBundle:Page:pdfMensajebienvenida.html.twig', array(
                    'image'=>  $img,
                    'titulo'=>$source['mensajeintroducTitulo'],
                    'cuerpo'=>$source['mensajeintrudCuerpo'],

                )
            );

        }    
        $pdfHelper = new PdfHelper();   
        $urlfooter = $this->generateUrl('gema_footertest');
        $pdfGenerator = $this->get('knp_snappy.pdf');
        $guid = $helper->GUID();
        $webPath=$this->get('kernel')->getRootDir().'/../web/pdfs/'.$guid .'/';
        $outputfilename=$webPath.$filename;
        $request = $this->getRequest();
        $baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();
        $myRepo = $em->getRepository('gemaBundle:Configuracion');      
        $urlvirtual=$myRepo->find(1)->getVirtualurl();
        
        $options = array(
            'margin-top'    => 4,
            'margin-right'  => 1,
            'margin-bottom' => 1,
            'margin-left'   => 1,
             //'dpi'=>1.33,
            'zoom'=>$zoompdf
        );
        $firstpdf = $pdfHelper->GeneratePdf($html,$pdfGenerator,$options,'firstpart', $webPath);

        if(isset($source['listaprecios']) && $source['listaprecios']!='' && $source['listaprecios']!='undefined' && $source['listaprecios']!=null) {

            $texto='';

            foreach($source['listtoroprocesada'] as $t){
                $texto.='<div class="row">
                 <div class="col-md-6" style="text-align: left"><span class="menuitem primd">'.$t[1].'</span></div>
              <div class="col-md-6"><span class="menuitem">$'.$t[2].'</span></div>
              </div>';


            }

            $html= $this->renderView('gemaBundle:Page:pdfListaprecios.html.twig', array(
                'texto'=>$texto

            ));
        }         

        $secondpdf = $pdfHelper->GeneratePdf($html,$pdfGenerator,$options,'secondpart', $webPath);

        //pdf detalles
        $options = array(
            'margin-top'    => 2,
            'margin-right'  => 1,
            'margin-bottom' => 16,
            'margin-left'   => 1,                  
        );  
        $torosIds=array();
        foreach($source['torosInfo'] as $t)
            $torosIds[]=$t[0];        
        $basedet = $urlvirtual == true ? DIRECTORY_SEPARATOR.'/toro/': $baseurl.DIRECTORY_SEPARATOR.'/toro/';
        $unificados=$pdfHelper->UnificadosDataFormed($torosIds,$impresion,$em,$basedet);
        $html = $this->renderView('gemaBundle:Maquetacatalogo:unificado.html.twig', array( 'unificados'=>$unificados,));        
       // print($html);die();
        $detallepart = $pdfHelper->GeneratePdf($html,$pdfGenerator,$options,'detallepart', $webPath,true,$urlfooter,$request);

        //Merge
        $pdfmerge =  new \PDFMerger;
        $pdfmerge->addPDF($firstpdf, 'all');
        $pdfmerge->addPDF($detallepart, 'all');
        $pdfmerge->addPDF($secondpdf, 'all');
        $pdfmerge->merge('file',$outputfilename.'.pdf');   
       
        $helper->DeleteFile($firstpdf);   
        $helper->DeleteFile($detallepart);  
        $helper->DeleteFile($secondpdf);   

        $path = $urlvirtual ==true ? DIRECTORY_SEPARATOR.'/pdfs/'.$guid .'/'.$filename.'.pdf': $baseurl.DIRECTORY_SEPARATOR.'/pdfs/'.$guid .'/'.$filename.'.pdf';         
        return  array(
            0=>$filename,
            1=>$path,
        );
    }




    
    /* #region  gencatalogoOld */
    private function gencatalogoOld($source)
    {

        $html='';
        if(!isset($source['titulopdf']) || $source['titulopdf']==null || $source['titulopdf']=='undefined')
            $filename='catalogo';
        else
            $filename=$source['titulopdf'];

        $em = $this->getDoctrine()->getManager();
        $zoompdf=$em->getRepository('gemaBundle:Configuracion')->find(1)->getZoompdf();
        if(isset($source['capas']) && $source['capas']=='on'){

            $capaimg=$source['capaName'];
            $capaimg=explode('/',$capaimg);
            // print_r($capaimg);die();

            $capaimg=$capaimg[count($capaimg)-1];
            $capaimg='pdfresources/tapas/'.$capaimg;


            $html.=$this->renderView('gemaBundle:Page:pdfCapa.html.twig', array(
                    'capaimg'=>$capaimg

                )
            );
        }



        $imgIntrodName=str_replace('_small','',$source['imgIntrodName']) ;
        $titulo=$source['titulo'];
        $subtitulo=$source['subtitulo'];
        $contacto=$source['contacto'];
        $nombre=$source['nombre'];
        $direccion=$source['direccion'];
        $telefono=$source['telefono'];
        $email=$source['email'];
        $titulopdf=$source['titulopdf'];
        $impresion = $source['impresion'];

        $band=true;
        if($titulo==''&&$subtitulo==''&& $contacto==''&&$nombre==''&&$direccion==''&&$telefono==''&&$email==''&&$titulopdf=='')
        {
            $band=false;
        }

        if($band){

            $html.=$this->renderView('gemaBundle:Page:pdfPortada.html.twig', array(
                    'imgIntrodName'=>  $imgIntrodName,
                    'titulo'=>$titulo,
                    'subtitulo'=>$subtitulo,
                    'contacto'=>$contacto,
                    'nombre'=>$nombre,
                    'direccion'=>$direccion,
                    'telefono'=>$telefono,
                    'email'=>$email,
                    'titulopdf'=>$titulopdf
                )
            );

        }




        if(isset($source['tablacontenidos']) && $source['tablacontenidos']=='on'){

            $html.=$this->tablacontenidos($source);

        }

        if( isset($source['mensajeintroducTitulo']) && $source['mensajeintroducTitulo']!='' && $source['mensajeintroducTitulo']!='undefined' && $source['mensajeintroducTitulo']!=null){

            $helper=new MyHelper();
            $img=str_replace('_small','', $helper->randomPic('pdfresources/backgroundintroductorio/'));

            $html.=$this->renderView('gemaBundle:Page:pdfMensajebienvenida.html.twig', array(
                    'image'=>  $img,
                    'titulo'=>$source['mensajeintroducTitulo'],
                    'cuerpo'=>$source['mensajeintrudCuerpo'],

                )
            );

        }

        if(isset($source['torosInfo'])){

            foreach($source['torosInfo'] as $t){
                $html.= $this->detallehtml($t[0],$em);
            }


        }



        if(isset($source['listaprecios']) && $source['listaprecios']!='' && $source['listaprecios']!='undefined' && $source['listaprecios']!=null) {

            $texto='';

            foreach($source['listtoroprocesada'] as $t){
                $texto.='<div class="row">
                 <div class="col-md-6" style="text-align: left"><span class="menuitem primd">'.$t[1].'</span></div>
              <div class="col-md-6"><span class="menuitem">$'.$t[2].'</span></div>
              </div>';


            }

            $html.= $this->renderView('gemaBundle:Page:pdfListaprecios.html.twig', array(
                'texto'=>$texto

            ));
        }




        $helper=new MyHelper();
        $guid=$helper->GUID();
        $webPath=$this->get('kernel')->getRootDir().'/../web/pdfs/'.$guid .'/';
        if (!file_exists($webPath)) {
            mkdir($webPath, 0777, true);
        }
        $webPath=$webPath.$filename.'.pdf';

        $pdfGenerator = $this->get('knp_snappy.pdf');
        $pdfGenerator->setTimeout(10000);





        $options = array(
            'margin-top'    => 4,
            'margin-right'  => 1,
            'margin-bottom' => 1,
            'margin-left'   => 1,
//                'dpi'=>1.33,
            'zoom'=>$zoompdf
        );
        foreach ($options as $margin => $value) {
            $pdfGenerator->setOption($margin, $value);
        }


        $pdfGenerator->generateFromHtml(
            $html,
            $webPath
        );

        $myRepo = $em->getRepository('gemaBundle:Configuracion');
        $request = $this->getRequest();
        $baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();

        $urlvirtual=$myRepo->find(1)->getVirtualurl();
        if($urlvirtual==true)
            $path=DIRECTORY_SEPARATOR.'/pdfs/'.$guid .'/'.$filename.'.pdf';
        else
            $path=$baseurl.DIRECTORY_SEPARATOR.'/pdfs/'.$guid .'/'.$filename.'.pdf';
        return  array(
            0=>$filename,
            1=>$path,
        );
    }

    /* #endregion */

    public function capatestAction($capaname){


        return $this->render('gemaBundle:Page:pdfCapa.html.twig', array(
            'capaimg'=>'pdfresources/tapas/'.$capaname ));

    }

    /**
     * Metodo para testtear la portada
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function portadaAction(){

        $imgIntrodName='pdfresources/backgroundtitulo/imgIntrod.jpg';
        $titulo='Mi titulo';
        $subtitulo='Mi subtitulo';
        $contacto='Contacto';
        $nombre='Hector';
        $direccion='Dirección';
        $telefono='78778977';
        $email='zeldax54@gmail.com';
        $titulopdf='titulo pdf';

       return $this->render('gemaBundle:Page:pdfPortada.html.twig', array(
                'imgIntrodName'=>  $imgIntrodName,
                'titulo'=>$titulo,
                'subtitulo'=>$subtitulo,
                'contacto'=>$contacto,
                'nombre'=>$nombre,
                'direccion'=>$direccion,
                'telefono'=>$telefono,
                'email'=>$email,
                'titulopdf'=>$titulopdf
            )
        );

    }

    public function tablacontenidos($source){

        $mensajebienv=$source['mensajeintroducTitulo'];
        $toros=$source['listtoroprocesada'];
        $listaprecios=$source['listaprecios'];

        $empieza=4;

        $texto='';

        if($mensajebienv!='' && $mensajebienv!='undefined' && $mensajebienv!=null){

            $empieza=1;
            $texto.=
                '<div class="row">
                 <div class="col-md-6" style="text-align: left;"><span class="menuitem primd">'.$mensajebienv.'</span></div>
              <div class="col-md-6"><span class="menuitem">'.$empieza.'</span></div>
              </div>';
        }

        foreach($toros as $t){
            $empieza++;
            $texto.='<div class="row">
                 <div class="col-md-6" style="text-align: left"><span class="menuitem primd">'.$t[1].'</span></div>
              <div class="col-md-6"><span class="menuitem">'.$empieza.'</span></div>
              </div>';
        }

        if($listaprecios!='' && $listaprecios!='undefined' && $listaprecios!=null) {
            $empieza++;

            $texto.='<div class="row">
                 <div class="col-md-6" style="text-align: left"><span class="menuitem primd">Lista de Precios</span></div>
              <div class="col-md-6"><span class="menuitem">'.$empieza.'</span></div>
              </div>';

        }


        return $this->renderView('gemaBundle:Page:pdfTablacontenidos.html.twig', array(
            'texto'=>$texto

        ));

    }



    /* #region   detallehtml*/
    private function detallehtml($id,$em,$margins=0)
    {

    $toro=$em->getRepository('gemaBundle:Toro')->find($id);

    $helper=new MyHelper();
    $img=$helper->randomPic('toro'.DIRECTORY_SEPARATOR.$toro->getGuid().'P'.DIRECTORY_SEPARATOR);

    if($img==null){
        $img=$helper->directPic('genericfiles'.DIRECTORY_SEPARATOR,'toro.png');
        $pricimgdesc='';
    }

    else{
        $vowels = array("_small","_large","_medium");
        $img=str_replace($vowels,"",$img);
        $data= explode(DIRECTORY_SEPARATOR,$img);
        $descripcionprinc=$em->getRepository('gemaBundle:MediaDescription')-> findOneBy(
            array(

                'nombre'=>$data[2],
                'folder'=>$data[0],
                'subforlder'=>$data[1]

            )

        );
        if($descripcionprinc!=null)
            $pricimgdesc=$descripcionprinc->getDescripcion();
        else
            $pricimgdesc='';
    }

    $imgfp=$this->imgFacilidadParto($helper,$toro->getFacilidadparto());
    $imgcp=$this->ConceptPlus($helper,$toro->getCP());
    $silueta=$helper->directPic('genericfiles'.DIRECTORY_SEPARATOR,$toro->getRaza()->getSilueta().'.jpg');
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


    $view='gemaBundle:Page:toropdf.html.twig';


    return $this->renderView($view, array(
            'toro'=>$toro,
            'princimg'=>$img,
            'imgfp'=>$imgfp,
            'imgcp'=>$imgcp,
            'silueta'=>$silueta,
            'tablagenetica'=>$tablasflag,
            'tabla'=>$tabla,
            'tablagennombre'=>$tablagennombre,
            'razaname'=>   $toro->getRaza()->getNombre(),
            'pricimgdesc'=>$pricimgdesc,
             'margins'=>$margins
        )
    );
}

    /* #endregion */

    /* #region  detallehtmltestAction */
    public function detallehtmltestAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $toro=$em->getRepository('gemaBundle:Toro')->find($id);

        $helper=new MyHelper();
        $img=$helper->randomPic('toro'.DIRECTORY_SEPARATOR.$toro->getGuid().'P'.DIRECTORY_SEPARATOR);

        if($img==null){
            $img=$helper->directPic('genericfiles'.DIRECTORY_SEPARATOR,'toro.png');
            $pricimgdesc='';
        }

        else{
            $vowels = array("_small","_large","_medium");
            $img=str_replace($vowels,"",$img);
            $data= explode(DIRECTORY_SEPARATOR,$img);
            $descripcionprinc=$em->getRepository('gemaBundle:MediaDescription')-> findOneBy(
                array(

                    'nombre'=>$data[2],
                    'folder'=>$data[0],
                    'subforlder'=>$data[1]

                )

            );
            if($descripcionprinc!=null)
                $pricimgdesc=$descripcionprinc->getDescripcion();
            else
                $pricimgdesc='';
        }

        $imgfp=$this->imgFacilidadParto($helper,$toro->getFacilidadparto());
        $imgcp=$this->ConceptPlus($helper,$toro->getCP());
        $silueta=$helper->directPic('genericfiles'.DIRECTORY_SEPARATOR,$toro->getRaza()->getSilueta().'.jpg');
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
        $view='gemaBundle:Page:toropdf.html.twig';
        return $this->render($view, array(
                'toro'=>$toro,
                'princimg'=>$img,
                'imgfp'=>$imgfp,
                'imgcp'=>$imgcp,
                'silueta'=>$silueta,
                'tablagenetica'=>$tablasflag,
                'tabla'=>$tabla,
                'tablagennombre'=>$tablagennombre,
                'razaname'=>   $toro->getRaza()->getNombre(),
                'pricimgdesc'=>$pricimgdesc,
                'margins'=>1


            )
        );
    }
    /* #endregion */

    /**
     * Genera una imagen jpg del html de detalle del toro
     * @return JsonResponse
     */
    public function toroimgAction(){

        try{

            $toroId=$_POST["id"];
            $em = $this->getDoctrine()->getManager();
            $repoconf=$em->getRepository('gemaBundle:Configuracion');
            $zoompdf=$repoconf->find(1)->getZoompdf();
            $urlvirtual=$repoconf->find(1)->getVirtualurl();
            $toro=$em->getRepository('gemaBundle:Toro')->find($toroId);
            $request = $this->getRequest();
            $baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();
            $helper=new MyHelper();
            $guid=$helper->GUID();
            $filename = $helper->remove_accents($toro->getApodo());
            $filename=str_replace(' ','',$filename);

            $html=$this->detallehtml($toroId,$em,1);
            $extension='png';
            $imgGenerator = $this->get('knp_snappy.image');
            $imgGenerator->setTimeout(10000);
            $imgGenerator->setDefaultExtension($extension);
            $imgGenerator->setOption('width', '1000');
            $imgGenerator->setOption('format', $extension);
//            $imgGenerator->setOption('height','1080');
            $imgGenerator->setOption('zoom',$zoompdf);
            $imgGenerator->setOption('crop-h','1080');


            $webPath=$this->get('kernel')->getRootDir().'/../web/pdfs/'.$guid .'/';
            if (!file_exists($webPath)) {
                mkdir($webPath, 0777, true);
            }
            $webPath=$webPath.$filename.'.'.$extension;
            $imgGenerator->generateFromHtml(
                $html,
                $webPath
            );
            if($urlvirtual==true)
                $path=DIRECTORY_SEPARATOR.'/pdfs/'.$guid .'/'.$filename.'.'.$extension;
            else
                $path=$baseurl.DIRECTORY_SEPARATOR.'/pdfs/'.$guid .'/'.$filename.'.'.$extension;

            return new JsonResponse(array(
                0=>'1',
                1=>$path,
                2=>$filename.'.'.$extension
            ));

        }catch(\Exception $e){

            return new JsonResponse(array(
                0=>'0',
                1=>$e->getMessage()
            ));
        }


    }
    /**
     * Genera un pdf del detalle de un toro mismo html que la foto (no es el html del detalle es uno custom; el que se usa en el catalogo)
     */
    public function singletoroPdfAction(){

        try{
            $toroId=$_POST["id"];
            $em = $this->getDoctrine()->getManager();
            $repoconf=$em->getRepository('gemaBundle:Configuracion');
            $zoompdf=$repoconf->find(1)->getZoompdf();
            $urlvirtual=$repoconf->find(1)->getVirtualurl();
            $toro=$em->getRepository('gemaBundle:Toro')->find($toroId);
            $request = $this->getRequest();
            $baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();
            $helper=new MyHelper();
            $guid=$helper->GUID();
            $filename= $helper->remove_accents($toro->getApodo());
            $filename=str_replace(' ','',$filename);

            $html=$this->detallehtml($toroId,$em);
            $pdfGenerator = $this->get('knp_snappy.pdf');
            $pdfGenerator->setTimeout(10000);

            $options = array(
                'zoom'=>$zoompdf
            );
            foreach ($options as $margin => $value) {
                $pdfGenerator->setOption($margin, $value);
            }

            $webPath=$this->get('kernel')->getRootDir().'/../web/pdfs/'.$guid .'/';
            if (!file_exists($webPath)) {
                mkdir($webPath, 0777, true);
            }
            $webPath=$webPath.$filename.'.pdf';
            $pdfGenerator->generateFromHtml(
                $html,
                $webPath
            );
            if($urlvirtual==true)
                $path=DIRECTORY_SEPARATOR.'/pdfs/'.$guid .'/'.$filename.'.pdf';
            else
                $path=$baseurl.DIRECTORY_SEPARATOR.'/pdfs/'.$guid .'/'.$filename.'.pdf';

            return new JsonResponse(array(
                0=>'1',
                1=>$path,
                2=>$filename.'.pdf'
            ));

        }catch(\Exception $e){

            return new JsonResponse(array(
                0=>'0',
                1=>$e->getMessage()
            ));
        }

    }
   
	
	function deleteDir($path) {
    // The preg_replace is necessary in order to traverse certain types of folder paths (such as /dir/[[dir2]]/dir3.abc#/)
    // The {,.}* with GLOB_BRACE is necessary to pull all hidden files (have to remove or get "Directory not empty" errors)
    $files = glob(preg_replace('/(\*|\?|\[)/', '[$1]', $path).'/{,.}*', GLOB_BRACE);
    foreach ($files as $file) {
        if ($file == $path.'/.' || $file == $path.'/..') { continue; } // skip special dir entries
        is_dir($file) ? $this->deleteDir($file) : unlink($file);
    }
    rmdir($path);
    return;
}
    public function limpiarcatalogosAction(){

        $webPath = $this->get('kernel')->getRootDir().'/../web/pdfs/';
        $webPathnewcatag = $this->get('kernel')->getRootDir().'/../web/pdfs/catalogs/';
        if (is_dir( $webPathnewcatag ) ) {
            
            $dircatalgs = new DirectoryIterator($webPathnewcatag);
            foreach ($dircatalgs as $fileinfo) {
                if ($fileinfo->isDir() && !$fileinfo->isDot()) {
                    $this->deleteDir($webPathnewcatag.DIRECTORY_SEPARATOR.$fileinfo);
                }
            }    
        } 
        if (is_dir( $webPath ) ) {
            $dir = new DirectoryIterator($webPath);
            foreach ($dir as $fileinfo) {
                if ($fileinfo->isDir() && !$fileinfo->isDot()) {
                    $this->deleteDir($webPath.DIRECTORY_SEPARATOR.$fileinfo);
                }
            }
        }
       
        
        return $this->render('gemaBundle:Default:index.html.twig', array(
            )
        );
    }

    public function limpiarcacheAction(){
    try{
        $request = $this->getRequest();
        $webPath = $this->get('kernel')->getRootDir().'/../app/cache/';
        $ruta =$request->getScheme() . '://' . $request->getHttpHost(). $this->generateUrl(
            'gemaBundle_adminhome'              
        );        
        $dir = new DirectoryIterator($webPath);
            foreach ($dir as $fileinfo) {
                if ($fileinfo->isDir() && !$fileinfo->isDot()) {
                    if(strpos($fileinfo,'jms_serializer')===false)             
                      $this->deleteDir($webPath.DIRECTORY_SEPARATOR.$fileinfo);
                }
            }
            
          
            
            print('<html>
            <head>           
            </head>
             <a href="'.$ruta.'" id="mylink"> Cache eliminada click para volver al admin</a>
            </html>');
           die();    
       
    }
    catch(\Exception $e ){
        print($e->getMessage());die();

        return $this->render('gemaBundle:Default:index.html.twig', array(
            )
        );
    }

       
    }

      /* #region  Axuliares */
      function getDesc($data){
        $em = $this->getDoctrine()->getManager();
        $descripcionprinc=$em->getRepository('gemaBundle:MediaDescription')-> findOneBy(
            array(
                'nombre'=>str_replace('_small','',$data[2]),
                'folder'=>$data[0],
                'subforlder'=>$data[1]
            )
        );
        if($descripcionprinc==null)
            return '';
        return $pricimgdesc=$descripcionprinc->getDescripcion();
    }

    function ConceptPlus($helper,$cp){
        if($cp==false)
            return null;
        return $helper->directPic('genericfiles'.DIRECTORY_SEPARATOR,'conceptPlus.png');
    }

    function Nacionalidad($helper,$nacionalidad)
    {

        if($nacionalidad==null)
            return null;
        $nacionalidad=str_replace(' ', '', $nacionalidad);

        return $helper->directPic('genericfiles'.DIRECTORY_SEPARATOR,$nacionalidad.'.jpg');
    }

    function imgFacilidadParto($helper,$facilidad){
        if($facilidad==null)
            return null;
        return $helper->directPic('genericfiles'.DIRECTORY_SEPARATOR,'fp_'.$facilidad.'.png');
    }

 
    /* #endregion */



}

