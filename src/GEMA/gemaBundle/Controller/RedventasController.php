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

        if($codigo=='comercial')
        {
            $datosof=$em->getRepository('gemaBundle:DatosOficina')->find(1);
            $staff=$em->getRepository('gemaBundle:Staff')->stafflist();
            $paramfolder='staff';
            $deptotec=$em->getRepository('gemaBundle:DeptoTecnico')->deptotecnicolist();
            $paramfolderdptotec='deptotecnico';
            $isdeptotec=true;
        }
        else{
            $deptotec=null;
            $isdeptotec=null;
            $paramfolderdptotec=null;
            $paramfolder='vendedor';
            $prov=$em->getRepository('gemaBundle:Provincia')->findOneBycodigo($codigo);
            $provincianame='<em class="redventasHeader" style="color:#00388B;margin-left: 10px;">'.$prov->getNombre().'</em>' ;
            $staff=$em->getRepository('gemaBundle:Vendedor')->findBy(array(
                'provincia'=>$prov->getId(),
                'publico'=>true
            ),array(
                'posicion'=>'ASC'
            ));
            $localdistrib=$em->getRepository('gemaBundle:Distribuidorlocal')->findBy(array(
                'provincia'=>$prov->getId()
            ), array('ciudad' => 'ASC'));

            foreach($localdistrib as $l)
            {
                $l->datos=array_filter(json_decode($l->getPersonal(),true));
            }
        }


        $distribuidores=$em->getRepository('gemaBundle:Distribuidor')->findAll();
        $helper=new MyHelper();

        foreach($staff as $s){
            $img=$helper->randomPic($paramfolder.DIRECTORY_SEPARATOR.$s->getGuid().DIRECTORY_SEPARATOR,true);
            if($img==null)
                $img=$helper->directPic('genericfiles'.DIRECTORY_SEPARATOR,'user.png',true);
            $s->foto=$img;
        }
        if($deptotec!=null){
            foreach($deptotec as $s){
                $img=$helper->randomPic($paramfolderdptotec.DIRECTORY_SEPARATOR.$s->getGuid().DIRECTORY_SEPARATOR,true);
                if($img==null)
                    $img=$helper->directPic('genericfiles'.DIRECTORY_SEPARATOR,'user.png',true);
                $s->foto=$img;
            }
        }

        $gife=$helper->directPic('genericfiles'.DIRECTORY_SEPARATOR,'paperplane.gif');
        $razas = $em->getRepository('gemaBundle:Raza')->findAll();
        $foto = $helper->directPic('logo'.DIRECTORY_SEPARATOR,'Foto.jpg');
        $wasap = $helper->directPic('logo'.DIRECTORY_SEPARATOR,'Whatsapp.jpg');
        $facebook = $helper->directPic('logo'.DIRECTORY_SEPARATOR,'Facebook.jpg');
        $instagram = $helper->directPic('logo'.DIRECTORY_SEPARATOR,'Instagram.jpg');
        $youtube = $helper->directPic('logo'.DIRECTORY_SEPARATOR,'Youtube.jpg');
        $linkedin = $helper->directPic('logo'.DIRECTORY_SEPARATOR,'Linkedin.jpg');

        return $this->render('gemaBundle:Page:red-ventas.html.twig', array(
              'datosoficina'=> $datosof,
                'staff'=>$staff,
                'distrib'=>$distribuidores,
                'provname'=>$provincianame,
                'codigo'=>$codigo,
                'localdist'=>$localdistrib,
                 'gife'=>$gife,
                'deptotec'=>$deptotec,
                'paramfolderdptotec'=>$paramfolderdptotec,
                'isdeptotec'=>$isdeptotec,
                'foto' => $foto,
                'wasap'=>$wasap,
                'facebook'=>$facebook,
                'instagram'=>$instagram,
                'youtube'=>$youtube,
                'linkedin'=>$linkedin
            )
        );

    }
}