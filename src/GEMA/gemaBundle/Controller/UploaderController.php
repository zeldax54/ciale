<?php

namespace GEMA\gemaBundle\Controller;


use GEMA\gemaBundle\Entity\Toro;
use PHPExcel_Shared_Date;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use PHPExcel;
use PHPExcel_IOFactory;
use GEMA\gemaBundle\Helpers\MyHelper;


/**
 * Uploader controller.
 *
 */
class UploaderController extends Controller
{

    /**
     * Lists all Boletin entities.
     * @param Request $request
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function uploadAction(Request $request)
    {
        try {
            $actualizarToros = $request->request->get('torosFormAutoCargaActualizarTorosExistentes');
//        0 Actualizar y Crear
//        1 Solo Crear

            $cargarToros = $request->request->get('torosFormAutoCargaTorosQueNoExisten');
//        0 Omitor Toros que No esten Presentes
//        1 Desactivar los No Presentes
//        2 Borrar los No Presentes

            //Tabla Seleccionada
            $tablaSelected=$request->request->get('tablaSelected');

            $razaId = $request->request->get('razasselect');
            $helper=new MyHelper();


            $excelName = $_FILES['torosFormAutoCargaExcel']['name'];
            $rutafinal = $this->get('kernel')->getRootDir() . '/../web/excelsfiles/' . $excelName;

            if (move_uploaded_file($_FILES['torosFormAutoCargaExcel']['tmp_name'], $rutafinal)
                && $_FILES['torosFormAutoCargaExcel']['error'] == 0
            ) {

                $helper=new MyHelper();
               $extexcel= $helper->SaberExt($excelName = $_FILES['torosFormAutoCargaExcel']['name']);
              if($extexcel=='xls'){
                  $objReader=PHPExcel_IOFactory::createReader('Excel5');
              }else
                $objReader = PHPExcel_IOFactory::createReader('Excel2007');
                if ($objReader->canRead($rutafinal))
                    $objPHPExcel = $objReader->load($rutafinal);
                else{
                    return new JsonResponse(
                        "No se puede leer el fichero"
                    );
                }
                $hoja = $objPHPExcel->getActiveSheet();
                $maxCell = $hoja->getHighestRowAndColumn();
                $data = $hoja->rangeToArray('A1:' . $maxCell['column'] . $maxCell['row']);
//                $data = array_map('array_filter', $data);
//                $data = array_filter($data);

                $em = $this->getDoctrine()->getManager();
                $toroRepo = $em->getRepository("gemaBundle:Toro");
                $razaRepo = $em->getRepository("gemaBundle:Raza");
                $raza = $razaRepo->find($razaId);
                $mapa = $raza->getMapa()->getMapadatos();


                $torosUniquesNames = array();
                $iterator = 0;
                $rowhead=$data[1];
                $rowheadtabla=$data[0];

                foreach ($data as $toro) {
                    if ($iterator > 1) {
                   $nombreinterno=$toro[$this->getMapaPos('nombreinterno', $mapa,$rowhead)];
                   $torosUniquesNames[] = $nombreinterno;
                            $toroF = $toroRepo->findOneByNombreinterno($nombreinterno);
                            $toroF=$this->UpdateCreateToro($raza, $actualizarToros, $toroF, $toro, $mapa,$tablaSelected,$rowhead,$rowheadtabla,$hoja,$iterator);
                           if(is_string($toroF)){
                               return new JsonResponse($toroF);
                           }
                            $em->persist($toroF);

                    }
                    $iterator++;
                }
                //Depurando opcion2
                if($cargarToros!=0)
                {
                    $allToros=$toroRepo->findBy(array('raza' => $razaId));
                    foreach($allToros as $inbdtoro)
                    {
                        $band=false;
                        foreach($torosUniquesNames as $unique)
                            if($inbdtoro->getNombreinterno()==$unique)
                            { $band=true; break;
                            }
                        if($band==false)
                        {
                             if($cargarToros==1)
                                 $inbdtoro->setPublico(0);
                            if($cargarToros==2)
                            {
                                $webPath = $this->get('kernel')->getRootDir().'/../web/toro/'.$inbdtoro->getGuid();
                                $webPath2 = $this->get('kernel')->getRootDir().'/../web/toro/'.$inbdtoro->getGuid().'P';
                                $this->get("gema.utiles")->traza('Toro eliminado por proceso de carga desde excel apodo:'.$inbdtoro->getApodo());
                                $helper->RemoveFolder($webPath);
                                $helper->RemoveFolder($webPath2);
                                foreach($inbdtoro->getYoutubes() as $y){
                                    $em->remove($y);
                                }
                                $em->remove($inbdtoro);
                            }

                        }
                    }
                }
                $em->flush();
                return new JsonResponse('Carga exitosa!');

            } else {
                return new JsonResponse('Error cargando archivo Excel.Pruebe a recargar la pagina.');
            }
        } catch (\Exception $e) {

            return new JsonResponse('Error!! Revise los datos del excel.'.$e->getMessage());
        }

    }

    function UpdateCreateToro($raza, $flag, $toro, $row, $mapa,$tablaSelected,$rowhead,$rowheadtabla,$hoja,$iterator)
    {
        try {
            $isnnew=false;
            if ($flag == 1 && $toro != null)
                return $toro;
            if ($toro == null)
            {
                $isnnew=true;
                $toro = new Toro();
                $toro->setPublico(1);
            }
            $toro->setRaza($raza);
            $toro->setNombre($row[$this->getMapaPos('nombre', $mapa,$rowhead)]);
            $toro->setNacionalidad($row[$this->getMapaPos('nacionalidad', $mapa,$rowhead)]);
            $toro->setNombreinterno($row[$this->getMapaPos('nombreinterno', $mapa,$rowhead)]);
            if($row[$this->getMapaPos('apodo', $mapa,$rowhead)]=='VERDADERO' )
                $toro->setApodo('VERDADERO');
            else
            $toro->setApodo($row[$this->getMapaPos('apodo', $mapa,$rowhead)]);
            $toro->setCriador($row[$this->getMapaPos('criador', $mapa,$rowhead)]);
            $toro->setPropietario($row[$this->getMapaPos('propietario', $mapa,$rowhead)]);
            $toro->setDescripcion($row[$this->getMapaPos('descripcion', $mapa,$rowhead)]);
            $toro->setNuevo($this->convertBool($row[$this->getMapaPos('nuevo', $mapa,$rowhead)]));

            $toro->setPadre($row[$this->getMapaPos('padre', $mapa,$rowhead)]);
            $toro->setMadre($row[$this->getMapaPos('madre', $mapa,$rowhead)]);
            $toro->setPadrepadre($row[$this->getMapaPos('padrepadre', $mapa,$rowhead)]);
            $toro->setMadrepadre($row[$this->getMapaPos('madrepadre', $mapa,$rowhead)]);
            $toro->setPadremadre($row[$this->getMapaPos('padremadre', $mapa,$rowhead)]);
            $toro->setMadremadre($row[$this->getMapaPos('madremadre', $mapa,$rowhead)]);
            $toro->setPadrepadrepadre($row[$this->getMapaPos('padrepadrepadre', $mapa,$rowhead)]);
            $toro->setMadrepadrepadre($row[$this->getMapaPos('madrepadrepadre', $mapa,$rowhead)]);
            $toro->setPadremadrepadre($row[$this->getMapaPos('padremadrepadre', $mapa,$rowhead)]);
            $toro->setMadremadrepadre($row[$this->getMapaPos('madremadrepadre', $mapa,$rowhead)]);
            $toro->setPadrepadremadre($row[$this->getMapaPos('padrepadremadre', $mapa,$rowhead)]);
            $toro->setMadrepadremadre($row[$this->getMapaPos('madrepadremadre', $mapa,$rowhead)]);
            $toro->setPadremadremadre($row[$this->getMapaPos('padremadremadre', $mapa,$rowhead)]);
            $toro->setMadremadremadre($row[$this->getMapaPos('madremadremadre', $mapa,$rowhead)]);

            $toro->setEvaluaciongenetica($row[$this->getMapaPos('evaluaciongenetica', $mapa,$rowhead)]);
            $toro->setLineagenetica($row[$this->getMapaPos('lineagenetica', $mapa,$rowhead)]);
            $toro->setFacilidadparto($row[$this->getMapaPos('facilidadparto', $mapa,$rowhead)]);
            $toro->setCP($this->convertCP($row[$this->getMapaPos('CP', $mapa,$rowhead)]));
            $toro->setRp($row[$this->getMapaPos('rp', $mapa,$rowhead)]);
            $toro->setHBA($row[$this->getMapaPos('HBA', $mapa,$rowhead)]);
            $toro->setSenasa($row[$this->getMapaPos('senasa', $mapa,$rowhead)]);

            //   print_r(\DateTime::createFromFormat('d-m-Y',str_replace("/", "-", $row[$this->getMapaPos('fechanacimiento', $mapa)]) ));die();

        //    print_r($row[$this->getMapaPos('fechanacimiento', $mapa,$rowhead)]);die();
            if($row[$this->getMapaPos('fechanacimiento', $mapa,$rowhead)]==null || $row[$this->getMapaPos('fechanacimiento', $mapa,$rowhead)]=='')
                $toro->setFechanacimiento(null);
            else{
                $col=$this->getMapaPos('fechanacimiento', $mapa,$rowhead);
                $valor=$hoja->getCellByColumnAndRow($col, $iterator+1)->getValue();
                      if(is_numeric($valor))
                          $valor=date('d-m-Y',PHPExcel_Shared_Date::ExcelToPHP(str_replace("/","-",$valor)));
                $fecha=date_create_from_format('d-m-Y',str_replace("/", "-", $valor) );
                $toro->setFechanacimiento($fecha);
            }
            $toro->setADN($row[$this->getMapaPos('ADN', $mapa,$rowhead)]);
            $toro->setCircunferenciaescrotal($row[$this->getMapaPos('circunferenciaescrotal', $mapa,$rowhead)]);
            $toro->setLargogrupa($row[$this->getMapaPos('largogrupa', $mapa,$rowhead)]);
            $toro->setAnchogrupa($row[$this->getMapaPos('anchogrupa', $mapa,$rowhead)]);
            $toro->setAltogrupa($row[$this->getMapaPos('altogrupa', $mapa,$rowhead)]);
            $toro->setAltogrupa($row[$this->getMapaPos('altogrupa', $mapa,$rowhead)]);
            $toro->setLargocorporal($row[$this->getMapaPos('largocorporal', $mapa,$rowhead)]);
            $toro->setPeso($row[$this->getMapaPos('peso', $mapa,$rowhead)]);

            $toro->setPn1($row[$this->getMapaPos('pn1', $mapa,$rowhead)]);
            $toro->setP205d($row[$this->getMapaPos('p205d', $mapa,$rowhead)]);
            $toro->setP365d($row[$this->getMapaPos('p365d', $mapa,$rowhead)]);
            $toro->setP550d($row[$this->getMapaPos('p550d', $mapa,$rowhead)]);
            $toro->setP550d($row[$this->getMapaPos('p550d', $mapa,$rowhead)]);
            $toro->setPrecio($row[$this->getMapaPos('precio', $mapa,$rowhead)]);
            $toro->setPruebapelaje($row[$this->getMapaPos('pruebapelaje', $mapa,$rowhead)]);


           if($isnnew==true)           {
               $helper=new MyHelper();
               $toro->setGuid($helper->GUID());
           }

            if($raza->getTablasmanual()==true){
                $toro->setNombreraza($row[$this->getMapaPos('nombreraza', $mapa,$rowhead)]);
            }

            if($raza->getTablasmanual()==false){
                $toro->setTipotablaselected($tablaSelected);
                $tablas=$raza->getTipotabla()->getTablas();

                $jsonTables='{';

                foreach($tablas as $tabla){
                    $temptable='"'.$tabla->getNombre().'":[';
                    $tablabody=$tabla->getTablabody();
                    $tabladatos = $tabla->getTabladatos();
                    $tablastartIndex=$this->getTablaStartPos($tabla->getNombre(),$rowheadtabla);
                    foreach($tablabody as $body) {

                        $temptable .= '{';
                        $temptable .= '"rowhead":"' . $body->getRowname() . '",';

                        foreach ($tabladatos as $dato)
                        {

                            $temptable.='"'.$dato->getNombre().'":"'.$row[$this->getTablaPos($dato->getNombre(),$rowhead,$tablastartIndex)+$body->getLejania()].'",';
                        }
                        $temptable=substr($temptable,0,strlen($temptable)-1);
                        $temptable.='},';
                    }
                    $temptable=substr($temptable,0,strlen($temptable)-1);
                    $temptable.=']';
                    $checktable='{'.$temptable.'}';
                    $checkarray=json_decode($checktable,true);
                //    print_r($checkarray);
                    if(!$this->checknullarray($checkarray[$tabla->getNombre()])){
                        $jsonTables.=$temptable.',';
                    }
                }
                $jsonTables=substr($jsonTables,0,strlen($jsonTables)-1);
                $jsonTables.='}';
                $toro->setTablagenetica($jsonTables);
            }
            return $toro;

        }
        catch (\Exception $e)
        {

            return $e->getMessage();
        }

    }

    function checknullarray($array){

        foreach($array as $a)
        {
            foreach($a as $key=>$b){

                if($key!='rowhead'){
                    if($b!=null && $b!='')
                        return false;
                }
            }
        }
        return true;
    }

    function convertCP($value)
    {
        $value=strtolower($value);
        if($value=='cp' ||  $value=='si')
            return 1;
        return 0;
    }

    function convertBool($value){
        $value=strtolower($value);
        if($value=='si')
            return 1;
        return 0;
    }

    function getMapaPos($nombre,$mapa,$filacab){
        $inExcelNombre='';
           foreach($mapa as $m){
          if(mb_strtolower($m->getNombre())===mb_strtolower($nombre)){
                   $inExcelNombre=$m->getComentario();
                  break;
               }
        }

        for($i=0;$i<count($filacab);$i++)
            if(mb_strtolower($filacab[$i])===mb_strtolower($inExcelNombre))
               return $i;
        return -1;
    }

    function getTablaPos($nombre,$filacab,$tablastartIndex){

        for($i=$tablastartIndex;$i<count($filacab);$i++){

            if(mb_strtolower($filacab[$i])==mb_strtolower($nombre)){
                return $i;
            }
        }

        return -1;
    }

    function getTablaStartPos($tablaname,$headtabla){

        foreach($headtabla as $key=>$head){
            if( mb_strtolower($head)==mb_strtolower($tablaname)){
               return $key;
            }
        }
       return -1;
    }



    function findTablasByRazaAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $raza = $em->getRepository("gemaBundle:Raza")->find($id);
        if($raza->getTipoTabla()==null){

            $arr[]=array(
                'id'=>'',
                 'nombre'=>''
            );
            return new JsonResponse($arr);

        }

        $tablas=$raza->getTipoTabla()->getTablas();

        $tablasJSON = array();
        foreach ($tablas as $t ) {
            $tablasJSON[] = array(
                'id' => $t->getId(),
                'nombre' => $t->getNombreresumido()
            );
        }

        return new JsonResponse($tablasJSON);
    }

}
