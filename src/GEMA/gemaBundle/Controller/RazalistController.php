<?php
/**
 * Created by PhpStorm.
 * User: AK0
 * Date: 08/07/2017
 * Time: 15:17
 */

namespace GEMA\gemaBundle\Controller;
use GEMA\gemaBundle\Entity\Tabla;
use GEMA\gemaBundle\Entity\TablaDatos;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use GEMA\gemaBundle\Helpers\MyHelper;



class RazalistController extends Controller
{


    function listRazaAction($nombre)
    {

        $helper=new MyHelper();
        $em = $this->getDoctrine()->getManager();
        $r=$em->getRepository('gemaBundle:Razafather')->findOneByNombre($nombre);
        if($r!=null){
            $isfather=1;
            $id=$r->getId();

        }
        else{
            $isfather=0;
            $r2=$father=$em->getRepository('gemaBundle:Raza')->findOneByNombre($nombre);
            $id=$r2->getId();
        }
        if($isfather==1)
        {
            $father=$em->getRepository('gemaBundle:Razafather')->find($id);
            $razas=$father->getRazas();

            $toros=$em->getRepository('gemaBundle:Toro')->torosbyRazas($razas);

       //     print(count($toros));die();
          //  $toros=$em->getRepository('gemaBundle:Toro')->torosbyRazas($razas);
         //   print_r(count($toros));die();
            $nombreraza=$father->getNombre();
            $tablas=array();
            $mocho=false;
            foreach($razas as $r){
                $tablasch=$r->getTipotabla()->getTablas();
                foreach($tablasch as $t)
                {
                    $torobytabla=$em->getRepository('gemaBundle:Toro')->torosPublicosByRazaAndTabla($r->getId(),$t->getNombre());
                    if($torobytabla !=null && count($torobytabla)>0)
                    {
                        $t->toros=$torobytabla;
                        $tablas[]=$t;
                    }
                }
                if($r->getMocho()==true)
                    $mocho=true;
            }
            $fathersmenu=$em->getRepository('gemaBundle:Razafather')->findbynotId($id);
            $razasmenu=$em->getRepository('gemaBundle:Raza')->findBy(array(
                'father'=>null,
                'tiporaza'=>1
            ));
            $otherrazas=null;
        }
        else{
             $raza=$em->getRepository('gemaBundle:Raza')->find($id);
             $toros=$em->getRepository('gemaBundle:Toro')->torosbyRazaP($raza);

            $nombreraza=$raza->getNombre();
            if( $raza->getTipotabla() !=null){
                $tablas=$raza->getTipotabla()->getTablas();
                foreach($tablas as $t){
                    $torobytabla=$em->getRepository('gemaBundle:Toro')->torosPublicosByRazaAndTabla($raza->getId(),$t->getNombre());
                    if($torobytabla !=null && count($torobytabla)>0)
                    {
                        $t->toros=$torobytabla;
                    }
                }

            }
            else
                $tablas=null;

            $mocho=$raza->getMocho();
            $fathersmenu=$em->getRepository('gemaBundle:Razafather')->findAll();
            $razasmenu=$em->getRepository('gemaBundle:Raza')->Notid($id,$raza->getTiporaza()->getId());
            if($raza->getId()==26){
                $conn = $this->get('database_connection');
                $consulta = 'SELECT DISTINCT nombreraza from toro where raza_id=26';
                $otherrazasm = $conn->fetchAll($consulta);
               for($i=0;$i<count($otherrazasm);$i++){
                   if($otherrazasm[$i]['nombreraza']==null or $otherrazasm[$i]['nombreraza']=='')
                       $otherrazasm[$i]['nombreraza']='Sin Raza Definida';
               }

                $otherrazas=$otherrazasm;

            }
            else
                $otherrazas=null;
        }


        foreach($toros as $toro)
        {
            if($toro->getPublico()==0)
                unset($toro);
            else{
                $img=$helper->randomPic('toro'.DIRECTORY_SEPARATOR.$toro->getGuid().'P'.DIRECTORY_SEPARATOR,true);
                if($img==null)
                    $img=$helper->directPic('genericfiles'.DIRECTORY_SEPARATOR,'toro.png',true);

                $toro->imgprincipal=$img;


                if($toro->getNuevo()==true)
                    $toro->nuevoflag='<span style="padding: 3px;background: red;color: white;"><strong>Nuevo</strong></span>';
                else
                    $toro->nuevoflag=null;
                $toro->facilidadglag=$this->imgFacilidadParto($helper,$toro->getFacilidadparto());
                $toro->nacionalidadflag=$this->Nacionalidad($helper,$toro->getNacionalidad());
                $toro->conceptplusflag=$this->ConceptPlus($helper,$toro->getCP());
                $toro->tablasflag=json_decode($toro->getTablagenetica(),true);
            }




        }

//        print(count($toros));   print( '<br>');
//        foreach($toros as $t){
//            print( $t->imgprincipal);
//            print( '<br>');
//        }
//        die();
//


        return $this->render('gemaBundle:Page:tablaraza.html.twig', array(
                'toros'=>$toros,
                'razaname'=>$nombreraza,
                'tablas'=>$tablas,
                'mocho'=>$mocho,
                'fathersmenu'=>$fathersmenu,
                'razasmenu'=>$razasmenu,
                'otherrazas'=>$otherrazas,


            )
        );
    }


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

