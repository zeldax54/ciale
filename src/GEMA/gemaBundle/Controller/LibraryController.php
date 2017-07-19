<?php

namespace GEMA\gemaBundle\Controller;

use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Console\Helper\Helper;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use GEMA\gemaBundle\Helpers\MyHelper;


/**
 * Library controller.
 *
 */
class LibraryController extends Controller
{

    public static $imagenes = array(0 => 'jpg', 1 => 'png', 2 => 'gif', 3 => 'jpeg',4=>'JPG');
    public static $excels = array(0=>'xls',1=>'xlsx');
    public static $words = array(0=>'doc',1=>'docx',2=>'pdf');
    public static $videos = array(0=>'mp4',1=>'avi',2=>'flv',3=>'webm');

    const  mediaFolder='media'.DIRECTORY_SEPARATOR;

    public function LibraryPageAction($param,$folder)
    {

        if ($folder == "library") {
            $folderFull = $folder . DIRECTORY_SEPARATOR;
            $retid = $folder;
        } else {
            $folderFull = $folder . DIRECTORY_SEPARATOR . $param . DIRECTORY_SEPARATOR;
            $retid = $folder . $param;
        }

        $serverName = $_SERVER['SERVER_NAME'];
        $webPath = $this->get('kernel')->getRootDir() . '/../web/' . $folderFull;
        $helper=new MyHelper();
        $helper->CheckCreate($webPath);
        $ficheros = array_diff(scandir($webPath), array('.', '..'));
        $final=array();
        if ($folder == 'library')
        {
            $alldata=array(
                0=>'raza',
                1=>'boletines',
                2=>'toro',
                3=>'mediainpage',
                4=>'staff');

            foreach($alldata as $data)
            {
                $path= $this->get('kernel')->getRootDir() . '/../web/'.$data;
                $extrafolders=array_diff(scandir($path), array('.', '..'));
                foreach($extrafolders as $f)
                {
                    $files=array_diff(scandir($path.'/'.$f), array('.', '..'));
                    foreach($files as $ff)
                    {

                        $final[]=array(
                            0=>$ff,
                            1=>$this->FileExt($ff,$data.DIRECTORY_SEPARATOR.$f.DIRECTORY_SEPARATOR,self::mediaFolder),
                            3=>$data.$f.$ff,
                            4=>$data,
                            5=>$f,
                            6=>$data.DIRECTORY_SEPARATOR.$f.DIRECTORY_SEPARATOR.$ff

                        );
                    }
                }
            }


        }


        foreach($ficheros as $fichero)
        {

            $final[]=array(
                0=>$fichero,
                1=>$this->FileExt($fichero,$folderFull,self::mediaFolder),
                3=>$retid.$fichero,
                4=>$folder,
                5=>$param,
                6=>$folderFull.$fichero

            );
        }

        return $this->render('gemaBundle:Library:page.html.twig', array(
                'archivos'=>$final,
                 'serverName'=>$serverName,
                'folder'=>$folder,
                'guidParam'=>$param
            )
        );
    }


    private  function FileExt($string,$libraryFolder,$mediaFolder)
    {
        $split  = explode('.',$string);
        if(isset($split[count($split)-1]))
        {
            $ext=$split[count($split)-1];
            if(array_search($ext,self::$imagenes)!==false)
                return $libraryFolder.$string;
            if(array_search($ext,self::$excels)!==false)
                return $mediaFolder.'spreadsheet.png';
            if(array_search($ext,self::$words)!==false)
                return $mediaFolder.'document.png';
            if(array_search($ext,self::$videos)!==false)
                return $mediaFolder.'video.png';
            return $mediaFolder.'default.png';
        }
            return $mediaFolder.'default.png';
    }


    public function FilesUrlAction(){
        exit();
    //  return new JsonResponse( $this->getRequest()->getBasePath().'/library/');
        die();
        new Response();
    }

    public function CopyFileAction($param,$guidParam){
        if($param=='library')
        {
            $webPath = $this->get('kernel')->getRootDir().'/../web/library/';
            $folderFull=$param.DIRECTORY_SEPARATOR;
            $retid=$param;

        }

        else{
            $webPath=$this->get('kernel')->getRootDir().'/../web/'.$param.'/'.$guidParam.'/';
            $folderFull=$param.DIRECTORY_SEPARATOR.$guidParam.DIRECTORY_SEPARATOR;
            $retid=$param.$guidParam;
            if (!file_exists($webPath)) {
                mkdir($webPath, 0777, true);
            }

        }
        $fileName=$_FILES['file']['name'];
        if (move_uploaded_file($_FILES['file']['tmp_name'], $webPath.$fileName)) {
            $final=array(
                0=>$this->getRequest()->getBasePath().'/'.$folderFull.$_FILES['file']['name'],
                1=>$this->getRequest()->getBasePath().'/'.$this->FileExt($_FILES['file']['name'],$folderFull,self::mediaFolder),
                2=>true,
                3=>$_FILES['file']['name'],
                4=>$retid.$fileName,
                5=>$param,
                6=>$guidParam

            );
            return new JsonResponse($final);
        } else {
            $final=array(
                0=>$_FILES['file']['name'],
                1=>"Error al cargar el Archivo.\n".$_FILES['file']['error'],
                2=>false,

            );
            return new JsonResponse($final);
        }
    }



    public function DeleteFileAction($folder,$guidParam,$filename)
    {
        if ($folder == 'library')
        {
            $ruta=$folder.'/'.$filename;
            $retid=$folder.$filename;

        }
        else{
            $ruta=$folder.'/'.$guidParam.'/'.$filename;
            $retid=$folder.$guidParam.$filename;
        }

            $webPath = $this->get('kernel')->getRootDir().'/../web/'.$ruta;



            if(!file_exists($webPath)){
                $final=array(
                    0=>false,
                    1=>$filename
                );return new JsonResponse($final);
            }

            $final=array(
                0=>unlink($webPath),
                1=>$filename,
                2=>$retid,
                3=>1
            );
            return new JsonResponse($final);


    }


//    public function FindFileAction($texto)
//    {
//
//            $webPath = $this->get('kernel')->getRootDir() . '/../web/library';
//            $ficheros = array_diff(scandir($webPath), array('.', '..'));
//            $final=array();
//
//            if($texto!="-1")
//            foreach($ficheros as $fichero)
//            {
//                if(strpos(strtolower($fichero),strtolower($texto))!==false)
//                {
//                    $final[]= array( 0=>$fichero,
//                        1=>$this->FileExt($fichero,self::libraryFolder,self::mediaFolder));
//                }
//            }
//            else
//                foreach($ficheros as $fichero)
//            {
//                $final[]=array(
//                    0=>$fichero,
//                    1=>$this->FileExt($fichero,self::libraryFolder,self::mediaFolder)
//                );
//            }
//
//            $archivosJSON = array();
//            foreach ($final as $f ) {
//                $archivosJSON[] = array(
//                    'nombre' => $f[0],
//                    'imagen' => $f[1]
//                );
//            }
//
//            return new JsonResponse($archivosJSON);
//
//
//    }

}
