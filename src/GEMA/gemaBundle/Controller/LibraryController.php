<?php

namespace GEMA\gemaBundle\Controller;

use GEMA\gemaBundle\Entity\MediaDescription;
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
                1=>'boletin',
                2=>'toro',
                3=>'mediainpage',
                4=>'staff',
                5=>'archivos',
                6=>'productoprograma');

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

    private function SaberExt($filename){
        $split  = explode('.',$filename);
        return $split[count($split)-1];
    }

    private function isImg($ext){
        if(array_search($ext,self::$imagenes)!==false)
            return true;
        return false;
    }


    public function FilesUrlAction(){
        exit();
    //  return new JsonResponse( $this->getRequest()->getBasePath().'/library/');
        die();
        new Response();
    }

    public function CopyFileAction($param,$guidParam){
        $helper=new MyHelper();
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
        $fileName=$helper->remove_accents($fileName);
        if (move_uploaded_file($_FILES['file']['tmp_name'], $webPath.$fileName)) {

            $ext=$this->SaberExt($fileName);



            if($this->isImg($ext)==true && $param!='mediainpage' && $param!='library'){

                $marcaguapic= $helper->randomPic('mediainpage'.DIRECTORY_SEPARATOR.'watermark'.DIRECTORY_SEPARATOR);
                $estampa = imagecreatefrompng($marcaguapic);
               if($ext=='jpg' )
                   $im = imagecreatefromjpeg($webPath.$fileName);
                if($ext=='png' )
                    $im = imagecreatefrompng($webPath.$fileName);

                $margen_dcho = 10;
                $margen_inf = 0;
                $sx = imagesx($estampa);
                $sy = imagesy($estampa);
                imagecopy($im, $estampa, imagesx($im) - $sx - $margen_dcho, imagesy($im) - $sy - $margen_inf, 0, 0, imagesx($estampa), imagesy($estampa));
                imagepng($im,$webPath.$fileName);
                imagedestroy($im);

            }

            if($this->isImg($ext)){
                $helper=new MyHelper();
                $fileout=str_replace('.'.$ext,'_small.'.$ext,$fileName);
              //  print($fileout);
               $resp= $helper->makeImage($webPath.$fileName,$webPath.$fileout);
            }else{
                $resp=false;
            }


            $final=array(
                0=>$this->getRequest()->getBasePath().'/'.$folderFull.$fileName,
                1=>$this->getRequest()->getBasePath().'/'.$this->FileExt($fileName,$folderFull,self::mediaFolder),
                2=>true,
                3=>$fileName,
                4=>$retid.$fileName,
                5=>$param,
                6=>$guidParam,
                7=>$resp
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

        $ext=$this->SaberExt($filename);

        $outname=str_replace('.'.$ext,'_small.'.$ext,$filename);

        if ($folder == 'library')
        {

            $ruta=$folder.'/'.$filename;
            $ruta2=$folder.'/'.$outname;
            $retid=$folder.$filename;

        }
        else{
            $ruta=$folder.'/'.$guidParam.'/'.$filename;
            $ruta2=$folder.'/'.$guidParam.'/'.$outname;
            $retid=$folder.$guidParam.$filename;
        }


            $webPath = $this->get('kernel')->getRootDir().'/../web/'.$ruta;
            $webPath2 = $this->get('kernel')->getRootDir().'/../web/'.$ruta2;



            if(!file_exists($webPath)){
                $final=array(
                    0=>false,
                    1=>$filename
                );return new JsonResponse($final);
            }

    
        if(file_exists($webPath2)){
            unlink($webPath2);
        }

            $final=array(
                0=>unlink($webPath),
                1=>$filename,
                2=>$retid,
                3=>1,

            );
        $em = $this->getDoctrine()->getManager();
        $descripcionprev=$em->getRepository('gemaBundle:MediaDescription')-> findOneBy(
            array(
                'nombre'=>$filename,
                'folder'=>$folder,
                'subforlder'=>$guidParam
            )
        );
        if($descripcionprev!=null){
            $em->remove($descripcionprev);
            $em->flush();

        }

        return new JsonResponse($final);

    }


  public function setdescriptionAction($folder,$subfolder,$nombre,$description){

      try{
          if($subfolder=='undefined')
              $subfolder='';
          $em = $this->getDoctrine()->getManager();
          $descripcionprev=$em->getRepository('gemaBundle:MediaDescription')-> findOneBy(
              array(
                  'nombre'=>$nombre,
                  'folder'=>$folder,
                  'subforlder'=>$subfolder
              )
          );

          if($descripcionprev!=null){

              $descripcionprev=$em->getRepository('gemaBundle:MediaDescription')->find($descripcionprev->getId());
              $descripcionprev->setFolder($folder);
              $descripcionprev->setSubforlder($subfolder);
              $descripcionprev->setNombre($nombre);
              $descripcionprev->setDescripcion($description);

              $em->persist($descripcionprev);
              $em->flush();

              $final=array(
                  0=>'Actualizado'
              );
              return new JsonResponse($final);
          }

          else{

              $des=new MediaDescription();
              $des->setFolder($folder);
              $des->setSubforlder($subfolder);
              $des->setNombre($nombre);
              $des->setDescripcion($description);

              $em->persist($des);
              $em->flush();
              $final=array(
                  0=>'Actualizado'
              );
              return new JsonResponse($final);
          }


      }
      catch( Exception $e){
          $final=array(
              0=>$e->getMessage()
          );
          return new JsonResponse($final);
      }


     // $repodesc = $em->getRepository('gemaBundle:Raza');

  }

    public function getdescriptionAction($folder,$subfolder,$nombre){

        try{
            $em = $this->getDoctrine()->getManager();
            $descripcionprev=$em->getRepository('gemaBundle:MediaDescription')-> findOneBy(
                array(
                    'nombre'=>$nombre,
                    'folder'=>$folder,
                    'subforlder'=>$subfolder
                )
            );

            if($descripcionprev==null){
                $final=array(
                    0=>''
                );
                return new JsonResponse($final);
            }
            $final=array(
                0=>$descripcionprev->getDescripcion()
            );
            return new JsonResponse($final);

        }
        catch(Exception $e){
            $final=array(
                0=>$e->getMessage()
            );
            return new JsonResponse($final);
        }
    }





}
