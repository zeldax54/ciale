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



class RazalistController extends Controller
{


    function listRazaAction($nombreraza)
    {
        $helper=new MyHelper();
        $em = $this->getDoctrine()->getManager();
        $toros=$em->getRepository('gemaBundle:Toro')->torosByRazaName($nombreraza);
        $raza=$em->getRepository('gemaBundle:Raza')->findRazabyName($nombreraza);

        $razas=$em->getRepository('gemaBundle:Raza')->findallBut($raza->getId(),
            $raza->getTipoRaza()->getId());

        $tablas=$raza->getTipotabla()->getTablas();
        foreach($toros as $toro)
        {

            $img=$helper->randomPic('toro'.DIRECTORY_SEPARATOR.$toro->getGuid().'P'.DIRECTORY_SEPARATOR);
            if($img==null)
                $img=$helper->directPic('genericfiles'.DIRECTORY_SEPARATOR,'toro.png');
            $toro->imgprincipal=$img;
            if($toro->getNuevo()==true)
                $toro->nuevoflag='<span style="padding: 3px;background: red;color: white;"><strong>Nuevo</strong></span>';
            else
                $toro->nuevoflag=null;
            $toro->facilidadglag=$this->imgFacilidadParto($helper,$toro->getFacilidadparto());
            $toro->nacionalidadflag=$this->Nacionalidad($helper,$toro->getNacionalidad());
            $toro->conceptplusflag=$this->ConceptPlus($helper,$toro->getCP());
            $toro->tablasflag=json_decode($toro->getTablagenetica(),true);
//            print_r($toro->getTablagenetica());die();
        }
        return $this->render('gemaBundle:Page:tablaraza.html.twig', array(
                'toros'=>$toros,
                'raza'=>$nombreraza,
                'tablas'=>$tablas,
                 'mocho'=>$raza->getMocho(),
                'razas'=>$razas


            )
        );
    }

    function toroDetailAction($toroid){

        $em = $this->getDoctrine()->getManager();
        $toro=$em->getRepository('gemaBundle:Toro')->find($toroid);
        $razas=$em->getRepository('gemaBundle:Raza')->findallBut($toro->getRaza()->getId(),
            $toro->getRaza()->getTipoRaza()->getId());

        $helper=new MyHelper();
        $img=$helper->randomPic('toro'.DIRECTORY_SEPARATOR.$toro->getGuid().'P'.DIRECTORY_SEPARATOR);

        if($img==null)
            $img=$helper->directPic('genericfiles'.DIRECTORY_SEPARATOR,'toro.png');

        $imgfp=$this->imgFacilidadParto($helper,$toro->getFacilidadparto());
        $imgcp=$this->ConceptPlus($helper,$toro->getCP());
        $silueta=$helper->directPic('genericfiles'.DIRECTORY_SEPARATOR,$toro->getRaza()->getSilueta().'.jpg');
        $tablasflag=json_decode($toro->getTablagenetica(),true);
        $watermarkimg=$helper->randomPic('mediainpage'.DIRECTORY_SEPARATOR.'watermark'.DIRECTORY_SEPARATOR);

        $tablarname=$em->getRepository('gemaBundle:Tabla')->find($toro->getTipotablaselected());
        $tablasflag=$tablasflag[$tablarname->getNombre()];
        $tabla=$tablarname;
        $tablagennombre=$tablarname->getNombre();

        $mediaInpage=array();
        if(count($toro->getYoutubes())>0)
        foreach($toro->getYoutubes() as $y)
        {
            $mediaInpage[]=array(
                'tipo'=>'video',
                'url'=> $y->getUrl(),
                'representacion'=>$helper->directPic('genericfiles'.DIRECTORY_SEPARATOR,'video.png')
            );
        }

        $mediatoro=$helper->filesInFolder('toro'.DIRECTORY_SEPARATOR.$toro->getGuid().DIRECTORY_SEPARATOR);
        if(count($mediatoro)>0)
        foreach($mediatoro as $mt){
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
                if($flag==4)
                    $flaadd++;
            }
        }
        return $this->render('gemaBundle:Page:detalle-toro.html.twig', array(
                'toro'=>$toro,
                'princimg'=>$img,
                 'imgfp'=>$imgfp,
                'imgcp'=>$imgcp,
                'silueta'=>$silueta,
                'watermarkimg'=>$watermarkimg,
                'tablagenetica'=>$tablasflag,
                'tabla'=>$tabla,
                'tablagennombre'=>$tablagennombre,
                'mediatoro'=>$lis,
                'razas'=>$razas

            )
        );
    }

      public function searchCarneAction($dato,$isfull){

        $em = $this->getDoctrine()->getManager();
        $toros=$em->getRepository('gemaBundle:Toro')->torosfullseach($dato);
          if($isfull=='0')
          {
              if( count($toros)==0)
              {
                  $respuesta=array(
                      0=>0,

                  );
                  return new JsonResponse($respuesta);
              }
              else{
                  $respuesta=array(
                      0=>1,
                  );
                  return new JsonResponse($respuesta);
              }
          }


        $helper=new MyHelper();
        foreach($toros as $toro)
        {
            $img=$helper->randomPic('toro'.DIRECTORY_SEPARATOR.$toro->getGuid().'P'.DIRECTORY_SEPARATOR);
            if($img==null)
                $img=$helper->directPic('genericfiles'.DIRECTORY_SEPARATOR,'toro.png');
            $toro->imgprincipal=$img;
            if($toro->getNuevo()==true)
                $toro->nuevoflag='<span style="padding: 3px;background: red;color: white;"><strong>Nuevo</strong></span>';
            else
                $toro->nuevoflag=null;
            $toro->facilidadglag=$this->imgFacilidadParto($helper,$toro->getFacilidadparto());
            $toro->nacionalidadflag=$this->Nacionalidad($helper,$toro->getNacionalidad());
            $toro->conceptplusflag=$this->ConceptPlus($helper,$toro->getCP());

        }
        return $this->render('gemaBundle:Page:tablaraza.html.twig', array(
                'toros'=>$toros,
                'fullsearch'=>true
            )
        );
//        return $this->redirect($this->generateUrl('admin_toro_'));
    }



    function ConceptPlus($helper,$cp){
        if($cp==false)
            return null;
        return $helper->directPic('genericfiles'.DIRECTORY_SEPARATOR,'conceptPlus.png');
    }

    function Nacionalidad($helper,$nacionalidad)
    {

       if($nacionalidad==null)
            return null;    ;
        if(strcmp($nacionalidad,'ARG'))
            $nacionalidad='Argentina';
        return $helper->directPic('genericfiles'.DIRECTORY_SEPARATOR,$nacionalidad.'.jpg');
    }

    function imgFacilidadParto($helper,$facilidad){
      if($facilidad==null)
          return null;
        return $helper->directPic('genericfiles'.DIRECTORY_SEPARATOR,'fp_'.$facilidad.'.png');
    }


}