<?php
/**
 * Created by PhpStorm.
 * User: AK0
 * Date: 26/07/2017
 * Time: 1:05
 */

namespace GEMA\gemaBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use GEMA\gemaBundle\Helpers\MyHelper;


class ProdProgclientController extends Controller
{


    public function indexAction($tipo){



        $em = $this->getDoctrine()->getManager();
       if($tipo=='Programas')
           $tipo='Programa';
        else
            $tipo='Producto';
        $prodprogs = $em->getRepository('gemaBundle:Productosprogramas')->findBy(
            array(
                'publico'=>1,
                 'tipo'=>$tipo
            )
        );
        $helper=new MyHelper();

         foreach($prodprogs as $p){
           $p->logo=$helper->randomPic('productoprograma'.DIRECTORY_SEPARATOR.$p->getGuid().'L'.DIRECTORY_SEPARATOR);
        }
        return $this->render('gemaBundle:Page:prodprog.html.twig', array(
            'prodprog' => $prodprogs

        ));

    }

    public function newwindAction($nombremenu){

        $em = $this->getDoctrine()->getManager();
        $prodprogs = $em->getRepository('gemaBundle:Productosprogramas')->findOneBynombremenu($nombremenu);

        if($prodprogs==NULL ){
            $response = $this->forward('gemaBundle:Page:index', array(

            ));

            return $response;
        }

        $helper=new MyHelper();
        $prodprogs->logo=$helper->randomPic('productoprograma'.DIRECTORY_SEPARATOR.$prodprogs->getGuid().'L'.DIRECTORY_SEPARATOR);
        return $this->render('gemaBundle:Page:prodprognewwind.html.twig', array(
            'prodprog' => $prodprogs

        ));

    }


    public function cpAction(){
        $helper=new MyHelper();
        $servername= $_SERVER['SERVER_NAME'];
        $redventasimg=$helper->randomPic('cp'.DIRECTORY_SEPARATOR.'backgroundvideo'.DIRECTORY_SEPARATOR);
        $imagelist=$helper->randomPic('cp'.DIRECTORY_SEPARATOR.'cpil'.DIRECTORY_SEPARATOR);
        $circle=$helper->randomPic('cp'.DIRECTORY_SEPARATOR.'circle'.DIRECTORY_SEPARATOR);
        //toros
        $em = $this->getDoctrine()->getManager();
        $toros=$em->getRepository('gemaBundle:Toro')->findBy(array(
            'CP'=>1,
            'publico'=>1
        ));

        foreach ($toros as $toro){
            $img=$helper->randomPic('toro'.DIRECTORY_SEPARATOR.$toro->getGuid().'P'.DIRECTORY_SEPARATOR,true);
            if($img==null)
                $img=$helper->directPic('genericfiles'.DIRECTORY_SEPARATOR,'toro.png',true);
            $toro->imgprincipal=$img;
        }
        $colaboradores=$em->getRepository('gemaBundle:Colaborador')->findAll();
        $lugares=array();
        foreach ($colaboradores as $colaborador)
        {
            $lugares[]=$colaborador->getUbicacion();
            $image= $helper->randomPic('colaborador'.DIRECTORY_SEPARATOR.$colaborador->getGuid().'P'.DIRECTORY_SEPARATOR,true);
                if($img==null)
                    $img=$helper->directPic('genericfiles'.DIRECTORY_SEPARATOR,'toro.png',true);
            $colaborador->image=$image;

        }
        $lugares=array_unique($lugares);
        $dudas=$em->getRepository('gemaBundle:Singlehtml')->findOneBy(
            array(
                'nombre'=>'dudas'
            )
        );
        return $this->render('gemaBundle:Page:cp.html.twig', array(
            'redimg'=>$redventasimg,
            'servername'=>$servername,
            'imagelist'=>$imagelist,
            'circle'=>$circle,
            'toros'=>$toros,
            'colaboradores'=>$colaboradores,
            'lugares'=>$lugares,
             'dudas'=>$dudas
        ));
    }
}