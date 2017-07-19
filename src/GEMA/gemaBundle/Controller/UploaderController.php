<?php

namespace GEMA\gemaBundle\Controller;


use GEMA\gemaBundle\Entity\Toro;
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


                $objReader = PHPExcel_IOFactory::createReader('Excel2007');
                if ($objReader->canRead($rutafinal))
                    $objPHPExcel = $objReader->load($rutafinal);
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

                foreach ($data as $toro) {
                    if ($iterator > 1) {
                        $torosUniquesNames[] = $toro[0];
                        $toroF = $toroRepo->findOneByNombreinterno($toro[0]);
                        $toroF=$this->UpdateCreateToro($raza, $actualizarToros, $toroF, $toro, $mapa,$tablaSelected);

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
                                $this->get("gema.utiles")->traza('Toro eliminado por proceso de carga desde excel apodo:'.$inbdtoro->getApodo());
                                $helper->RemoveFolder($webPath);
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

    function UpdateCreateToro($raza, $flag, $toro, $row, $mapa,$tablaSelected)
    {
        try {
            $isnnew=false;
            if ($flag == 1 && $toro != null)
                return $toro;
            if ($toro == null)
            {
                $isnnew=true;
                $toro = new Toro();
            }





            $toro->setRaza($raza);
            $toro->setNombre($row[$this->getMapaPos('nombre', $mapa)]);
            $toro->setNacionalidad($row[$this->getMapaPos('origen', $mapa)]);
            $toro->setNombreinterno($row[$this->getMapaPos('nombreinterno', $mapa)]);
            if($row[$this->getMapaPos('apodo', $mapa)]=='VERDADERO' )
                $toro->setApodo('VERDADERO');
            else
            $toro->setApodo($row[$this->getMapaPos('apodo', $mapa)]);
            $toro->setCriador($row[$this->getMapaPos('criador', $mapa)]);
            $toro->setPropietario($row[$this->getMapaPos('propietario', $mapa)]);
            $toro->setDescripcion($row[$this->getMapaPos('caracteristicas', $mapa)]);
            $toro->setNuevo($this->convertBool($row[$this->getMapaPos('nuevo', $mapa)]));

            $toro->setPadre($row[$this->getMapaPos('padre', $mapa)]);
            $toro->setMadre($row[$this->getMapaPos('madre', $mapa)]);
            $toro->setPadrepadre($row[$this->getMapaPos('padrepadre', $mapa)]);
            $toro->setMadrepadre($row[$this->getMapaPos('madrepadre', $mapa)]);
            $toro->setPadremadre($row[$this->getMapaPos('padremadre', $mapa)]);
            $toro->setMadremadre($row[$this->getMapaPos('madremadre', $mapa)]);
            $toro->setPadrepadrepadre($row[$this->getMapaPos('padrepadrepadre', $mapa)]);
            $toro->setMadrepadrepadre($row[$this->getMapaPos('madrepadrepadre', $mapa)]);
            $toro->setPadremadrepadre($row[$this->getMapaPos('padremadrepadre', $mapa)]);
            $toro->setMadremadrepadre($row[$this->getMapaPos('madremadrepadre', $mapa)]);
            $toro->setPadrepadremadre($row[$this->getMapaPos('padrepadremadre', $mapa)]);
            $toro->setMadrepadremadre($row[$this->getMapaPos('madrepadremadre', $mapa)]);
            $toro->setPadremadremadre($row[$this->getMapaPos('padremadremadre', $mapa)]);
            $toro->setMadremadremadre($row[$this->getMapaPos('madremadremadre', $mapa)]);

            $toro->setEvaluaciongenetica($row[$this->getMapaPos('evaluaciongenetica', $mapa)]);
            $toro->setLineagenetica($row[$this->getMapaPos('lineagenetica', $mapa)]);
            $toro->setFacilidadparto($row[$this->getMapaPos('facilidadparto', $mapa)]);
            $toro->setCP($this->convertCP($row[$this->getMapaPos('CP', $mapa)]));
            $toro->setRp($row[$this->getMapaPos('rp', $mapa)]);
            $toro->setHBA($row[$this->getMapaPos('HBA', $mapa)]);
            $toro->setSenasa($row[$this->getMapaPos('senasa', $mapa)]);

            //   print_r(\DateTime::createFromFormat('d-m-Y',str_replace("/", "-", $row[$this->getMapaPos('fechanacimiento', $mapa)]) ));die();

            if($row[$this->getMapaPos('fechanacimiento', $mapa)]==null)
                $toro->setFechanacimiento(null);
            else{
                $fecha=date_create_from_format('d-m-Y',str_replace("/", "-", $row[$this->getMapaPos('fechanacimiento', $mapa)]) );
                $toro->setFechanacimiento($fecha);
            }
            $toro->setADN($row[$this->getMapaPos('ADN', $mapa)]);
            $toro->setCircunferenciaescrotal($row[$this->getMapaPos('circunferenciaescrotal', $mapa)]);
            $toro->setLargogrupa($row[$this->getMapaPos('largogrupa', $mapa)]);
            $toro->setAnchogrupa($row[$this->getMapaPos('anchogrupa', $mapa)]);
            $toro->setAltogrupa($row[$this->getMapaPos('altogrupa', $mapa)]);
            $toro->setAltogrupa($row[$this->getMapaPos('altogrupa', $mapa)]);
            $toro->setLargocorporal($row[$this->getMapaPos('largocorporal', $mapa)]);
            $toro->setPeso($row[$this->getMapaPos('peso', $mapa)]);

            $toro->setPn1($row[$this->getMapaPos('pn1', $mapa)]);
            $toro->setP205d($row[$this->getMapaPos('p205d', $mapa)]);
            $toro->setP365d($row[$this->getMapaPos('p365d', $mapa)]);
            $toro->setP550d($row[$this->getMapaPos('p550d', $mapa)]);
            $toro->setP550d($row[$this->getMapaPos('p550d', $mapa)]);
            $toro->setPrecio($row[$this->getMapaPos('precio', $mapa)]);
            $toro->setTipotablaselected($tablaSelected);
            $toro->setPublico(1);
           if($isnnew==true)           {
               $helper=new MyHelper();
               $toro->setGuid($helper->GUID());
           }

            $tablas=$raza->getTipotabla()->getTablas();
            $jsonTables='{';
            foreach($tablas as $tabla){
                $jsonTables.='"'.$tabla->getNombre().'":[';
                $tablabody=$tabla->getTablabody();
                $tabladatos = $tabla->getTabladatos();
                foreach($tablabody as $body) {
                    $jsonTables .= '{';
                    $jsonTables .= '"rowhead":"' . $body->getRowname() . '",';

                    foreach ($tabladatos as $dato)
                    {
                        $jsonTables.='"'.$dato->getNombre().'":"'.$row[$dato->getPosinExcel()+$body->getLejania()].'",';
                    }
                    $jsonTables=substr($jsonTables,0,strlen($jsonTables)-1);
                    $jsonTables.='},';
                }
                $jsonTables=substr($jsonTables,0,strlen($jsonTables)-1);
                $jsonTables.='],';
            }
            $jsonTables=substr($jsonTables,0,strlen($jsonTables)-1);
            $jsonTables.='}';

            $toro->setTablagenetica($jsonTables);
            return $toro;

        }
        catch (\Exception $e)
        {

            return $e->getMessage();
        }

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

    function getMapaPos($nombre,$mapa){

        foreach($mapa as $m)
        {
            if($m->getNombre()==$nombre)
                return $m->getPosinExcel();
        }
        return -1;
    }


    function findTablasByRazaAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $raza = $em->getRepository("gemaBundle:Raza")->find($id);
        $tablas=$raza->getTipoTabla()->getTablas();

        $tablasJSON = array();
        foreach ($tablas as $t ) {
            $tablasJSON[] = array(
                'id' => $t->getId(),
                'nombre' => $t->getNombre()
            );
        }

        return new JsonResponse($tablasJSON);
    }

}
