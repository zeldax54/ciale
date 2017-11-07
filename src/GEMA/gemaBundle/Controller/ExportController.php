<?php
/**
 * Created by PhpStorm.
 * User: AK0
 * Date: 08/07/2017
 * Time: 15:17
 */

namespace GEMA\gemaBundle\Controller;
use GEMA\gemaBundle\Entity\Tabla;
use GEMA\gemaBundle\Entity\TablaDatos;
use PHPExcel_Cell_DataType;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use GEMA\gemaBundle\Helpers\MyHelper;
use PHPExcel;






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


  function excelExportAction($toros,$alltoros){


    $torosid=explode('|',$toros);

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
          $nombexcel=$raza->getfather()->getNombre();

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

          $nombexcel=$raza->getNombre();
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
        //  if($toro->getId()==412)

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

          $rowsiter++;
      }

    header('Content-Type: application/vnd.ms-excel; charset=UTF-8'); //mime type
    header('Content-Disposition: attachment;filename="'.$nombexcel.'.xls"'); //tell browser what's the file name
    header('Cache-Control: max-age=0'); //no cache
    $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');

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
    //  print_r($ids);die();
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



     public function pdfgenerateAction(){
         try
         {

             set_time_limit(0);

             $torosIds=$_POST["ids"];
             $filename=$_POST["filename"];
             $html='';
             $renderscriptcss=1;
             foreach($torosIds as $id){

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

                 if($renderscriptcss==1)
                     $view='gemaBundle:Page:toropdf.html.twig';
                 else
                     $view='gemaBundle:Page:toropdfnocss.html.twig';

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
                         'renderscriptcss'=>$renderscriptcss

                     )
                 );
                 $renderscriptcss=0;

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

             return new JsonResponse(array(
                 0=>'1',
                 1=>$path
             ));

         }catch (\Exception $e){

             return new JsonResponse(array(
                 0=>0,
                 1=>$e->getMessage()
             ));
         }





     }

    public function  toropdfAction($toroid){

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

    //Catalogo

    public function catalogbaseinfoAction(){

        $helper=new MyHelper();
        $em = $this->getDoctrine()->getManager();
        $myRepo = $em->getRepository('gemaBundle:Configuracion');
        $request = $this->getRequest();
        $baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();

        $miniaturacrarpdf=$helper->randomPic('pdfresources/miniaturacrearpdf/',true);
        $miniaturaimprimirtoros=$helper->randomPic('pdfresources/miniaturaimprimirtoros/',true);

        $urlvirtual=$myRepo->find(1)->getVirtualurl();
        if($urlvirtual==true){
            $miniaturacrarpdf= DIRECTORY_SEPARATOR.$miniaturacrarpdf;
            $miniaturaimprimirtoros= DIRECTORY_SEPARATOR.$miniaturaimprimirtoros;
        }
        else{
            $miniaturacrarpdf=$baseurl.DIRECTORY_SEPARATOR.$miniaturacrarpdf;
            $miniaturaimprimirtoros=$baseurl.DIRECTORY_SEPARATOR.$miniaturaimprimirtoros;
        }


        $datos[]=array(
            'miniaturacrarpdf'=>$miniaturacrarpdf,
            'miniaturaimprimirtoros'=>$miniaturaimprimirtoros
        );
        return new JsonResponse(
            $datos
        );

    }


}

