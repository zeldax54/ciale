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


    public function indexAction(){

        $em = $this->getDoctrine()->getManager();
        $prodprogs = $em->getRepository('gemaBundle:Productosprogramas')->findBy(
            array(
                'publico'=>1
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
}