    function toroDetailAction($apodo){

        $em = $this->getDoctrine()->getManager();
        $toro=$em->getRepository('gemaBundle:Toro')->findOneByApodo($apodo);

        $razas=$em->getRepository('gemaBundle:Raza')->findallBut($toro->getRaza()->getId(),
            $toro->getRaza()->getTipoRaza()->getId());

        $helper=new MyHelper();
        $img=$helper->randomPic('toro'.DIRECTORY_SEPARATOR.$toro->getGuid().'P'.DIRECTORY_SEPARATOR);


          $razafather=$toro->getRaza()->getFather();
        if($razafather==null)
            $razafather=$toro->getRaza();

          $fathersmenu=$em->getRepository('gemaBundle:Razafather')->findAll();

          $razasmenu=$em->getRepository('gemaBundle:Raza')->findBy(array(
              'father'=>null,
              'tiporaza'=>1
          ));

        if($img==null){
            $img=$helper->directPic('genericfiles'.DIRECTORY_SEPARATOR,'toro.png');
            $pricimgdesc='';
        }

       else{
           $vowels = array("_small","_large","_medium");
           $img=str_replace($vowels,"",$img);
           $data= explode(DIRECTORY_SEPARATOR,$img);
//
//           $vowels = array("_small","_large","_medium");
//           $nombrefoto=str_replace($vowels,"",$data[2]);
           $descripcionprinc=$em->getRepository('gemaBundle:MediaDescription')-> findOneBy(
               array(

                   'nombre'=>$data[2],
                   'folder'=>$data[0],
                   'subforlder'=>$data[1]

               )

               );


           if($descripcionprinc!=null)
            $pricimgdesc=$descripcionprinc->getDescripcion();
           else
               $pricimgdesc='';
       }

        $imgfp=$this->imgFacilidadParto($helper,$toro->getFacilidadparto());
        $imgcp=$this->ConceptPlus($helper,$toro->getCP());
        $silueta=$helper->directPic('genericfiles'.DIRECTORY_SEPARATOR,$toro->getRaza()->getSilueta().'.jpg');
        if($toro->getRaza()->getTablasmanual()!=true)
        {
            $tablasflag=json_decode($toro->getTablagenetica(),true);
            $tablarname=$em->getRepository('gemaBundle:Tabla')->find($toro->getTipotablaselected());
            if(isset($tablasflag[$tablarname->getNombre()])){
                $tablasflag=$tablasflag[$tablarname->getNombre()];
                $tabla=$tablarname;
            }


           else{
               $nkey='';
               foreach($tablasflag as $key=>$tabla){
                   $tablasflag=$tablasflag[$key];$nkey=$key;break;
               }
               $tabla=$em->getRepository('gemaBundle:Tabla')->findOneBy(array(
                  'nombre'=>$nkey
               ));
           }

            $tablagennombre=$tablarname->getNombre();
        }
        else{
            $tablasflag=null;
            $tablagennombre=null;
            $tabla=null;

            if($toro->getTablagenetica()!=null){
                $datos=json_decode($toro->getTablagenetica(),true);
                $tablaname=array_keys($datos);
                $tablarname=$tablaname[0];
                $columnas= array_keys($datos[$tablarname][0]);
                $tablasflag=$datos[$tablarname];


                $tabla=new Tabla();
                foreach($columnas as $col){
                    if($col!='rowhead'){
                        $td=new TablaDatos();
                        $td->setNombre($col);
                        $tabla->addTabladato($td);
                    }
                }
            }
        }


        $mediaInpage=array();
        if(count($toro->getYoutubes())>0)
        foreach($toro->getYoutubes() as $y)
        {
            $mediaInpage[]=array(
                'tipo'=>'video',
                'url'=> $y->getUrl(),
                'representacion'=>$helper->videosPic($y->getUrl()),
                'descripcion'=>''
            );
        }

        $mediatoro=$helper->filesInFolder('toro'.DIRECTORY_SEPARATOR.$toro->getGuid().DIRECTORY_SEPARATOR,true);
        if(count($mediatoro)>0)
        {
            sort($mediatoro, SORT_NATURAL | SORT_FLAG_CASE);
            foreach($mediatoro as $mt){
                $mediaInpage[]=array(
                    'tipo'=>'img',
                    'url'=> $mt,
                    'representacion'=>$mt,
                    'descripcion'=>$this->getDesc(explode(DIRECTORY_SEPARATOR,$mt))
                );
            }
        }

        $lis=array();

        if(count($mediaInpage)>0)
        {

        //    print_r($mediaInpage);die();
           // $claves_aleatorias = array_rand($mediaInpage, count($mediaInpage));

          //  print_r($claves_aleatorias);die();

           // $finalmedia=$mediaInpage;
//            if($claves_aleatorias!==0){
//                foreach($claves_aleatorias as $clave)
//                {
//                    $finalmedia[]=$mediaInpage[$clave];
//                }
//            }else{
//                $finalmedia=$claves_aleatorias;
//            }

            $flag=0;
            $flaadd=0;

           // shuffle ($mediaInpage);
            for($i=0;$i<count($mediaInpage);$i++)
            {
                if($mediaInpage[$i]['tipo']=='video')
                {
                    $lis[$flaadd][]=array(

                        'rep'=>$mediaInpage[$i]['representacion'],
                        'url'=>$mediaInpage[$i]['url'],
                        'descripcion'=>''

                    );
                }
                else{
                    $lis[$flaadd][] =array(

                        'rep'=>$mediaInpage[$i]['representacion'],
                        'url'=>$mediaInpage[$i]['representacion'],
                        'descripcion'=>$mediaInpage[$i]['descripcion'],

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
                'tablagenetica'=>$tablasflag,
                'tabla'=>$tabla,
                'tablagennombre'=>$tablagennombre,
                'mediatoro'=>$lis,
                'razas'=>$razas,
                'father'=>$razafather,
                'fathersmenu'=>$fathersmenu,
                'razasmenu'=>$razasmenu,
                'razaname'=>   $toro->getRaza()->getNombre(),
                'pricimgdesc'=>$pricimgdesc



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


    public function searchcarnev2Action($dato){

        $em = $this->getDoctrine()->getManager();
        $helper=new MyHelper();
        $toros=$em->getRepository('gemaBundle:Toro')->torosbyLike($dato);
        if(count($toros)==0)
            return new JsonResponse(0);
        $bulls=array();
        foreach($toros as $t){
            $img=$helper->randomPic('toro'.DIRECTORY_SEPARATOR.$t->getGuid().'P'.DIRECTORY_SEPARATOR,true);
            if($img==null)
                $img=$helper->directPic('genericfiles'.DIRECTORY_SEPARATOR,'toro.png',true);
         $bulls[]=array(
             'id'=>$t->getId(),
             'apodo'=>$t->getApodo(),
             'nombreraza'=>$t->getRaza()->getNombre(),
             'nombretoro'=>$t->getNombre(),
             'imagen'=>DIRECTORY_SEPARATOR.'web'.DIRECTORY_SEPARATOR.$img
             );
        }
        return new JsonResponse($bulls);

    }



    function ConceptPlus($helper,$cp){
        if($cp==false)
            return null;
        return $helper->directPic('genericfiles'.DIRECTORY_SEPARATOR,'conceptPlus.png');
    }

    function Nacionalidad($helper,$nacionalidad)
    {

       if($nacionalidad==null)
            return null;
        $nacionalidad=str_replace(' ', '', $nacionalidad);

        return $helper->directPic('genericfiles'.DIRECTORY_SEPARATOR,$nacionalidad.'.jpg');
    }

    function imgFacilidadParto($helper,$facilidad){
      if($facilidad==null)
          return null;
        return $helper->directPic('genericfiles'.DIRECTORY_SEPARATOR,'fp_'.$facilidad.'.png');
    }


}