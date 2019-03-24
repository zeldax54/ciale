<?php
namespace GEMA\gemaBundle\Helpers;
/**
 * Created by PhpStorm.
 * User: AK0
 * Date: 22/06/2017
 * Time: 2:38
 */

use MongoDB\Driver\Exception\ExecutionTimeoutException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
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

     function filesInFolder($folder,$small=false)
     {
         global $kernel;
         $path = $kernel->getRootDir() . DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'web'.DIRECTORY_SEPARATOR.$folder;
         $this->CheckCreate($path);
         $ficheros = array_diff(scandir($path), array('.', '..'));
         $final=array();
         foreach($ficheros as $ff){




             if($small==true){
               //  sad
                 if(strpos($ff, '_small') !== false){

                     for($i=0;$i<count($final);$i++){
                         if(str_replace('_small','',$final[$i])==$ff)
                         {
                             unset($final[$i]) ;break;
                         }
                         if($final[$i]==$ff){
                             unset($final[$i]) ;break;
                         }
                     }


                         $final[]=
                             $this->FileExt($ff,$folder,self::mediaFolder);


                 }
                 else{
                     $band=false;
                     if(isset($final))
                         foreach($final as $finalel){//Buscar si ya esta en los smalls
                             if(str_replace('_small','',$finalel)==$ff)
                             {
                                 $band=true;break;
                             }
                             if($finalel==$ff){
                                 $band=true;break;
                             }
                         }

                     if($band==false){
                         $ext=$this->SaberExt($ff);
                         $outname=str_replace('.'.$ext,'_small.'.$ext,$ff);
                         $in=$path.$ff;


                         $out=$path.$outname;
                         if($this->makeImage($in,$out)==true){
                             $final[]=$this->FileExt($outname,$folder,self::mediaFolder);
                         }else{
                             $final[]=$this->FileExt($ff,$folder,self::mediaFolder);
                         }
                     }

                    // print ($path.$ff);die();
                   //  print_r($this->FileExt($ff,$folder,self::mediaFolder));die();
                 }
             }else{
                 if(strpos($ff, '_small') === false)
                 $final[]=
                     $this->FileExt($ff,$folder,self::mediaFolder);
             }

         }



         if(!isset($final))
             return null;
         if(count($final)>0)
             $final=array_unique($final);
         return $final;

     }

    function directPic($rutafolder,$picName,$small=false)
    {

        global $kernel;
        $path = $kernel->getRootDir() . DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'web'.DIRECTORY_SEPARATOR.$rutafolder;
        $ficheros = array_diff(scandir($path), array('.', '..'));

        if($small==true){
            $ext=$this->SaberExt($picName);
            $originalNam=$picName;
            $picName= str_replace('.'.$ext,'_small.'.$ext,$picName);

            foreach($ficheros as $f){

                if(mb_strtolower($f)===mb_strtolower($picName))
                    return $this->FileExt($f,$rutafolder,self::mediaFolder);
            }

            //Chequeo caso no small a ver si puede crearse
            foreach($ficheros as $f){
                if(mb_strtolower($f)===mb_strtolower($originalNam)){
                    $in=$path.$originalNam;
                    $out=$path.$picName;
                    if($this->makeImage($in,$out)==true){

                        return $this->FileExt($picName,$rutafolder,self::mediaFolder);
                    }

                   else{

                       return $this->FileExt($f,$rutafolder,self::mediaFolder);
                   }

                }
            }
        }

       foreach($ficheros as $f)
       {


           if(mb_strtolower($f)===mb_strtolower($picName))
           {
               $img=$this->FileExt($f,$rutafolder,self::mediaFolder);
//               $path=$kernel->getRootDir() . DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'web'.DIRECTORY_SEPARATOR;
//               $filein=$path.$img;
//               $ext=$this->SaberExt($img);
//               $outname=str_replace('.'.$ext,'_small.'.$ext,$img);
//               $fileout=$path.$outname;
//              if($this->makeImage($filein,$fileout)==true){
//                  return $outname;
//              }
               return $img;
           }

       }

        return null;

    }

    function videosPic($videourl)
    {

        try {
            preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $videourl, $match);
            $youtube_id = $match[1];

            $ruta = $this->directPic('minyoutube' . DIRECTORY_SEPARATOR, $youtube_id . '.jpg', false);

            if ($ruta == null) {

                $urlapi = 'https://img.youtube.com/vi/' . $youtube_id . '/sddefault.jpg';

                global $kernel;
                $path = $kernel->getRootDir() . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'web' . DIRECTORY_SEPARATOR . 'minyoutube' . DIRECTORY_SEPARATOR . $youtube_id . '.jpg';
               if (@copy($urlapi, $path)) {
                    return $path;
                }
                return $this->directPic('genericfiles'.DIRECTORY_SEPARATOR,'video.png',false);


            }

            return $ruta;
        } catch (\Exception $e) {


        }
    }



    function generateThumb($videourl){

        try{

            preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $videourl, $match);
            $youtube_id = $match[1];
            $ruta=$this->directPic('minyoutube'.DIRECTORY_SEPARATOR,$youtube_id);
            if($ruta==null){

                $urlapi='https://img.youtube.com/vi/'.$youtube_id.'/sddefault.jpg';
                global $kernel;
                $path = $kernel->getRootDir() . DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'web'.DIRECTORY_SEPARATOR.'minyoutube'.DIRECTORY_SEPARATOR.$youtube_id.'.jpg';
                copy($urlapi,$path);
                return $path;
            }
        }catch(\Exception $e){
            return false;
        }

    }

    function Kernelurl()
    {
        global $kernel;
       return $kernel->getRootDir();
    }

    function randomPic($folder,$small=false)
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

        if($small==true){
            foreach($final as $fin){
                if (strpos($fin, '_small') !== false) {

                   return $fin;
                }
            }
                $img = array_rand($final, 1);
            $imgfin=$final[$img];
            //No ha sido creda una _small ver de ser posible su creación.
             $realpath= $kernel->getRootDir() . DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'web'.DIRECTORY_SEPARATOR;
            $filein=$realpath.$imgfin;
            $arrexplodeName=explode(DIRECTORY_SEPARATOR,$imgfin);
            $outname=$arrexplodeName[count($arrexplodeName)-1];
            $ext=$this->SaberExt($outname);
            $out= str_replace('.'.$ext,'_small.'.$ext,$outname);
            $fileout=$realpath.$folder.$out;
               //
            if($this->makeImage($filein,$fileout)==true){
                return $folder.$out;
            }
            return $imgfin;


        }else
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

   public function SaberExt($filename){
        $split  = explode('.',$filename);
        if(isset($split[count($split)-1])) {
        return $split[count($split)-1];
        }
        return null;
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


    /**
     * @param $pictures array
     * @param $videos array
     * @param $agrupador int  Numero de elementos a mostrar en una pantalla del slider
     * @return array
     */
    public function generateMedias($pictures, $videos, $agrupador){
        $mediaInpage=array();
       if($videos!=null){
           $videos=json_decode($videos,true);
           foreach($videos as $y)
           {


               $img=$this->videosPic($y);

               $mediaInpage[]=array(
                   'tipo'=>'video',
                   'url'=> $y,
                   'representacion'=>$img
               );
           }
       }


        if(count($pictures)>0){
            sort($pictures, SORT_NATURAL | SORT_FLAG_CASE);
            foreach($pictures as $mt){
                $mediaInpage[]=array(
                    'tipo'=>'img',
                    'url'=> $mt,
                    'representacion'=>$mt
                );
            }
        }

        $lis=array();
        if(count($mediaInpage)>0)
        {
//            $claves_aleatorias = array_rand($mediaInpage, count($mediaInpage));
//            $finalmedia=array();
//            if($claves_aleatorias!=0){
//                foreach($claves_aleatorias as $clave)
//                {
//                    $finalmedia[]=$mediaInpage[$clave];
//                }
//
//            }else{
//                $
//            }


            $flag=0;
            $flaadd=0;

           // shuffle ($mediaInpage);
            for($i=0;$i<count($mediaInpage);$i++)
            {
                if($mediaInpage[$i]['tipo']=='video')
                {
                    $lis[$flaadd][]=array(

                        'rep'=>$mediaInpage[$i]['representacion'],
                        'url'=>$mediaInpage[$i]['url'],
                        'tipo'=>'video'

                    );
                }
                else{
                    $lis[$flaadd][] =array(

                        'rep'=>$mediaInpage[$i]['representacion'],
                        'url'=>$mediaInpage[$i]['representacion'],
                        'tipo'=>'imagen'

                    );
                }
                $flag++;
                if($flag==$agrupador)
                    $flaadd++;
            }
        }

        return $lis;

    }



    function makeImage($file_in,$file_out,$size=360,$orientation="",$jpegQuality=100) { //- function to make a new image


        if(getimagesize($file_in)[0]<$size)
            return false;
        // make sure it's valid
        list($w, $h, $type) = @getimagesize($file_in);
        if($w < 1) return false;

        $src_img = null;
        // find image type and create temp image and variable
        if ($type == IMAGETYPE_JPEG) {
            $src_img = @imagecreatefromjpeg($file_in);
        } else if ($type == IMAGETYPE_GIF) {
            $src_img = @imagecreatefromgif($file_in);
        } else if ($type == IMAGETYPE_PNG) {
            $src_img = @imagecreatefrompng($file_in);
        }
        if(!$src_img) return false;

        // choose which side to change the size of: width or height, based on parameter... if neither w or h, then use whichever is longer
        if ($orientation == "w") {
            $new_w = $size;
            $new_h = $h * ($size/$w);
        } else if ($orientation == "h") {
            $new_h = $size;
            $new_w = $w * ($size/$h);
        } else {
            if ($h > $w) {
                $new_h = $size;
                $new_w = $w * ($size/$h);
            } else {
                $new_w = $size;
                $new_h = $h * ($size/$w);
            }
        }

        // create temp image
        $tmp_img = imagecreatetruecolor($new_w, $new_h);
        $white = imagecolorallocate($tmp_img, 255, 255, 255);
        imagefill($tmp_img, 0, 0, $white);

        // make the new temp image all transparent
        imagecolortransparent($tmp_img, $white);
        imagealphablending($tmp_img, false);
        imagesavealpha($tmp_img, true);

        // put uploaded image onto temp image
        imagecopyresampled($tmp_img, $src_img, 0,0,0,0,$new_w,$new_h,$w,$h);

        if ($type == IMAGETYPE_JPEG) {
            imagejpeg($tmp_img, $file_out, $jpegQuality);
        } else if ($type == IMAGETYPE_GIF) {
            imagegif($tmp_img, $file_out);
        } else if ($type == IMAGETYPE_PNG) {
            imagealphablending($tmp_img, false);
            imagesavealpha($tmp_img, true);
            imagepng($tmp_img, $file_out);
        }

        imagedestroy($tmp_img);
        return true;
    }

    function remove_accents($string) {
        if ( !preg_match('/[\x80-\xff]/', $string) )
            return $string;

        $chars = array(
            // Decompositions for Latin-1 Supplement
            chr(195).chr(128) => 'A', chr(195).chr(129) => 'A',
            chr(195).chr(130) => 'A', chr(195).chr(131) => 'A',
            chr(195).chr(132) => 'A', chr(195).chr(133) => 'A',
            chr(195).chr(135) => 'C', chr(195).chr(136) => 'E',
            chr(195).chr(137) => 'E', chr(195).chr(138) => 'E',
            chr(195).chr(139) => 'E', chr(195).chr(140) => 'I',
            chr(195).chr(141) => 'I', chr(195).chr(142) => 'I',
            chr(195).chr(143) => 'I', chr(195).chr(145) => 'N',
            chr(195).chr(146) => 'O', chr(195).chr(147) => 'O',
            chr(195).chr(148) => 'O', chr(195).chr(149) => 'O',
            chr(195).chr(150) => 'O', chr(195).chr(153) => 'U',
            chr(195).chr(154) => 'U', chr(195).chr(155) => 'U',
            chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y',
            chr(195).chr(159) => 's', chr(195).chr(160) => 'a',
            chr(195).chr(161) => 'a', chr(195).chr(162) => 'a',
            chr(195).chr(163) => 'a', chr(195).chr(164) => 'a',
            chr(195).chr(165) => 'a', chr(195).chr(167) => 'c',
            chr(195).chr(168) => 'e', chr(195).chr(169) => 'e',
            chr(195).chr(170) => 'e', chr(195).chr(171) => 'e',
            chr(195).chr(172) => 'i', chr(195).chr(173) => 'i',
            chr(195).chr(174) => 'i', chr(195).chr(175) => 'i',
            chr(195).chr(177) => 'n', chr(195).chr(178) => 'o',
            chr(195).chr(179) => 'o', chr(195).chr(180) => 'o',
            chr(195).chr(181) => 'o', chr(195).chr(182) => 'o',
            chr(195).chr(182) => 'o', chr(195).chr(185) => 'u',
            chr(195).chr(186) => 'u', chr(195).chr(187) => 'u',
            chr(195).chr(188) => 'u', chr(195).chr(189) => 'y',
            chr(195).chr(191) => 'y',
            // Decompositions for Latin Extended-A
            chr(196).chr(128) => 'A', chr(196).chr(129) => 'a',
            chr(196).chr(130) => 'A', chr(196).chr(131) => 'a',
            chr(196).chr(132) => 'A', chr(196).chr(133) => 'a',
            chr(196).chr(134) => 'C', chr(196).chr(135) => 'c',
            chr(196).chr(136) => 'C', chr(196).chr(137) => 'c',
            chr(196).chr(138) => 'C', chr(196).chr(139) => 'c',
            chr(196).chr(140) => 'C', chr(196).chr(141) => 'c',
            chr(196).chr(142) => 'D', chr(196).chr(143) => 'd',
            chr(196).chr(144) => 'D', chr(196).chr(145) => 'd',
            chr(196).chr(146) => 'E', chr(196).chr(147) => 'e',
            chr(196).chr(148) => 'E', chr(196).chr(149) => 'e',
            chr(196).chr(150) => 'E', chr(196).chr(151) => 'e',
            chr(196).chr(152) => 'E', chr(196).chr(153) => 'e',
            chr(196).chr(154) => 'E', chr(196).chr(155) => 'e',
            chr(196).chr(156) => 'G', chr(196).chr(157) => 'g',
            chr(196).chr(158) => 'G', chr(196).chr(159) => 'g',
            chr(196).chr(160) => 'G', chr(196).chr(161) => 'g',
            chr(196).chr(162) => 'G', chr(196).chr(163) => 'g',
            chr(196).chr(164) => 'H', chr(196).chr(165) => 'h',
            chr(196).chr(166) => 'H', chr(196).chr(167) => 'h',
            chr(196).chr(168) => 'I', chr(196).chr(169) => 'i',
            chr(196).chr(170) => 'I', chr(196).chr(171) => 'i',
            chr(196).chr(172) => 'I', chr(196).chr(173) => 'i',
            chr(196).chr(174) => 'I', chr(196).chr(175) => 'i',
            chr(196).chr(176) => 'I', chr(196).chr(177) => 'i',
            chr(196).chr(178) => 'IJ',chr(196).chr(179) => 'ij',
            chr(196).chr(180) => 'J', chr(196).chr(181) => 'j',
            chr(196).chr(182) => 'K', chr(196).chr(183) => 'k',
            chr(196).chr(184) => 'k', chr(196).chr(185) => 'L',
            chr(196).chr(186) => 'l', chr(196).chr(187) => 'L',
            chr(196).chr(188) => 'l', chr(196).chr(189) => 'L',
            chr(196).chr(190) => 'l', chr(196).chr(191) => 'L',
            chr(197).chr(128) => 'l', chr(197).chr(129) => 'L',
            chr(197).chr(130) => 'l', chr(197).chr(131) => 'N',
            chr(197).chr(132) => 'n', chr(197).chr(133) => 'N',
            chr(197).chr(134) => 'n', chr(197).chr(135) => 'N',
            chr(197).chr(136) => 'n', chr(197).chr(137) => 'N',
            chr(197).chr(138) => 'n', chr(197).chr(139) => 'N',
            chr(197).chr(140) => 'O', chr(197).chr(141) => 'o',
            chr(197).chr(142) => 'O', chr(197).chr(143) => 'o',
            chr(197).chr(144) => 'O', chr(197).chr(145) => 'o',
            chr(197).chr(146) => 'OE',chr(197).chr(147) => 'oe',
            chr(197).chr(148) => 'R',chr(197).chr(149) => 'r',
            chr(197).chr(150) => 'R',chr(197).chr(151) => 'r',
            chr(197).chr(152) => 'R',chr(197).chr(153) => 'r',
            chr(197).chr(154) => 'S',chr(197).chr(155) => 's',
            chr(197).chr(156) => 'S',chr(197).chr(157) => 's',
            chr(197).chr(158) => 'S',chr(197).chr(159) => 's',
            chr(197).chr(160) => 'S', chr(197).chr(161) => 's',
            chr(197).chr(162) => 'T', chr(197).chr(163) => 't',
            chr(197).chr(164) => 'T', chr(197).chr(165) => 't',
            chr(197).chr(166) => 'T', chr(197).chr(167) => 't',
            chr(197).chr(168) => 'U', chr(197).chr(169) => 'u',
            chr(197).chr(170) => 'U', chr(197).chr(171) => 'u',
            chr(197).chr(172) => 'U', chr(197).chr(173) => 'u',
            chr(197).chr(174) => 'U', chr(197).chr(175) => 'u',
            chr(197).chr(176) => 'U', chr(197).chr(177) => 'u',
            chr(197).chr(178) => 'U', chr(197).chr(179) => 'u',
            chr(197).chr(180) => 'W', chr(197).chr(181) => 'w',
            chr(197).chr(182) => 'Y', chr(197).chr(183) => 'y',
            chr(197).chr(184) => 'Y', chr(197).chr(185) => 'Z',
            chr(197).chr(186) => 'z', chr(197).chr(187) => 'Z',
            chr(197).chr(188) => 'z', chr(197).chr(189) => 'Z',
            chr(197).chr(190) => 'z', chr(197).chr(191) => 's'
        );

        $string = strtr($string, $chars);

        return $string;
    }




    public function CopyFile($webPath,$file)
    {
        if (!file_exists($webPath)) {
            mkdir($webPath, 0777, true);
        }
        $fileName = $file['name'];
        $fileName = $this->remove_accents($fileName);
        if (move_uploaded_file($file['tmp_name'], $webPath . $fileName)) {

            return $webPath . $fileName;
        }

        return false;
    }

}