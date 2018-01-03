<?php

namespace GEMA\gemaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use GEMA\gemaBundle\Helpers\MyHelper;

class PageController extends Controller {


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

    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

            $reporaza = $em->getRepository('gemaBundle:Raza');
            $repodistribuidores = $em->getRepository('gemaBundle:Distribuidor');
            $distribuidores=$repodistribuidores->findAll();

           $fathers=$em->getRepository('gemaBundle:Razafather')->findAll();
           $razasnofather=$em->getRepository('gemaBundle:Raza')->findBy(array(
               'father'=>null,
                'tiporaza'=>1
           ));





        $leche=$reporaza->findBy(array(
            'tiporaza'=>3
        ));

        $helper=new MyHelper();
        $sliders=$helper->filesInFolder('mediainpage'.DIRECTORY_SEPARATOR.'princslider'.DIRECTORY_SEPARATOR);
        $princimg=$helper->randomPic('mediainpage'.DIRECTORY_SEPARATOR.'mainpagepics'.DIRECTORY_SEPARATOR);
        $redventasimg=$helper->randomPic('mediainpage'.DIRECTORY_SEPARATOR.'imgredventas'.DIRECTORY_SEPARATOR);
        $imagetopdesccatalog=$helper->randomPic('mediainpage'.DIRECTORY_SEPARATOR.'imgtopcatalog'.DIRECTORY_SEPARATOR);
        $imagecatalogCarne=$helper->randomPic('mediainpage'.DIRECTORY_SEPARATOR.'imgscatalogCarne'.DIRECTORY_SEPARATOR);
        $imagecatalogLeche=$helper->randomPic('mediainpage'.DIRECTORY_SEPARATOR.'imgcatalogLeche'.DIRECTORY_SEPARATOR);
        $boletincarne=$helper->randomurlFile('descargables'.DIRECTORY_SEPARATOR.'boletincarne'.DIRECTORY_SEPARATOR);
        $boletinleche=$helper->randomurlFile('descargables'.DIRECTORY_SEPARATOR.'boletinleche'.DIRECTORY_SEPARATOR);

        $noticias = $em->getRepository('gemaBundle:Noticia')->lastthree();
         $servername= $_SERVER['SERVER_NAME'];
        sort($sliders, SORT_NATURAL | SORT_FLAG_CASE);
         $slidersdesc=array();
        foreach($sliders as $s){
            $slidersdesc[]=array(
                'img'=>$s,
                'desc'=>$this->getDesc(explode(DIRECTORY_SEPARATOR,$s))
            );
        }


        return $this->render('gemaBundle:Page:page.html.twig', array(
                'fathers'=>$fathers,
                'razasnofather'=>$razasnofather
                ,'leche'=>$leche,
                'sliders'=>$slidersdesc,
                'imgprinc'=>$princimg,
                'redimg'=>$redventasimg,
                'distrib'=>$distribuidores,
                'imgtopcatalog'=>$imagetopdesccatalog,
                'imgcarne'=>$imagecatalogCarne,
                'imgleche'=>$imagecatalogLeche,
                'noticias'=>$noticias,
                'boletincarne'=>$boletincarne,
                'boletinleche'=>$boletinleche,
                'servername'=>$servername
                )
      );
    }



}
