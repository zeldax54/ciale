<?php

namespace GEMA\gemaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use GEMA\gemaBundle\Helpers\MyHelper;

class PageController extends Controller {

    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

            $reporaza = $em->getRepository('gemaBundle:Raza');
            $repodistribuidores = $em->getRepository('gemaBundle:Distribuidor');
            $distribuidores=$repodistribuidores->findAll();
            $carne=$reporaza->findBy(array(
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

        return $this->render('gemaBundle:Page:page.html.twig', array(
'carne'=>$carne,'leche'=>$leche,'sliders'=>$sliders,'imgprinc'=>$princimg,'redimg'=>$redventasimg,
                'distrib'=>$distribuidores,'imgtopcatalog'=>$imagetopdesccatalog,
                'imgcarne'=>$imagecatalogCarne,'imgleche'=>$imagecatalogLeche,
                'noticias'=>$noticias,
                'boletincarne'=>$boletincarne,
                'boletinleche'=>$boletinleche
                )
                );
    }



}
