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


  function excelExportAction($toros){

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
      }
      $this->SetBold($objPHPExcel,$letras,2);
      $this->Colorear($objPHPExcel,$letras,1);

      $tablas=$raza->getTipotabla()->getTablas();
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
        //  print_r($toroarr);die();
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
                  $objPHPExcel->getActiveSheet()->SetCellValue($letra.$rowsiter,$valor);
                  $this->centerCell($objPHPExcel,$letra,$rowsiter);
              }

               $iter++;

          }
          $tablasdata=json_decode($toroarr['tablagenetica'],true);
        //  print_r($tablasdata);die();
          foreach($tablas as $t){
              foreach($t->getTablaDatos() as $d){
                  $tabd=$tablasdata[$t->getNombre()];
                  foreach($t->getTablaBody() as $b){
                    $myrow=$this->findInTab($b->getRowName(),$tabd);
                     $value=$myrow[$d->getNombre()];
                      $letra=$this->getNext($iter);

                      $objPHPExcel->getActiveSheet()->SetCellValue($letra.$rowsiter,$value);
                      $this->centerCell($objPHPExcel,$letra,$rowsiter);
                      $iter++;
                  }
            //    print_r($tabd);die();

              }

           }

          $rowsiter++;
      }

    header('Content-Type: application/vnd.ms-excel; charset=UTF-8'); //mime type
    header('Content-Disposition: attachment;filename="'.$raza->getNombre().'.xls"'); //tell browser what's the file name
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


}