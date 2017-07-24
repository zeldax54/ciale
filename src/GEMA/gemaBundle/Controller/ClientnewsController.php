<?php
/**
 * Created by PhpStorm.
 * User: AK0
 * Date: 21/07/2017
 * Time: 19:18
 */

namespace GEMA\gemaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use GEMA\gemaBundle\Helpers\MyHelper;
use GEMA\gemaBundle\Entity\CategoriaNoticia;

class ClientnewsController  extends Controller
{


    public function clientnoticiasAction($tipo,$start,$end)
    {

        $em = $this->getDoctrine()->getManager();
        $noticias = $em->getRepository('gemaBundle:Noticia')->first10($tipo,$start,$end);
        $helper=new MyHelper();
        foreach($noticias as $noticia)
         $noticia->portada= $img=$helper->randomPic('noticia'.DIRECTORY_SEPARATOR.$noticia->getGuid().DIRECTORY_SEPARATOR);

        if($tipo!='Todas')
        {
            $tipos = $em->getRepository('gemaBundle:CategoriaNoticia')->findallbut($tipo);
            $entity = new CategoriaNoticia();
            $entity->setNombre('Todas');
            $tipos[]=$entity;
         }

        else
            $tipos = $em->getRepository('gemaBundle:CategoriaNoticia')->findAll();
     //   print(count($noticias));die();

        if(count($noticias)==0)
        { $start=0;$end=10;}

        return $this->render('gemaBundle:Page:noticias.html.twig', array(
            'noticias' => $noticias,
            'tiposnoticias'=>$tipos,
            'tiposelected'=>$tipo,
            'start'=>$start,
             'end'=>$end
        ));
    }

     public function clientsinflenoticiaAction($id){

         $em = $this->getDoctrine()->getManager();
         $noticia = $em->getRepository('gemaBundle:Noticia')->find($id);
         $helper=new MyHelper();
         $mediaInpage=array();
         $medianews=$helper->filesInFolder('noticia'.DIRECTORY_SEPARATOR.$noticia->getGuid().'G'.DIRECTORY_SEPARATOR);
         if(count($medianews)>0)
             foreach($medianews as $mt){
                 $mediaInpage[]=array(
                     'tipo'=>'img',
                     'url'=> $mt,
                     'representacion'=>$mt
                 );
             }
         $lis=array();
         if(count($mediaInpage)>0)
         {
             $claves_aleatorias = array_rand($mediaInpage, count($mediaInpage));
             $finalmedia=array();
             foreach($claves_aleatorias as $clave)
             {
                 $finalmedia[]=$mediaInpage[$clave];
             }
             $flag=0;
             $flaadd=0;

             shuffle ($mediaInpage);
             for($i=0;$i<count($mediaInpage);$i++)
             {
                 if($mediaInpage[$i]['tipo']=='video')
                 {
                     $lis[$flaadd][]=array(

                         'rep'=>$mediaInpage[$i]['representacion'],
                         'url'=>$mediaInpage[$i]['url']

                     );
                 }
                 else{
                     $lis[$flaadd][] =array(

                         'rep'=>$mediaInpage[$i]['representacion'],
                         'url'=>$mediaInpage[$i]['representacion']

                     );
                 }
                 $flag++;
                 if($flag==40)
                     $flaadd++;
             }
         }

         $noticia->portada= $img=$helper->randomPic('noticia'.DIRECTORY_SEPARATOR.$noticia->getGuid().DIRECTORY_SEPARATOR);
         return $this->render('gemaBundle:Page:noticia.html.twig', array(
             'noticia' => $noticia,
             'mediainpage'=>$lis
         ));
     }

}