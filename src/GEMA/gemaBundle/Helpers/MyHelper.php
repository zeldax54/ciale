<?php
namespace GEMA\gemaBundle\Helpers;
/**
 * Created by PhpStorm.
 * User: AK0
 * Date: 22/06/2017
 * Time: 2:38
 */

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Kernel;

class MyHelper
{

     public static $imagenes = array(0 => 'jpg', 1 => 'png', 2 => 'gif', 3 => 'jpeg',4=>'JPG');
     public static $excels = array(0=>'xls',1=>'xlsx');
     public static $words = array(0=>'doc',1=>'docx',2=>'pdf');
     public static $videos = array(0=>'mp4',1=>'avi',2=>'flv',3=>'webm');

     const  mediaFolder='media'.DIRECTORY_SEPARATOR;

   public function GUID()
    {
        if (function_exists('com_create_guid') === true)
        {
            return trim(com_create_guid(), '{}');
        }

        return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
    }

     public function CreateDir($path,$param,$guid){
         $webPath=$path.$param.'/'.$guid;
         if (!file_exists($webPath)) {
             mkdir($webPath, 0777, true);
         }
     }

    public function CheckCreate($fullpath){

        if (!file_exists($fullpath)) {
            mkdir($fullpath, 0777, true);
        }
    }

     public function RemoveFolder($webPath){
         if(file_exists($webPath))
             $this->_remove_path($webPath);
     }


     function _remove_path($folder){
         $files = glob( $folder . DIRECTORY_SEPARATOR . '*');
         foreach( $files as $file ){
             if($file == '.' || $file == '..'){continue;}
             if(is_dir($file)){
                 $this->_remove_path( $file );
             }else{
                 unlink( $file );
             }
         }
         rmdir( $folder );
     }

     function filesInFolder($folder)
     {
         global $kernel;
         $path = $kernel->getRootDir() . DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'web'.DIRECTORY_SEPARATOR.$folder;
         $this->CheckCreate($path);
         $ficheros = array_diff(scandir($path), array('.', '..'));
         foreach($ficheros as $ff)
             $final[]=
                $this->FileExt($ff,$folder,self::mediaFolder);
         if(!isset($final))
             return null;
         return $final;

     }

    function directPic($rutafolder,$picName)
    {

        global $kernel;
        $path = $kernel->getRootDir() . DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'web'.DIRECTORY_SEPARATOR.$rutafolder;
        $ficheros = array_diff(scandir($path), array('.', '..'));
       foreach($ficheros as $f)
       {
           if(mb_strtolower($f)==mb_strtolower($picName))
               return $this->FileExt($f,$rutafolder,self::mediaFolder);
       }
        return null;

    }

    function randomPic($folder)
    {
        global $kernel;
        $path = $kernel->getRootDir() . DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'web'.DIRECTORY_SEPARATOR.$folder;

        if (!file_exists($path)) {
        return null;
        }

        $ficheros = array_diff(scandir($path), array('.', '..'));
        if(count($ficheros)==0)
            return null;
        foreach($ficheros as $ff)
            $final[]=
                $this->FileExt($ff,$folder,self::mediaFolder);
        $img = array_rand($final, 1);
        return $final[$img];
    }

     function randomurlFile($folder){
         global $kernel;
         $path = $kernel->getRootDir() . DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'web'.DIRECTORY_SEPARATOR.$folder;

         if (!file_exists($path)) {
             return null;
         }

         $ficheros = array_diff(scandir($path), array('.', '..'));
         if(count($ficheros)==0)
             return null;
         foreach($ficheros as $ff)
             $final[]=$folder.$ff;
         $img = array_rand($final, 1);
         return $final[$img];
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


}