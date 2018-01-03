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

    public function newwindAction($id){

        $em = $this->getDoctrine()->getManager();
        $prodprogs = $em->getRepository('gemaBundle:Productosprogramas')->find($id);
        $helper=new MyHelper();
        $prodprogs->logo=$helper->randomPic('productoprograma'.DIRECTORY_SEPARATOR.$prodprogs->getGuid().'L'.DIRECTORY_SEPARATOR);
        return $this->render('gemaBundle:Page:prodprognewwind.html.twig', array(
            'prodprog' => $prodprogs

        ));

    }
}