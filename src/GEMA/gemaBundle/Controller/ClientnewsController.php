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
         $noticia->portada= $helper->randomPic('noticia'.DIRECTORY_SEPARATOR.$noticia->getGuid().DIRECTORY_SEPARATOR);

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
        { $start=0;$end=9;}

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
         $medianews=$helper->filesInFolder('noticia'.DIRECTORY_SEPARATOR.$noticia->getGuid().'G'.DIRECTORY_SEPARATOR);
         $youtubes=$noticia->getYoutube();
         $lis=$helper->generateMedias($medianews,$youtubes,40);

         $noticia->portada= $img=$helper->randomPic('noticia'.DIRECTORY_SEPARATOR.$noticia->getGuid().DIRECTORY_SEPARATOR);
         return $this->render('gemaBundle:Page:noticia.html.twig', array(
             'noticia' => $noticia,
             'mediainpage'=>$lis
         ));
     }
    public function clienlistboletinesAction($start,$end){

        $em = $this->getDoctrine()->getManager();
        $boletines = $em->getRepository('gemaBundle:Boletin')->first10($start,$end);
        $helper=new MyHelper();
        foreach($boletines as $boletin)
            $boletin->portada= $img=$helper->randomPic('boletin'.DIRECTORY_SEPARATOR.$boletin->getGuid().DIRECTORY_SEPARATOR);
        if(count($boletines)==0)
        { $start=0;$end=9;}

        return $this->render('gemaBundle:Page:boletines.html.twig', array(
            'boletines' => $boletines,
            'start'=>$start,
            'end'=>$end
        ));

    }

    public function clientboletinAction($id){
        $em = $this->getDoctrine()->getManager();
        $boletin = $em->getRepository('gemaBundle:Boletin')->find($id);
        $helper=new MyHelper();
        $medianews=$helper->filesInFolder('noticia'.DIRECTORY_SEPARATOR.$boletin->getGuid().'G'.DIRECTORY_SEPARATOR);
        $youtubes=$boletin->getYoutube();
        $lis=$helper->generateMedias($medianews,$youtubes,40);

        $boletin->portada= $img=$helper->randomPic('noticia'.DIRECTORY_SEPARATOR.$boletin->getGuid().DIRECTORY_SEPARATOR);
        return $this->render('gemaBundle:Page:noticia.html.twig', array(
            'noticia' => $boletin,
            'mediainpage'=>$lis,
            'isboletin'=>true
        ));
    }

}