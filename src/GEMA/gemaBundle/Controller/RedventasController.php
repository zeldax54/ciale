<?php
/**
 * Created by PhpStorm.
 * User: AK0
 * Date: 08/07/2017
 * Time: 15:17
 */

namespace GEMA\gemaBundle\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use GEMA\gemaBundle\Helpers\MyHelper;



class RedventasController extends Controller
{




    public function inicioAction($codigo){

        $em = $this->getDoctrine()->getManager();

        $datosof=null;
        $provincianame=null;
        $localdistrib=null;
        if($codigo==-1)
        {
            $datosof=$em->getRepository('gemaBundle:DatosOficina')->find(1);
            $staff=$em->getRepository('gemaBundle:Staff')->stafflist();
            $paramfolder='staff';
        }
        else{
            $paramfolder='vendedor';
            $prov=$em->getRepository('gemaBundle:Provincia')->findOneBycodigo($codigo);
            $provincianame='<em class="redventasHeader" style="color:#00388B;margin-left: 10px;">'.$prov->getNombre().'</em>' ;
            $staff=$em->getRepository('gemaBundle:Vendedor')->findBy(array(
                'provincia'=>$prov->getId()
            ));
            $localdistrib=$em->getRepository('gemaBundle:Distribuidorlocal')->findBy(array(
                'provincia'=>$prov->getId()
            ));

            foreach($localdistrib as $l)
            {
                $l->datos=array_filter(json_decode($l->getPersonal(),true));
            }
        }


        $distribuidores=$em->getRepository('gemaBundle:Distribuidor')->findAll();
        $helper=new MyHelper();

        foreach($staff as $s){
            $img=$helper->randomPic($paramfolder.DIRECTORY_SEPARATOR.$s->getGuid().DIRECTORY_SEPARATOR);
            if($img==null)
                $img=$helper->directPic('genericfiles'.DIRECTORY_SEPARATOR,'user.png');
            $s->foto=$img;
        }
        $gife=$helper->directPic('genericfiles'.DIRECTORY_SEPARATOR,'paperplane.gif');
        $razas = $em->getRepository('gemaBundle:Raza')->findAll();
        return $this->render('gemaBundle:Page:red-ventas.html.twig', array(
              'datosoficina'=> $datosof,
                'staff'=>$staff,
                'distrib'=>$distribuidores,
                'provname'=>$provincianame,
                'codigo'=>$codigo,
                'localdist'=>$localdistrib,
                 'gife'=>$gife,



            )
        );

    }
}