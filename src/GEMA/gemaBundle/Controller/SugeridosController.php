<?php

namespace GEMA\gemaBundle\Controller;

use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use GEMA\gemaBundle\Helpers\MyHelper;
use GEMA\gemaBundle\Entity\Tabla;
use GEMA\gemaBundle\Entity\TablaDatos;
use Doctrine\ORM\QueryBuilder;

/**
 * Sugeridos controller.
 *
 */
class SugeridosController extends Controller
{

    /**
     * Lists all Boletin entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('gemaBundle:Toro');
        $toros=$repo->findAll();
        return $this->render('gemaBundle:Sugeridos:index.html.twig', array(
                    'toros' => $toros,
        ));
    }


    public function updateallAction(){

        try{
            set_time_limit(0);
            $em = $this->getDoctrine()->getManager();
            $repo = $em->getRepository('gemaBundle:Toro');
            $claves=$em->getRepository('gemaBundle:Configuracion')->find(1)->getPalabrasclave();
            $queryEntities1 = $em->createQuery('select t from gemaBundle:Toro t');
            $iterableEntities1 = $queryEntities1->iterate();
            while (($toro = $iterableEntities1->next()) !== false) {
                //$this->UpdateToro($toro[0]);
                if($toro[0]->getRaza()->getId()==15 || ($toro[0]->getRaza()->getId()==26 && $toro[0]->getMocho()==true))
                    $this->UpdateToroV2Mocho($toro[0],$claves,$repo,$em);
                else
                    $this->UpdateToroV2($toro[0],$claves,$repo,$em);

                $em->detach($toro[0]);
            }
            return new JsonResponse(

                array(
                    0=>1
                )
            ) ;

        }catch(\Exception $e){

            return new JsonResponse(

                array(
                    0=>$e->getMessage()
                )
            ) ;
        }
    }


    public function updateoneAction($id){

        try
        {
            set_time_limit(0);
            $em = $this->getDoctrine()->getManager();
            $repo = $em->getRepository('gemaBundle:Toro');
            $claves=$em->getRepository('gemaBundle:Configuracion')->find(1)->getPalabrasclave();
            $toro=$repo->find($id);
            if($toro->getRaza()->getId()==15 || ($toro->getRaza()->getId()==26 && $toro->getMocho()==true))
                $this->UpdateToroV2Mocho($toro,$claves,$repo,$em);
            else{
                $this->UpdateToroV2($toro,$claves,$repo,$em);
            }


            return new JsonResponse(

                array(
                    0=>1
                )
            ) ;

        }catch(\Exception $e){

            return new JsonResponse(

                array(
                    0=>$e->getMessage()
                )
            ) ;
        }

    }



     function UpdateToro($toro){

         $em = $this->getDoctrine()->getManager();
         $repo = $em->getRepository('gemaBundle:Toro');
         $sugeridosactuales=$toro->getTorosSugeridos();
         foreach($sugeridosactuales as $s)
             $toro->removeTorosSugerido($s);

         if($toro->getCP()==true){ //SI el toro es CP

             if($toro->getRaza()->getFather()!=null){

                 $qb = new QueryBuilder($em);
                 $qb
                     ->select("T","R","P")
                     ->from('gemaBundle:Toro', "T")
                     ->leftJoin('T.raza', "R")
                     ->leftJoin('R.father', "P")
                     ->Where("T.publico=1")
                     ->Where("T.CP=1");
                 $qb  ->andWhere('P.id='.$toro->getRaza()->getFather()->getId());
                 $qb  ->andWhere('T.id<>'.$toro->getId());
                 $toroscp=$qb->getQuery()->getResult();
             }
             else
             $toroscp=$repo->findBy(
                 array(
                     'CP'=>true,
                     'raza'=>$toro->getRaza(),
                     'publico'=>true

                 )
             );
             foreach($toroscp as $cptoro){
                 if($cptoro->getid()!=$toro->getId())
                 $toro->setToroSugerido($cptoro);
             }
             unset($toroscp);
         }

           //Facilidad de Parto
        $torofp=$repo->torosByFPnotCPnotMyId($toro->getRaza()->getFather(),$toro->getFacilidadparto(),$toro->getRaza()->getId(),$toro->getId(),$toro);

         foreach($torofp as $fptoro)
                 $toro->setToroSugerido($fptoro);
         unset($torofp);
         //Mocho
         if($toro->getMocho()==true){
             $toromocho=$repo->torosMochoNotCpNotMyFacPartoNotMyId($toro->getFacilidadparto(),$toro->getRaza()->getId(),$toro->getId(),$toro->getRaza()->getFather(),$toro);
             foreach($toromocho as $mochotoro)
                 $toro->setToroSugerido($mochotoro);
             unset($toromocho);

         }

         //PD < 30%
            $pdDato=$this->getDatoFromTablaGen($toro,'RANKING','PD');
             if($pdDato!=null ){
                  $torosg =$this->restantesRaza($toro,$repo,$em);
                  foreach($torosg as $torog){
                      $p=$this->getDatoFromTablaGen($torog,'RANKING','PD');
                      if($p<=30)
                      { $toro->setToroSugerido($torog);}
                  }
                 unset($torosg);
             }
         //PF

         $pF=$this->getDatoFromTablaGen($toro,'RANKING','PF');
         if($pF!=null and $pF<=30){
             $torosg  =$this->restantesRaza($toro,$repo,$em);
             foreach($torosg as $torog){
                 $p=$this->getDatoFromTablaGen($torog,'RANKING','PF');
                 if($p<=30)
                 { $toro->setToroSugerido($torog);}
             }
             unset($torosg);
         }
         //P.Año
         $pAnno=$this->getDatoFromTablaGen($toro,'RANKING','P.Año');
         if($pAnno!=null and $pAnno<=30){
             $torosg  =$this->restantesRaza($toro,$repo,$em);
             foreach($torosg as $torog){
                 $p=$this->getDatoFromTablaGen($torog,'RANKING','P.Año');
                 if($p<=30)
                 { $toro->setToroSugerido($torog);}
             }
             unset($torosg);
         }
         //Cabaña
         $descripc=$toro->getDescripcion();
         if($descripc!=null){
             $claveesgen=$em->getRepository('gemaBundle:Configuracion')->find(1)->getPalabrasclave();
             $clavesgenarray=explode(',',$claveesgen);
             $myclavesarray=array();
             foreach($clavesgenarray as $clave)
                 if (strpos($descripc, $clave) !== false)
                    $myclavesarray[]=$clave;

             $restantes=$this->restantesRaza($toro,$repo,$em);
             if(count($myclavesarray)>0){
                 foreach($restantes as $tororest){

                     foreach($myclavesarray as $myclave){
                         if($this->findClaveinToro($tororest,$myclave))
                             $toro->setToroSugerido($tororest);
                     }
                }
             }
             unset($restantes);
         }
         //Especifico
         if($toro->getRaza()->getFather()!=null){
             if($toro->getRaza()->getFather()->getId()==3){//Aberdeen Angus Negro

                 $anguscolorado=$repo->getHijosfromRazaPadreMismaFP(4,$toro->getFacilidadparto());

                 foreach($anguscolorado as $toroangus){
                     $toro->setToroSugerido($toroangus);
                 }
             }

             else if($toro->getRaza()->getFather()->getId()==4){//Aberdeen Angus colorado

                 $anguscolorado=$repo->getHijosfromRazaPadreMismaFP(3,$toro->getFacilidadparto());
                 foreach($anguscolorado as $toroangus){
                     $toro->setToroSugerido($toroangus);
                 }
             }

             else if($toro->getRaza()->getFather()->getId()==5){//Aberdeen Angus colorado

                 $anguscolorado=$repo->getHijosfromRazaPadreMismaFP(6,$toro->getFacilidadparto());
                 foreach($anguscolorado as $toroangus){
                     $toro->setToroSugerido($toroangus);
                 }
             }

             else if($toro->getRaza()->getFather()->getId()==6){//Aberdeen Angus colorado

                 $anguscolorado=$repo->getHijosfromRazaPadreMismaFP(5,$toro->getFacilidadparto());
                 foreach($anguscolorado as $toroangus){
                     $toro->setToroSugerido($toroangus);
                 }
             }
         }
         $em->persist($toro);
         $em->flush();
     }

     function UpdateToroV2Mocho($toro,$claves,$repo,$em){

         //Limpiando sugeridos
         $sugeridosactuales=$toro->getTorosSugeridos();
         foreach($sugeridosactuales as $s)
             $toro->removeTorosSugerido($s);

         $myclaves=$this->PalabrasClave($toro,$claves);
         if(count($myclaves)>0)
             $myclaves=implode(',',$myclaves);
         else
             $myclaves=null;

         //Caso 1
        if($toro->getCP()==true && $this->getDatoFromTablaGenMenor30($toro,'RANKING','PD')==true
             && ($this->getDatoFromTablaGenMenor30($toro,'RANKING','PF')|| $this->getDatoFromTablaGenMenor30($toro,'RANKING','P.Año')) &&
          $this->TienePalabrasClave($toro,$claves) && $toro->getMocho()==true && $toro->getCriador()!=null)

         {
             //Sugeridos 1 FP 15 MESES y CONCEPT PLUS Y MOCHO y <30% PD y <30% PF o P. Año  y  CABAÑA Y CRIADOR
             $params=array(
                 'CP'=>1,
                 'facilidadparto'=>$toro->getFacilidadparto(),
                 'mocho'=>$toro->getMocho(),
                 'criador'=>"'".$toro->getCriador()."'"

             );
             $sugeridos=  $repo->DinamycGet($toro,$params);
             $sugeridos=  $this->removeNoTablaGenMenor30($sugeridos,'PD');
             $sugeridos=  $this->removeNoTablaGenMenor30($sugeridos,'PF');
             $sugeridos=  $this->removeNoTablaGenMenor30($sugeridos,'P.Año');
             $sugeridos=$this->removeNotKeyWords($myclaves,$sugeridos);
             $this->setSugeridos($toro,$sugeridos);

             //Sugeridos_2 (FP 15 MESES y CONCEPT PLUS Y MOCHO y <30% PD y <30% PF o P. Año  y  CABAÑA) Eliminado Criador
             $params2=array(
                 'CP'=>1,
                 'facilidadparto'=>$toro->getFacilidadparto(),
                 'mocho'=>$toro->getMocho(),
             );
             $sugeridos2= $sugeridos5Base=$repo->DinamycGet($toro,$params2);
             $sugeridos2= $sugeridos4base= $this->removeNoTablaGenMenor30($sugeridos2,'PD'); //Toado punto partida sugeridos 4
             $sugeridos2=  $this->removeNoTablaGenMenor30($sugeridos2,'PF');
             $sugeridos2=$sugeridos3Base= $this->removeNoTablaGenMenor30($sugeridos2,'P.Año');//Tomando sugeridos2base como punto de partida para sugeridos3
             $sugeridos2=$this->removeNotKeyWords($myclaves,$sugeridos2);
             $sugeridos2=$this->removeArrayAfromBV2($sugeridos2,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos2);

             //Sugeridos_3 FP 15 MESES y CONCEPT PLUS Y MOCHO y <30% PD y <30% PF o P. Año ---Elimina CABAÑA
             $sugeridos3=$this->removeArrayAfromBV2($sugeridos3Base,$sugeridos2);
             $this->setSugeridos($toro,$sugeridos3);

             //Sugeridos_4 FP 15 MESES y CONCEPT PLUS Y MOCHO y <30% PD. --Eliminado <30% PF o P. Año y CABAña
             $sugeridos4=$this->removeArrayAfromBV2($sugeridos4base,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos4);

             //Sugeridos_5 FP 15 MESES y CONCEPT PLUS Y MOCHO --Elimina <30% PD y <30% PF o P. Año y CABAña
             $this->setSugeridos($toro,$this->removeArrayAfromBV2($sugeridos5Base,$toro->getTorosSugeridos()));

             //Sugeridos_6 FP 15 MESES y CONCEPT PLUS --Elimina mocho,<30% PD y <30% PF o P. Año y CABAña
             $params3=array(
                 'CP'=>1,
                 'facilidadparto'=>$toro->getFacilidadparto()
             );
             $sugeridos6= $repo->DinamycGet($toro,$params3);
             $this->setSugeridos($toro,$this->removeArrayAfromBV2($sugeridos6,$toro->getTorosSugeridos()));

             //Sugeridos_7 FP 15 MESES y <30% PD y <30% PF o P. Año
             $params4=array(
                 'facilidadparto'=>$toro->getFacilidadparto()
             );
             $sugeridos7=$sugeridos8Base=$sugeridos9Base=$repo->DinamycGet($toro,$params4);
             $sugeridos7=  $this->removeNoTablaGenMenor30($sugeridos7,'PD');
             $sugeridos7=  $this->removeNoTablaGenMenor30($sugeridos7,'PF');
             $sugeridos7=  $this->removeNoTablaGenMenor30($sugeridos7,'P.Año');
             $sugeridos7=$this->removeArrayAfromBV2($sugeridos7,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos7);

             //Sugeridos_8 FP 15 MESES y <30% PD
             $sugeridos8=$this->removeNoTablaGenMenor30($sugeridos8Base,'PD');
             $sugeridos8=$this->removeArrayAfromBV2($sugeridos8,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos8);

             //Sugeridos_9 FP 15 MESES y <30% PF o P. Año
             $sugeridos9=$this->removeNoTablaGenMenor30($sugeridos9Base,'PF');
             $sugeridos9=$this->removeNoTablaGenMenor30($sugeridos9,'P.Año');
             $sugeridos9=$this->removeArrayAfromBV2($sugeridos9,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos9);

             //Sugeridos_10 FP 15 MESES y MOCHO
             $params5=array(
                 'mocho'=>$toro->getMocho(),
                 'facilidadparto'=>$toro->getFacilidadparto()
             );
             $sugeridos10= $repo->DinamycGet($toro,$params5);
             $sugeridos10=$this->removeArrayAfromBV2($sugeridos10,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos10);

             //Sugeridos_11 FP 15 MESES y CRIADOR
             $params6=array(
                 'criador'=>"'".$toro->getCriador()."'",
                 'facilidadparto'=>$toro->getFacilidadparto()
             );
             $sugeridos11=$repo->DinamycGet($toro,$params6);
             $sugeridos11=$this->removeArrayAfromBV2($sugeridos11,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos11);

             //Especifico SIN FP
             if($toro->getFacilidadparto()==0 || $toro->getFacilidadparto()==null){

                 $params=array(
                     'facilidadparto'=>24
                 );
                 $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                 $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                 $this->setSugeridos($toro,$sugeridosFPMenor);

                 $params=array(
                     'facilidadparto'=>18
                 );
                 $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                 $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                 $this->setSugeridos($toro,$sugeridosFPMenor);

             }

             //Sugeridos_12 15 MESES
             $params7=array(
                 'facilidadparto'=>$toro->getFacilidadparto()

             );
             $sugeridos12=$repo->DinamycGet($toro,$params7);
             $sugeridos12=$this->removeArrayAfromBV2($sugeridos12,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos12);

             //Especifico
             if($toro->getFacilidadparto()==18){

                 $params=array(
                     'facilidadparto'=>15
                 );
                 $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                 $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                 $this->setSugeridos($toro,$sugeridosFPMenor);
             }else if($toro->getFacilidadparto()==24){

                 $params=array(
                     'facilidadparto'=>18
                 );
                 $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                 $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                 $this->setSugeridos($toro,$sugeridosFPMenor);

             }

             //Sugeridos_13 MOCHO Y CRIADOR
             $params8=array(
                 'criador'=>"'".$toro->getCriador()."'",
                 'mocho'=>$toro->getMocho(),
             );
             $sugeridos13=$repo->DinamycGet($toro,$params8);
             $sugeridos13=$this->removeArrayAfromBV2($sugeridos13,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos13);

             //Sugeridos_14
             $params9=array(
                 'mocho'=>$toro->getMocho()
             );
             $sugeridos14=$repo->DinamycGet($toro,$params9);
             $sugeridos14=$this->removeArrayAfromBV2($sugeridos14,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos14);

             //Sugeridos_15 CABAÑA
             $params10=array();
             $sugeridos15=$repo->DinamycGet($toro,$params10);
             $sugeridos15=$this->removeArrayAfromBV2($sugeridos15,$toro->getTorosSugeridos());
             $sugeridos15=$this->removeNotKeyWords($myclaves,$sugeridos15);
             $sugeridos15=$this->removeArrayAfromBV2($sugeridos15,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos15);

             //Sugeridos_16 CRIADOR
             $params11=array(
                 'criador'=>"'".$toro->getCriador()."'",
             );
             $sugeridos16=$repo->DinamycGet($toro,$params11);
             $sugeridos16=$this->removeArrayAfromBV2($sugeridos16,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos16);
         }

        // Caso2 FP 15 MESES	CONCEPT PLUS	<30% PF o P. Año	CABAÑA	MOCHO	CRIADOR
         else if($toro->getCP()==true
             && ($this->getDatoFromTablaGenMenor30($toro,'RANKING','PF')|| $this->getDatoFromTablaGenMenor30($toro,'RANKING','P.Año')) &&
             $this->TienePalabrasClave($toro,$claves) && $toro->getMocho()==true && $toro->getCriador()!=null)

         {
             //Sugeridos_1 FP 15 MESES y CONCEPT PLUS Y MOCHO y <30% PF o P. Año  y  CABAÑA Y CRIADOR
             $params=array(
                 'CP'=>1,
                 'facilidadparto'=>$toro->getFacilidadparto(),
                 'mocho'=>$toro->getMocho(),
                 'criador'=>"'".$toro->getCriador()."'"
             );
             $sugeridos=  $repo->DinamycGet($toro,$params);
             $sugeridos=  $this->removeNoTablaGenMenor30($sugeridos,'PF');
             $sugeridos=  $this->removeNoTablaGenMenor30($sugeridos,'P.Año');
             $sugeridos=$this->removeNotKeyWords($myclaves,$sugeridos);
             $this->setSugeridos($toro,$sugeridos);

             // Sugeridos_2 FP 15 MESES y CONCEPT PLUS Y MOCHO y <30% PF o P. Año  y  CABAÑA
             $params=array(
                 'CP'=>1,
                 'facilidadparto'=>$toro->getFacilidadparto(),
                 'mocho'=>$toro->getMocho(),
             );

             $sugeridos2=$sugeridos4base=$repo->DinamycGet($toro,$params);
             $sugeridos2=  $this->removeNoTablaGenMenor30($sugeridos2,'PF');
             $sugeridos2=$sugeridos3Base=$this->removeNoTablaGenMenor30($sugeridos2,'P.Año');//Tomando sugeridos2base como punto de partida para sugeridos3
             $sugeridos2=$this->removeNotKeyWords($myclaves,$sugeridos2);
             $sugeridos2=$this->removeArrayAfromBV2($sugeridos2,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos2);
             //Sugeridos_3 FP 15 MESES y CONCEPT PLUS Y MOCHO y <30% PF o P. Año
             $sugeridos3=$this->removeArrayAfromBV2($sugeridos3Base,$sugeridos2);
             $this->setSugeridos($toro,$sugeridos3);

             //Sugeridos_4 FP 15 MESES y CONCEPT PLUS Y MOCHO
             $sugeridos4=$this->removeArrayAfromBV2($sugeridos4base,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos4);

             //Sugeridos_5 FP 15 MESES y CONCEPT PLUS
             $params=array(
                 'CP'=>1,
                 'facilidadparto'=>$toro->getFacilidadparto()

             );
             $sugeridos5=$repo->DinamycGet($toro,$params);
             $sugeridos5=$this->removeArrayAfromBV2($sugeridos5,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos5);

             //Sugeridos_6 FP 15 MESES y <30% PF o P. Año
             $params=array(
                 'facilidadparto'=>$toro->getFacilidadparto()
             );
             $sugeridos6=$sugeridos9Base=$repo->DinamycGet($toro,$params);
             $sugeridos6=  $this->removeNoTablaGenMenor30($sugeridos6,'PF');
             $sugeridos6=  $this->removeNoTablaGenMenor30($sugeridos6,'P.Año');
             $sugeridos6=$this->removeArrayAfromBV2($sugeridos6,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos6);

             //Sugeridos_7 FP 15 MESES y MOCHO
             $params=array(
                 'facilidadparto'=>$toro->getFacilidadparto(),
                 'mocho'=>$toro->getMocho(),
             );
             $sugeridos7=$repo->DinamycGet($toro,$params);
             $sugeridos7=$this->removeArrayAfromBV2($sugeridos7,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos7);

             //Sugeridos8 FP 15 MESES y CRIADOR
             $params=array(
                 'facilidadparto'=>$toro->getFacilidadparto(),
                 'criador'=>"'".$toro->getCriador()."'"
             );
             $sugeridos8=$repo->DinamycGet($toro,$params);
             $sugeridos8=$this->removeArrayAfromBV2($sugeridos8,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos8);

             //Especifico SIN FP
             if($toro->getFacilidadparto()==0 || $toro->getFacilidadparto()==null){

                 $params=array(
                     'facilidadparto'=>24
                 );
                 $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                 $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                 $this->setSugeridos($toro,$sugeridosFPMenor);

                 $params=array(
                     'facilidadparto'=>18
                 );
                 $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                 $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                 $this->setSugeridos($toro,$sugeridosFPMenor);

             }

             //Sugeridos_9 15 MESES
             $sugeridos9=$this->removeArrayAfromBV2($sugeridos9Base,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos9);

             if($toro->getFacilidadparto()==18){

                 $params=array(
                     'facilidadparto'=>15
                 );
                 $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                 $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                 $this->setSugeridos($toro,$sugeridosFPMenor);
             }else if($toro->getFacilidadparto()==24){

                 $params=array(
                     'facilidadparto'=>18
                 );
                 $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                 $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                 $this->setSugeridos($toro,$sugeridosFPMenor);

             }

             //Sugeridos_10 MOCHO Y CRIADOR
             $params=array(
                 'criador'=>"'".$toro->getCriador()."'",
                 'mocho'=>$toro->getMocho(),
             );
             $sugeridos10=$repo->DinamycGet($toro,$params);
             $sugeridos10=$this->removeArrayAfromBV2($sugeridos10,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos10);

             //Sugeridos_11 MOCHO
             $params=array(
                 'criador'=>"'".$toro->getCriador()."'",
                 'mocho'=>$toro->getMocho(),
             );
             $sugeridos11=$repo->DinamycGet($toro,$params);
             $sugeridos11=$this->removeArrayAfromBV2($sugeridos11,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos11);

             //Sugeridos_12 CABAña
             $params=array(
             );

             $sugeridos12=$repo->DinamycGet($toro,$params);
             $sugeridos12=$this->removeNotKeyWords($myclaves,$sugeridos12);
             $sugeridos12=$this->removeArrayAfromBV2($sugeridos12,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos12);

             //Sugeridos_13 CRIADOR
             $params=array(
                 'criador'=>"'".$toro->getCriador()."'"
             );
             $sugeridos13=$repo->DinamycGet($toro,$params);
             $sugeridos13=$this->removeArrayAfromBV2($sugeridos13,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos13);

         }

         //Caso 3 FP 15 MESES y CONCEPT PLUS Y MOCHO y <30% PD y  CABAÑA Y CRIADOR
         elseif($toro->getCP()==true
             && $this->getDatoFromTablaGenMenor30($toro,'RANKING','PD') &&
             $this->TienePalabrasClave($toro,$claves) && $toro->getMocho()==true && $toro->getCriador()!=null)

         {
             //Sugeridos_1 FP 15 MESES y CONCEPT PLUS Y MOCHO y <30% PD y  CABAÑA Y CRIADOR
             $params=array(
                 'CP'=>1,
                 'facilidadparto'=>$toro->getFacilidadparto(),
                 'mocho'=>$toro->getMocho(),
                 'criador'=>"'".$toro->getCriador()."'"

             );
             $sugeridos=  $repo->DinamycGet($toro,$params);
             $sugeridos=  $this->removeNoTablaGenMenor30($sugeridos,'PD');
             $sugeridos=$this->removeNotKeyWords($myclaves,$sugeridos);
             $this->setSugeridos($toro,$sugeridos);

             //Sugeridos_2 FP 15 MESES y CONCEPT PLUS Y MOCHO y <30% PD y  CABAÑA
             $params=array(
                 'CP'=>1,
                 'facilidadparto'=>$toro->getFacilidadparto(),
                 'mocho'=>$toro->getMocho(),

             );
             $sugeridos2=$repo->DinamycGet($toro,$params);
             $sugeridos2=$sugeridos3Base=  $this->removeNoTablaGenMenor30($sugeridos2,'PD');
             $sugeridos2=$this->removeNotKeyWords($myclaves,$sugeridos2);
             $sugeridos2=$this->removeArrayAfromBV2($sugeridos2,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos2);

             //Sugeridos_3 FP 15 MESES y CONCEPT PLUS Y MOCHO y <30% PD
             $sugeridos3=$this->removeArrayAfromBV2($sugeridos3Base,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos3);

             //sugeridos_4 FP 15 MESES y CONCEPT PLUS Y MOCHO
             $params=array(
                 'CP'=>1,
                 'facilidadparto'=>$toro->getFacilidadparto(),
                 'mocho'=>$toro->getMocho(),

             );
             $sugeridos4=$repo->DinamycGet($toro,$params);
             $sugeridos4=$this->removeArrayAfromBV2($sugeridos4,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos4);

             //Sugeridos_5 FP 15 MESES y CONCEPT PLUS
             $params=array(
                 'CP'=>1,
                 'facilidadparto'=>$toro->getFacilidadparto(),

             );
             $sugeridos5=$repo->DinamycGet($toro,$params);
             $sugeridos5=$this->removeArrayAfromBV2($sugeridos5,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos5);

             //Sugeridos_6 FP 15 MESES y <30% PD
             $params=array(
                 'facilidadparto'=>$toro->getFacilidadparto(),
             );
             $sugeridos6=$repo->DinamycGet($toro,$params);
             $sugeridos6=$this->removeArrayAfromBV2($sugeridos6,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos6);

             //Sugeridos_7 FP 15 MESES y MOCHO
             $params=array(
                 'facilidadparto'=>$toro->getFacilidadparto(),
                 'mocho'=>$toro->getMocho(),

             );
             $sugeridos7=$repo->DinamycGet($toro,$params);
             $sugeridos7=$this->removeArrayAfromBV2($sugeridos7,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos7);

             //Especifico SIN FP
             if($toro->getFacilidadparto()==0 || $toro->getFacilidadparto()==null){

                 $params=array(
                     'facilidadparto'=>24
                 );
                 $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                 $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                 $this->setSugeridos($toro,$sugeridosFPMenor);

                 $params=array(
                     'facilidadparto'=>18
                 );
                 $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                 $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                 $this->setSugeridos($toro,$sugeridosFPMenor);

             }

             //Sugeridos_8 FP 15 MESES y CRIADOR
             $params=array(
                 'facilidadparto'=>$toro->getFacilidadparto(),
                 'criador'=>"'".$toro->getCriador()."'"

             );
             $sugeridos8=$repo->DinamycGet($toro,$params);
             $sugeridos8=$this->removeArrayAfromBV2($sugeridos8,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos8);


             if($toro->getFacilidadparto()==18){

                 $params=array(
                     'facilidadparto'=>15
                 );
                 $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                 $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                 $this->setSugeridos($toro,$sugeridosFPMenor);
             }else if($toro->getFacilidadparto()==24){

                 $params=array(
                     'facilidadparto'=>18
                 );
                 $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                 $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                 $this->setSugeridos($toro,$sugeridosFPMenor);

             }

             //Sugeridos_9 MOCHO Y CRIADOR
             $params=array(
                 'mocho'=>$toro->getMocho(),
                 'criador'=>"'".$toro->getCriador()."'"

             );
             $sugeridos9=$repo->DinamycGet($toro,$params);
             $sugeridos9=$this->removeArrayAfromBV2($sugeridos9,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos9);

             //Sugeridos_10 MOCHO
             $params=array(
                 'mocho'=>$toro->getMocho(),
             );
             $sugeridos10=$repo->DinamycGet($toro,$params);
             $sugeridos10=$this->removeArrayAfromBV2($sugeridos10,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos10);

             //Sugeridos_11 CABAÑA
             $params=array(
             );
             $sugeridos11=$repo->DinamycGet($toro,$params);
             $sugeridos11=$this->removeNotKeyWords($myclaves,$sugeridos11);
             $sugeridos11=$this->removeArrayAfromBV2($sugeridos11,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos11);

             //Sugeridos_12 CRIADOR
             $params=array(
                 'criador'=>"'".$toro->getCriador()."'"
             );
             $sugeridos12=$repo->DinamycGet($toro,$params);
             $sugeridos12=$this->removeArrayAfromBV2($sugeridos12,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos12);
         }

         //Caso 4 FP 15 MESES	CONCEPT PLUS	CABAÑA	MOCHO	CRIADOR
         elseif($toro->getCP()==true && $this->TienePalabrasClave($toro,$claves) && $toro->getMocho()==true && $toro->getCriador()!=null)
         {

             //Sugeridos_1 FP 15 MESES y CONCEPT PLUS Y MOCHO Y CABAÑA Y CRIADOR
             $params=array(
                 'CP'=>1,
                 'facilidadparto'=>$toro->getFacilidadparto(),
                 'mocho'=>$toro->getMocho(),
                 'criador'=>"'".$toro->getCriador()."'"

             );
             $sugeridos=  $repo->DinamycGet($toro,$params);
             $sugeridos=$this->removeNotKeyWords($myclaves,$sugeridos);
             $this->setSugeridos($toro,$sugeridos);

             //Sugeridos_2 FP 15 MESES y CONCEPT PLUS Y MOCHO Y CABAÑA
             $params=array(
                 'CP'=>1,
                 'facilidadparto'=>$toro->getFacilidadparto(),
                 'mocho'=>$toro->getMocho(),
             );
             $sugeridos2= $sugeridos3Base= $repo->DinamycGet($toro,$params);
             $sugeridos2=$this->removeNotKeyWords($myclaves,$sugeridos2);
             $sugeridos2=$this->removeArrayAfromBV2($sugeridos2,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos2);

             //Sugeridos_3 FP 15 MESES y CONCEPT PLUS Y MOCHO
             $sugeridos3=$this->removeArrayAfromBV2($sugeridos3Base,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos3);

             //Sugeridos_4 FP 15 MESES y CONCEPT PLUS
             $params=array(
                 'CP'=>1,
                 'facilidadparto'=>$toro->getFacilidadparto(),
             );
             $sugeridos4=  $repo->DinamycGet($toro,$params);
             $sugeridos4=$this->removeArrayAfromBV2($sugeridos4,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos4);

             //Sugeridos_5 FP 15 MESES y MOCHO
             $params=array(
                 'mocho'=>$toro->getMocho(),
                 'facilidadparto'=>$toro->getFacilidadparto(),
             );
             $sugeridos5=  $repo->DinamycGet($toro,$params);
             $sugeridos5=$this->removeArrayAfromBV2($sugeridos5,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos5);

             //Sugeridos_6 FP 15 MESES y CRIADOR
             $params=array(
                 'facilidadparto'=>$toro->getFacilidadparto(),
                 'criador'=>"'".$toro->getCriador()."'"
             );
             $sugeridos6=  $repo->DinamycGet($toro,$params);
             $sugeridos6=$this->removeArrayAfromBV2($sugeridos6,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos6);

             //Especifico SIN FP
             if($toro->getFacilidadparto()==0 || $toro->getFacilidadparto()==null){

                 $params=array(
                     'facilidadparto'=>24
                 );
                 $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                 $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                 $this->setSugeridos($toro,$sugeridosFPMenor);

                 $params=array(
                     'facilidadparto'=>18
                 );
                 $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                 $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                 $this->setSugeridos($toro,$sugeridosFPMenor);

             }

             //Sugeridos_7 15 MESES
             $params=array(
                 'facilidadparto'=>$toro->getFacilidadparto(),
             );
             $sugeridos7=  $repo->DinamycGet($toro,$params);
             $sugeridos7=$this->removeArrayAfromBV2($sugeridos7,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos7);

             if($toro->getFacilidadparto()==18){

                 $params=array(
                     'facilidadparto'=>15
                 );
                 $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                 $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                 $this->setSugeridos($toro,$sugeridosFPMenor);
             }else if($toro->getFacilidadparto()==24){

                 $params=array(
                     'facilidadparto'=>18
                 );
                 $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                 $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                 $this->setSugeridos($toro,$sugeridosFPMenor);

             }

             //Sugeridos_8 MOCHO Y CRIADOR
             $params=array(
                 'mocho'=>$toro->getMocho(),
                 'criador'=>"'".$toro->getCriador()."'"
             );
             $sugeridos8=  $repo->DinamycGet($toro,$params);
             $sugeridos8=$this->removeArrayAfromBV2($sugeridos8,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos8);

             //Sugeridos_9 MOCHO
             $params=array(
                 'mocho'=>$toro->getMocho(),
             );
             $sugeridos9=  $repo->DinamycGet($toro,$params);
             $sugeridos9=$this->removeArrayAfromBV2($sugeridos9,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos9);

             //Sugeridos_10 CABAÑA
             $params=array(

             );
             $sugeridos10=  $repo->DinamycGet($toro,$params);
             $sugeridos10=$this->removeNotKeyWords($myclaves,$sugeridos10);
             $sugeridos10=$this->removeArrayAfromBV2($sugeridos10,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos10);

             //Sugeridos_11 CRIADOR
             $params=array(
                 'criador'=>"'".$toro->getCriador()."'"
             );
             $sugeridos11=  $repo->DinamycGet($toro,$params);
             $sugeridos11=$this->removeArrayAfromBV2($sugeridos11,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos11);
         }

         //Caso 5 FP 15 MESES	<30% PD	<30% PF o P. Año	CABAÑA	MOCHO	CRIADOR

         elseif($this->getDatoFromTablaGenMenor30($toro,'RANKING','PD') &&
             ($this->getDatoFromTablaGenMenor30($toro,'RANKING','PF')|| $this->getDatoFromTablaGenMenor30($toro,'RANKING','P.Año'))
             && $this->TienePalabrasClave($toro,$claves) && $toro->getMocho()==true && $toro->getCriador()!=null)
         {
             //Sugerido_1 FP 15 MESES Y MOCHO y <30% PD y <30% PF o P. Año  y  CABAÑA Y CRIADOR
             $params=array(
                 'facilidadparto'=>$toro->getFacilidadparto(),
                 'mocho'=>$toro->getMocho(),
                 'criador'=>"'".$toro->getCriador()."'"

             );
             $sugeridos=  $repo->DinamycGet($toro,$params);
             $sugeridos=  $this->removeNoTablaGenMenor30($sugeridos,'PD');
             $sugeridos=  $this->removeNoTablaGenMenor30($sugeridos,'PF');
             $sugeridos=  $this->removeNoTablaGenMenor30($sugeridos,'P.Año');
             $sugeridos=$this->removeNotKeyWords($myclaves,$sugeridos);
             $this->setSugeridos($toro,$sugeridos);

             //Sugeridos_2 FP 15 MESES Y MOCHO y <30% PD y <30% PF o P. Año  y  CABAÑA
             $params=array(
                 'facilidadparto'=>$toro->getFacilidadparto(),
                 'mocho'=>$toro->getMocho(),

             );
             $sugeridos2=  $repo->DinamycGet($toro,$params);
             $sugeridos2=  $this->removeNoTablaGenMenor30($sugeridos2,'PD');
             $sugeridos2=  $this->removeNoTablaGenMenor30($sugeridos2,'PF');
             $sugeridos2=  $this->removeNoTablaGenMenor30($sugeridos2,'P.Año');
             $sugeridos2=$this->removeNotKeyWords($myclaves,$sugeridos2);
             $sugeridos2=$this->removeArrayAfromBV2($sugeridos2,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos2);

             //Sugeridos_3 FP 15 MESES Y MOCHO y <30% PD y <30% PF o P. Año

             $params=array(
                 'facilidadparto'=>$toro->getFacilidadparto(),
                 'mocho'=>$toro->getMocho(),

             );
             $sugeridos3=$sugeridos4Base=$sugeridos8Base= $repo->DinamycGet($toro,$params);
             $sugeridos3=  $this->removeNoTablaGenMenor30($sugeridos3,'PD');
             $sugeridos3=  $this->removeNoTablaGenMenor30($sugeridos3,'PF');
             $sugeridos3=  $this->removeNoTablaGenMenor30($sugeridos3,'P.Año');
             $sugeridos3=$this->removeArrayAfromBV2($sugeridos3,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos3);

             //Sugeridos_4 FP 15 MESES Y MOCHO y <30% PD
             $sugeridos4=$this->removeNoTablaGenMenor30($sugeridos4Base,'PD');
             $sugeridos4=$this->removeArrayAfromBV2($sugeridos4,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos4);

             //Sugeridos_5 FP 15 MESES y <30% PD y <30% PF o P. Año
             $params=array(
                 'facilidadparto'=>$toro->getFacilidadparto(),

             );
             $sugeridos5=$sugeridos7Base=$sugeridos10Base= $repo->DinamycGet($toro,$params);
             $sugeridos5= $sugeridos6Base= $this->removeNoTablaGenMenor30($sugeridos5,'PD');
             $sugeridos5=  $this->removeNoTablaGenMenor30($sugeridos5,'PF');
             $sugeridos5=  $this->removeNoTablaGenMenor30($sugeridos5,'P.Año');
             $sugeridos5=$this->removeArrayAfromBV2($sugeridos5,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos5);

             //Sugeridos_6 FP 15 MESES y <30% PD
             $sugeridos6=$this->removeArrayAfromBV2($sugeridos6Base,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos6);

             //Sugeridos_7 FP 15 MESES y <30% PF o P. Año
             $sugeridos7=$this->removeNoTablaGenMenor30($sugeridos7Base,'PF');
             $sugeridos7=$this->removeNoTablaGenMenor30($sugeridos7,'P.Año');
             $sugeridos7=$this->removeArrayAfromBV2($sugeridos7,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos7);

             //Sugeridos_8 FP 15 MESES y MOCHO
             $sugeridos8=$this->removeArrayAfromBV2($sugeridos8Base,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos8);

             //Sugeridos_9 FP 15 MESES y CRIADOR
             $params=array(
                 'facilidadparto'=>$toro->getFacilidadparto(),
                 'criador'=>"'".$toro->getCriador()."'"

             );
             $sugeridos9= $repo->DinamycGet($toro,$params);
             $sugeridos9=$this->removeArrayAfromBV2($sugeridos9,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos9);

             //Especifico SIN FP
             if($toro->getFacilidadparto()==0 || $toro->getFacilidadparto()==null){

                 $params=array(
                     'facilidadparto'=>24
                 );
                 $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                 $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                 $this->setSugeridos($toro,$sugeridosFPMenor);

                 $params=array(
                     'facilidadparto'=>18
                 );
                 $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                 $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                 $this->setSugeridos($toro,$sugeridosFPMenor);

             }

             //Sugeridos_10 FP 15 MESES
             $sugeridos10=$this->removeArrayAfromBV2($sugeridos10Base,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos10);

             if($toro->getFacilidadparto()==18){

                 $params=array(
                     'facilidadparto'=>15
                 );
                 $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                 $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                 $this->setSugeridos($toro,$sugeridosFPMenor);
             }else if($toro->getFacilidadparto()==24){

                 $params=array(
                     'facilidadparto'=>18
                 );
                 $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                 $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                 $this->setSugeridos($toro,$sugeridosFPMenor);

             }

             //Sugeridos_11 MOCHO Y CRIADOR
             $params=array(

                 'mocho'=>$toro->getMocho(),
                 'criador'=>"'".$toro->getCriador()."'"

             );
             $sugeridos11=  $repo->DinamycGet($toro,$params);
             $sugeridos11=$this->removeArrayAfromBV2($sugeridos11,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos11);

             //Sugeridos_12 MOCHO
             $params=array(
                 'mocho'=>$toro->getMocho(),
             );
             $sugeridos12=  $repo->DinamycGet($toro,$params);
             $sugeridos12=$this->removeArrayAfromBV2($sugeridos12,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos12);

             //Sugeridos_13 CABAÑA
             $params=array(
             );
             $sugeridos13=  $repo->DinamycGet($toro,$params);
             $sugeridos13=$this->removeArrayAfromBV2($sugeridos13,$toro->getTorosSugeridos());
             $sugeridos13=$this->removeNotKeyWords($myclaves,$sugeridos13);
             $this->setSugeridos($toro,$sugeridos13);

             //Sugeridos_14 CRIADOR
             $params=array(
                 'criador'=>"'".$toro->getCriador()."'"
             );
             $sugeridos14=  $repo->DinamycGet($toro,$params);
             $sugeridos14=$this->removeArrayAfromBV2($sugeridos14,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos14);
         }

         //Caso 6 FP 15 MESES	<30% PD	CABAÑA	MOCHO	CRIADOR

         elseif($this->getDatoFromTablaGenMenor30($toro,'RANKING','PD')
             && $this->TienePalabrasClave($toro,$claves) && $toro->getMocho()==true && $toro->getCriador()!=null)
         {
             //Sugeridos_1 FP 15 MESES Y MOCHO y <30% PD  y  CABAÑA Y CRIADOR
             $params=array(
                 'facilidadparto'=>$toro->getFacilidadparto(),
                 'mocho'=>$toro->getMocho(),
                 'criador'=>"'".$toro->getCriador()."'"

             );
             $sugeridos=  $repo->DinamycGet($toro,$params);
             $sugeridos=  $this->removeNoTablaGenMenor30($sugeridos,'PD');
             $sugeridos=$this->removeNotKeyWords($myclaves,$sugeridos);
             $this->setSugeridos($toro,$sugeridos);

             //Sugeridos_2 FP 15 MESES Y MOCHO y <30% PD  y  CABAÑA
             $params=array(
                 'facilidadparto'=>$toro->getFacilidadparto(),
                 'mocho'=>$toro->getMocho(),

             );
             $sugeridos2=  $repo->DinamycGet($toro,$params);
             $sugeridos2=  $this->removeNoTablaGenMenor30($sugeridos2,'PD');
             $sugeridos2=$this->removeNotKeyWords($myclaves,$sugeridos2);
             $sugeridos2=$this->removeArrayAfromBV2($sugeridos2,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos2);

             //Sugeridos_3 FP 15 MESES Y MOCHO y <30% PD
             $params=array(
                 'facilidadparto'=>$toro->getFacilidadparto(),
                 'mocho'=>$toro->getMocho(),

             );
             $sugeridos3= $sugeridos4Base=$repo->DinamycGet($toro,$params);
             $sugeridos3=  $this->removeNoTablaGenMenor30($sugeridos3,'PD');
             $sugeridos3=$this->removeArrayAfromBV2($sugeridos3,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos3);

             //Sugeridos_4 FP 15 MESES Y MOCHO
             $sugeridos4=$this->removeArrayAfromBV2($sugeridos4Base,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos4);

             //Sugeridos_5 FP 15 MESES y <30% PD
             $params=array(
                 'facilidadparto'=>$toro->getFacilidadparto(),
             );
             $sugeridos5=$sugeridos7Base=$repo->DinamycGet($toro,$params);
             $sugeridos5=  $this->removeNoTablaGenMenor30($sugeridos5,'PD');
             $sugeridos5=$this->removeArrayAfromBV2($sugeridos5,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos5);

             //Sugeridos_6 FP 15 MESES y CRIADOR
             $params=array(
                 'facilidadparto'=>$toro->getFacilidadparto(),
                 'criador'=>"'".$toro->getCriador()."'"
             );
             $sugeridos6=$repo->DinamycGet($toro,$params);
             $sugeridos6=$this->removeArrayAfromBV2($sugeridos6,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos6);

             //Especifico SIN FP
             if($toro->getFacilidadparto()==0 || $toro->getFacilidadparto()==null){

                 $params=array(
                     'facilidadparto'=>24
                 );
                 $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                 $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                 $this->setSugeridos($toro,$sugeridosFPMenor);

                 $params=array(
                     'facilidadparto'=>18
                 );
                 $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                 $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                 $this->setSugeridos($toro,$sugeridosFPMenor);

             }

             //Sugeridos_7 15 MESES
             $sugeridos7=$this->removeArrayAfromBV2($sugeridos7Base,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos7);

             if($toro->getFacilidadparto()==18){

                 $params=array(
                     'facilidadparto'=>15
                 );
                 $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                 $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                 $this->setSugeridos($toro,$sugeridosFPMenor);
             }else if($toro->getFacilidadparto()==24){

                 $params=array(
                     'facilidadparto'=>18
                 );
                 $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                 $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                 $this->setSugeridos($toro,$sugeridosFPMenor);

             }

             //Sugeridos_8 MOCHO Y CRIADOR
             $params=array(
                 'mocho'=>$toro->getMocho(),
                 'criador'=>"'".$toro->getCriador()."'"
             );
             $sugeridos8=$repo->DinamycGet($toro,$params);
             $sugeridos8=$this->removeArrayAfromBV2($sugeridos8,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos8);

             //Sugeridos_9 MOCHO
             $params=array(
                 'mocho'=>$toro->getMocho(),
             );
             $sugeridos9=$repo->DinamycGet($toro,$params);
             $sugeridos9=$this->removeArrayAfromBV2($sugeridos9,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos9);

             //Sugeridos_10 CABAÑA
             $params=array(
             );
             $sugeridos10=$repo->DinamycGet($toro,$params);
             $sugeridos10=$this->removeNotKeyWords($myclaves,$sugeridos10);
             $sugeridos10=$this->removeArrayAfromBV2($sugeridos10,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos10);

             //Sugeridos_11 CRIADOR
             $params=array(
                 'criador'=>"'".$toro->getCriador()."'"
             );
             $sugeridos11=$repo->DinamycGet($toro,$params);
             $sugeridos11=$this->removeArrayAfromBV2($sugeridos11,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos11);
         }

         // Caso 7 FP 15 MESES	<30% PF o P. Año	CABAÑA	MOCHO	CRIADOR
         elseif(($this->getDatoFromTablaGenMenor30($toro,'RANKING','PF')|| $this->getDatoFromTablaGenMenor30($toro,'RANKING','P.Año'))
             && $this->TienePalabrasClave($toro,$claves) && $toro->getMocho()==true && $toro->getCriador()!=null)
         {

             //Sugeridos_1 FP 15 MESES y <30% PF o P. Año y  CABAÑA Y MOCHO Y CRIADOR
             $params=array(
                 'facilidadparto'=>$toro->getFacilidadparto(),
                 'mocho'=>$toro->getMocho(),
                 'criador'=>"'".$toro->getCriador()."'"

             );
             $sugeridos=  $repo->DinamycGet($toro,$params);
             $sugeridos=  $this->removeNoTablaGenMenor30($sugeridos,'PF');
             $sugeridos=  $this->removeNoTablaGenMenor30($sugeridos,'P.Año');
             $sugeridos=$this->removeNotKeyWords($myclaves,$sugeridos);
             $this->setSugeridos($toro,$sugeridos);

             //Sugeridos_2 FP 15 MESES y <30% PF o P. Año y  CABAÑA Y MOCHO
             $params=array(
                 'facilidadparto'=>$toro->getFacilidadparto(),
                 'mocho'=>$toro->getMocho(),

             );
             $sugeridos2= $sugeridos5Base= $repo->DinamycGet($toro,$params);
             $sugeridos2=  $this->removeNoTablaGenMenor30($sugeridos2,'PF');
             $sugeridos2=  $this->removeNoTablaGenMenor30($sugeridos2,'P.Año');
             $sugeridos2=$this->removeNotKeyWords($myclaves,$sugeridos2);
             $sugeridos2=$this->removeArrayAfromBV2($sugeridos2,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos2);

             //Sugeridos_3 FP 15 MESES y <30% PF o P. Año y CABAÑA
             $params=array(
                 'facilidadparto'=>$toro->getFacilidadparto(),
             );
             $sugeridos3=  $repo->DinamycGet($toro,$params);
             $sugeridos3=  $this->removeNoTablaGenMenor30($sugeridos3,'PF');
             $sugeridos3=  $this->removeNoTablaGenMenor30($sugeridos3,'P.Año');
             $sugeridos3=$this->removeNotKeyWords($myclaves,$sugeridos3);
             $this->setSugeridos($toro,$sugeridos3);

             //Sugeridos_4 FP 15 MESES y <30% PF o P. Año
             $params=array(
                 'facilidadparto'=>$toro->getFacilidadparto(),
             );
             $sugeridos4= $sugeridos7Base= $repo->DinamycGet($toro,$params);
             $sugeridos4=  $this->removeNoTablaGenMenor30($sugeridos4,'PF');
             $sugeridos4=  $this->removeNoTablaGenMenor30($sugeridos4,'P.Año');
             $sugeridos4=$this->removeNotKeyWords($myclaves,$sugeridos4);
             $this->setSugeridos($toro,$sugeridos4);

             //Sugeridos_5 FP 15 MESES y MOCHO
             $sugeridos5=$this->removeNotKeyWords($myclaves,$sugeridos5Base);
             $this->setSugeridos($toro,$sugeridos5);

             //Sugeridos_6 FP 15 MESES y CRIADOR
             $params=array(
                 'facilidadparto'=>$toro->getFacilidadparto(),
                 'criador'=>"'".$toro->getCriador()."'"

             );
             $sugeridos6= $sugeridos5Base= $repo->DinamycGet($toro,$params);
             $sugeridos6=$this->removeArrayAfromBV2($sugeridos6,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos6);

             //Especifico SIN FP
             if($toro->getFacilidadparto()==0 || $toro->getFacilidadparto()==null){

                 $params=array(
                     'facilidadparto'=>24
                 );
                 $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                 $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                 $this->setSugeridos($toro,$sugeridosFPMenor);

                 $params=array(
                     'facilidadparto'=>18
                 );
                 $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                 $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                 $this->setSugeridos($toro,$sugeridosFPMenor);

             }

             //Sugeridos_7 15 MESES
             $sugeridos7= $sugeridos6=$this->removeArrayAfromBV2($sugeridos7Base,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos7);

             if($toro->getFacilidadparto()==18){

                 $params=array(
                     'facilidadparto'=>15
                 );
                 $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                 $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                 $this->setSugeridos($toro,$sugeridosFPMenor);
             }else if($toro->getFacilidadparto()==24){

                 $params=array(
                     'facilidadparto'=>18
                 );
                 $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                 $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                 $this->setSugeridos($toro,$sugeridosFPMenor);

             }

             //Sugeridos_8 MOCHO Y CRIADOR
             $params=array(
                 'mocho'=>$toro->getMocho(),
                 'criador'=>"'".$toro->getCriador()."'"

             );
             $sugeridos8=  $repo->DinamycGet($toro,$params);
             $sugeridos8=$this->removeArrayAfromBV2($sugeridos8,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos8);

             //Sugeridos_9
             $params=array(
                 'mocho'=>$toro->getMocho(),
             );
             $sugeridos9=  $repo->DinamycGet($toro,$params);
             $sugeridos9=$this->removeArrayAfromBV2($sugeridos9,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos9);

             //Sugeridos_10 CABAÑA
             $params=array(
             );
             $sugeridos10=  $repo->DinamycGet($toro,$params);
             $sugeridos10=$this->removeNotKeyWords($myclaves,$sugeridos10);
             $sugeridos10=$this->removeArrayAfromBV2($sugeridos10,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos10);

             //Sugeridos_11 CRIADOR
             $params=array(
                 'criador'=>"'".$toro->getCriador()."'"
             );
             $sugeridos11=  $repo->DinamycGet($toro,$params);
             $sugeridos11=$this->removeArrayAfromBV2($sugeridos11,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos11);
         }

         //Caso 8 FP 15 MESES	CABAÑA	MOCHO	CRIADOR
         elseif($this->TienePalabrasClave($toro,$claves) && $toro->getMocho()==true && $toro->getCriador()!=null)
         {
             //Sugeridos_1 FP 15 MESES Y CABAÑA Y MOCHO Y CRIADOR
             $params=array(
                 'facilidadparto'=>$toro->getFacilidadparto(),
                 'mocho'=>$toro->getMocho(),
                 'criador'=>"'".$toro->getCriador()."'"

             );
             $sugeridos=  $repo->DinamycGet($toro,$params);
             $sugeridos=$this->removeNotKeyWords($myclaves,$sugeridos);
             $this->setSugeridos($toro,$sugeridos);

             //Sugeridos_2 FP 15 MESES Y CABAÑA Y MOCHO
             $params=array(
                 'facilidadparto'=>$toro->getFacilidadparto(),
                 'mocho'=>$toro->getMocho(),
                 );
             $sugeridos2=$sugeridos4Base=  $repo->DinamycGet($toro,$params);
             $sugeridos2=$this->removeNotKeyWords($myclaves,$sugeridos2);
             $sugeridos2=$this->removeArrayAfromBV2($sugeridos2,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos2);

             //Sugeridos_3 FP 15 MESES Y CABAÑA
             $params=array(
                 'facilidadparto'=>$toro->getFacilidadparto(),
             );
             $sugeridos3= $sugeridos6Base= $repo->DinamycGet($toro,$params);
             $sugeridos3=$this->removeNotKeyWords($myclaves,$sugeridos3);
             $sugeridos3=$this->removeArrayAfromBV2($sugeridos3,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos3);

             //Sugeridos_4 FP 15 MESES Y MOCHO
             $sugeridos4=$this->removeArrayAfromBV2($sugeridos4Base,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos4);

             //Sugeridos_5 FP 15 MESES y CRIADOR
             $params=array(
             'facilidadparto'=>$toro->getFacilidadparto(),
             'criador'=>"'".$toro->getCriador()."'"
             );
             $sugeridos5=  $repo->DinamycGet($toro,$params);
             $sugeridos5=$this->removeArrayAfromBV2($sugeridos5,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos5);

             //Especifico SIN FP
             if($toro->getFacilidadparto()==0 || $toro->getFacilidadparto()==null){

                 $params=array(
                     'facilidadparto'=>24
                 );
                 $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                 $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                 $this->setSugeridos($toro,$sugeridosFPMenor);

                 $params=array(
                     'facilidadparto'=>18
                 );
                 $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                 $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                 $this->setSugeridos($toro,$sugeridosFPMenor);

             }

             //Sugeridos_6 15 MESES
             $sugeridos6=$this->removeArrayAfromBV2($sugeridos6Base,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos6);

             if($toro->getFacilidadparto()==18){

                 $params=array(
                     'facilidadparto'=>15
                 );
                 $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                 $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                 $this->setSugeridos($toro,$sugeridosFPMenor);
             }else if($toro->getFacilidadparto()==24){

                 $params=array(
                     'facilidadparto'=>18
                 );
                 $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                 $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                 $this->setSugeridos($toro,$sugeridosFPMenor);

             }

             //Sugeridos_7 MOCHO Y CRIADOR
             $params=array(
                 'mocho'=>$toro->getMocho(),
                 'criador'=>"'".$toro->getCriador()."'"
             );
             $sugeridos7=  $repo->DinamycGet($toro,$params);
             $sugeridos7=$this->removeArrayAfromBV2($sugeridos7,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos7);

             //Sugeridos_8 MOCHO
             $params=array(
                 'mocho'=>$toro->getMocho(),
             );
             $sugeridos8=  $repo->DinamycGet($toro,$params);
             $sugeridos8=$this->removeArrayAfromBV2($sugeridos8,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos8);

             //Sugeridos_9 CABAÑA
             $params=array(
             );
             $sugeridos9=  $repo->DinamycGet($toro,$params);
             $sugeridos9=$this->removeNotKeyWords($myclaves,$sugeridos9);
             $sugeridos9=$this->removeArrayAfromBV2($sugeridos9,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos9);

             //Sugeridos 10 CRIADOR
             $params=array(
                 'criador'=>"'".$toro->getCriador()."'"
             );
             $sugeridos10=  $repo->DinamycGet($toro,$params);
             $sugeridos10=$this->removeArrayAfromBV2($sugeridos10,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos10);
         }

         //Caso 9 FP 15 MESES	MOCHO	CRIADOR
         elseif($toro->getMocho()==true && $toro->getCriador()!=null)
         {
              //Sugeridos_1 FP 15 MESES Y MOCHO Y CRIADOR
             $params=array(
                 'facilidadparto'=>$toro->getFacilidadparto(),
                 'mocho'=>$toro->getMocho(),
                 'criador'=>"'".$toro->getCriador()."'"
             );
             $sugeridos=  $repo->DinamycGet($toro,$params);
             $this->setSugeridos($toro,$sugeridos);

             //Sugeridos_2 FP 15 MESES y MOCHO
             $params=array(
                 'facilidadparto'=>$toro->getFacilidadparto(),
                 'mocho'=>$toro->getMocho(),
             );
             $sugeridos2= $sugeridos6Base= $repo->DinamycGet($toro,$params);
             $sugeridos2=$this->removeArrayAfromBV2($sugeridos2,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos2);

             //Sugeridos_3 FP 15 MESES Y CRIADOR
             $params=array(
                 'facilidadparto'=>$toro->getFacilidadparto(),
                 'criador'=>"'".$toro->getCriador()."'"
             );
             $sugeridos3=  $repo->DinamycGet($toro,$params);
             $sugeridos3=$this->removeArrayAfromBV2($sugeridos3,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos3);


             //Especifico SIN FP
             if($toro->getFacilidadparto()==0 || $toro->getFacilidadparto()==null){

                 $params=array(
                     'facilidadparto'=>24
                 );
                 $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                 $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                 $this->setSugeridos($toro,$sugeridosFPMenor);

                 $params=array(
                     'facilidadparto'=>18
                 );
                 $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                 $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                 $this->setSugeridos($toro,$sugeridosFPMenor);

             }

             //Sugeridos_4 15 MESES
             $params=array(
                 'facilidadparto'=>$toro->getFacilidadparto(),
             );
             $sugeridos4=  $repo->DinamycGet($toro,$params);
             $sugeridos4=$this->removeArrayAfromBV2($sugeridos4,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos4);

             if($toro->getFacilidadparto()==18){

                 $params=array(
                     'facilidadparto'=>15
                 );
                 $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                 $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                 $this->setSugeridos($toro,$sugeridosFPMenor);
             }else if($toro->getFacilidadparto()==24){

                 $params=array(
                     'facilidadparto'=>18
                 );
                 $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                 $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                 $this->setSugeridos($toro,$sugeridosFPMenor);

             }

             //Sugeridos_5 MOCHO Y CRIADOR
             $params=array(
                 'mocho'=>$toro->getMocho(),
                 'criador'=>"'".$toro->getCriador()."'"
             );
             $sugeridos5=  $repo->DinamycGet($toro,$params);
             $sugeridos5=$this->removeArrayAfromBV2($sugeridos5,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos5);

             //Sugeridos_6 MOCHO
             $params=array(
                 'mocho'=>$toro->getMocho(),
             );
             $sugeridos6=  $repo->DinamycGet($toro,$params);
             $sugeridos6=$this->removeArrayAfromBV2($sugeridos6,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos6);

             //Sugeridos_7 CRIADOR
             $params=array(
                 'criador'=>"'".$toro->getCriador()."'"
             );
             $sugeridos7=  $repo->DinamycGet($toro,$params);
             $sugeridos7=$this->removeArrayAfromBV2($sugeridos7,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos7);
         }

         //Caso 10 FP 15 MESES	CRIADOR

         elseif($toro->getCriador()!=null)
         {
            //Sugeridos_1 FP 15 MESES Y CRIADOR
             $params=array(
                 'facilidadparto'=>$toro->getFacilidadparto(),
                 'criador'=>"'".$toro->getCriador()."'"
             );
             $sugeridos=  $repo->DinamycGet($toro,$params);
             $this->setSugeridos($toro,$sugeridos);

             //Especifico SIN FP
             if($toro->getFacilidadparto()==0 || $toro->getFacilidadparto()==null){

                 $params=array(
                     'facilidadparto'=>24
                 );
                 $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                 $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                 $this->setSugeridos($toro,$sugeridosFPMenor);

                 $params=array(
                     'facilidadparto'=>18
                 );
                 $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                 $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                 $this->setSugeridos($toro,$sugeridosFPMenor);

             }

             //Sugeridos_2 FP 15 MESES
             $params=array(
                 'facilidadparto'=>$toro->getFacilidadparto(),
             );
             $sugeridos2= $sugeridos6Base= $repo->DinamycGet($toro,$params);
             $sugeridos2=$this->removeArrayAfromBV2($sugeridos2,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos2);

             if($toro->getFacilidadparto()==18){

                 $params=array(
                     'facilidadparto'=>15
                 );
                 $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                 $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                 $this->setSugeridos($toro,$sugeridosFPMenor);
             }else if($toro->getFacilidadparto()==24){

                 $params=array(
                     'facilidadparto'=>18
                 );
                 $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                 $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                 $this->setSugeridos($toro,$sugeridosFPMenor);

             }

             //Sugeridos_3 CRIADOR
             $params=array(
                 'criador'=>"'".$toro->getCriador()."'"
             );
             $sugeridos3=  $repo->DinamycGet($toro,$params);
             $sugeridos3=$this->removeArrayAfromBV2($sugeridos3,$toro->getTorosSugeridos());
             $this->setSugeridos($toro,$sugeridos3);

         }

         //      die();
   //    print (count($sugeridos13));die();

         $em->persist($toro);
         $em->flush();
       //  die();
     }


    function UpdateToroV2($toro,$claves,$repo,$em){

        $sugeridosactuales=$toro->getTorosSugeridos();
        foreach($sugeridosactuales as $s)
            $toro->removeTorosSugerido($s);

        $myclaves=$this->PalabrasClave($toro,$claves);
        if(count($myclaves)>0)
            $myclaves=implode(',',$myclaves);
        else
            $myclaves=null;


        //Caso 1
        if($toro->getCP()==true && $this->getDatoFromTablaGenMenor30($toro,'RANKING','PD')==true
            && ($this->getDatoFromTablaGenMenor30($toro,'RANKING','PF')|| $this->getDatoFromTablaGenMenor30($toro,'RANKING','P.Año')) &&
            $this->TienePalabrasClave($toro,$claves)  && $toro->getCriador()!=null)

        {
            //Sugeridos 1 FP 15 MESES y CONCEPT PLUS Y MOCHO y <30% PD y <30% PF o P. Año  y  CABAÑA Y CRIADOR
            $params=array(
                'CP'=>1,
                'facilidadparto'=>$toro->getFacilidadparto(),

                'criador'=>"'".$toro->getCriador()."'"

            );
            $sugeridos=  $repo->DinamycGet($toro,$params);
            $sugeridos=  $this->removeNoTablaGenMenor30($sugeridos,'PD');
            $sugeridos=  $this->removeNoTablaGenMenor30($sugeridos,'PF');
            $sugeridos=  $this->removeNoTablaGenMenor30($sugeridos,'P.Año');
            $sugeridos=$this->removeNotKeyWords($myclaves,$sugeridos);
            $this->setSugeridos($toro,$sugeridos);

            //Sugeridos_2 (FP 15 MESES y CONCEPT PLUS Y MOCHO y <30% PD y <30% PF o P. Año  y  CABAÑA) Eliminado Criador
            $params2=array(
                'CP'=>1,
                'facilidadparto'=>$toro->getFacilidadparto(),

            );
            $sugeridos2= $sugeridos5Base=$repo->DinamycGet($toro,$params2);
            $sugeridos2= $sugeridos4base= $this->removeNoTablaGenMenor30($sugeridos2,'PD'); //Toado punto partida sugeridos 4
            $sugeridos2=  $this->removeNoTablaGenMenor30($sugeridos2,'PF');
            $sugeridos2=$sugeridos3Base= $this->removeNoTablaGenMenor30($sugeridos2,'P.Año');//Tomando sugeridos2base como punto de partida para sugeridos3
            $sugeridos2=$this->removeNotKeyWords($myclaves,$sugeridos2);
            $sugeridos2=$this->removeArrayAfromBV2($sugeridos2,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos2);

            //Sugeridos_3 FP 15 MESES y CONCEPT PLUS Y MOCHO y <30% PD y <30% PF o P. Año ---Elimina CABAÑA
            $sugeridos3=$this->removeArrayAfromBV2($sugeridos3Base,$sugeridos2);
            $this->setSugeridos($toro,$sugeridos3);

            //Sugeridos_4 FP 15 MESES y CONCEPT PLUS Y MOCHO y <30% PD. --Eliminado <30% PF o P. Año y CABAña
            $sugeridos4=$this->removeArrayAfromBV2($sugeridos4base,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos4);

            //Sugeridos_5 FP 15 MESES y CONCEPT PLUS Y MOCHO --Elimina <30% PD y <30% PF o P. Año y CABAña
            $this->setSugeridos($toro,$this->removeArrayAfromBV2($sugeridos5Base,$toro->getTorosSugeridos()));

            //Sugeridos_6 FP 15 MESES y CONCEPT PLUS --Elimina mocho,<30% PD y <30% PF o P. Año y CABAña
            $params3=array(
                'CP'=>1,
                'facilidadparto'=>$toro->getFacilidadparto()
            );
            $sugeridos6= $repo->DinamycGet($toro,$params3);
            $this->setSugeridos($toro,$this->removeArrayAfromBV2($sugeridos6,$toro->getTorosSugeridos()));

            //Sugeridos_7 FP 15 MESES y <30% PD y <30% PF o P. Año
            $params4=array(
                'facilidadparto'=>$toro->getFacilidadparto()
            );
            $sugeridos7=$sugeridos8Base=$sugeridos9Base=$repo->DinamycGet($toro,$params4);
            $sugeridos7=  $this->removeNoTablaGenMenor30($sugeridos7,'PD');
            $sugeridos7=  $this->removeNoTablaGenMenor30($sugeridos7,'PF');
            $sugeridos7=  $this->removeNoTablaGenMenor30($sugeridos7,'P.Año');
            $sugeridos7=$this->removeArrayAfromBV2($sugeridos7,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos7);

            //Sugeridos_8 FP 15 MESES y <30% PD
            $sugeridos8=$this->removeNoTablaGenMenor30($sugeridos8Base,'PD');
            $sugeridos8=$this->removeArrayAfromBV2($sugeridos8,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos8);

            //Sugeridos_9 FP 15 MESES y <30% PF o P. Año
            $sugeridos9=$this->removeNoTablaGenMenor30($sugeridos9Base,'PF');
            $sugeridos9=$this->removeNoTablaGenMenor30($sugeridos9,'P.Año');
            $sugeridos9=$this->removeArrayAfromBV2($sugeridos9,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos9);

            //Sugeridos_10 FP 15 MESES y MOCHO
            $params5=array(

                'facilidadparto'=>$toro->getFacilidadparto()
            );
            $sugeridos10= $repo->DinamycGet($toro,$params5);
            $sugeridos10=$this->removeArrayAfromBV2($sugeridos10,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos10);

            //Sugeridos_11 FP 15 MESES y CRIADOR
            $params6=array(
                'criador'=>"'".$toro->getCriador()."'",
                'facilidadparto'=>$toro->getFacilidadparto()
            );
            $sugeridos11=$repo->DinamycGet($toro,$params6);
            $sugeridos11=$this->removeArrayAfromBV2($sugeridos11,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos11);

            //Especifico SIN FP
            if($toro->getFacilidadparto()==0 || $toro->getFacilidadparto()==null){

                $params=array(
                    'facilidadparto'=>24
                );
                $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                $this->setSugeridos($toro,$sugeridosFPMenor);

                $params=array(
                    'facilidadparto'=>18
                );
                $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                $this->setSugeridos($toro,$sugeridosFPMenor);

            }

            //Sugeridos_12 15 MESES
            $params7=array(
                'facilidadparto'=>$toro->getFacilidadparto()

            );
            $sugeridos12=$repo->DinamycGet($toro,$params7);
            $sugeridos12=$this->removeArrayAfromBV2($sugeridos12,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos12);

            //Especifico
            if($toro->getFacilidadparto()==18){

                $params=array(
                    'facilidadparto'=>15
                );
                $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                $this->setSugeridos($toro,$sugeridosFPMenor);
            }else if($toro->getFacilidadparto()==24){

                $params=array(
                    'facilidadparto'=>18
                );
                $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                $this->setSugeridos($toro,$sugeridosFPMenor);

            }

            //Sugeridos_13 MOCHO Y CRIADOR
            $params8=array(
                'criador'=>"'".$toro->getCriador()."'",

            );
            $sugeridos13=$repo->DinamycGet($toro,$params8);
            $sugeridos13=$this->removeArrayAfromBV2($sugeridos13,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos13);

            //Sugeridos_14
            $params9=array(
                'mocho'=>$toro->getMocho()
            );
            $sugeridos14=$repo->DinamycGet($toro,$params9);
            $sugeridos14=$this->removeArrayAfromBV2($sugeridos14,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos14);

            //Sugeridos_15 CABAÑA
            $params10=array();
            $sugeridos15=$repo->DinamycGet($toro,$params10);
            $sugeridos15=$this->removeArrayAfromBV2($sugeridos15,$toro->getTorosSugeridos());
            $sugeridos15=$this->removeNotKeyWords($myclaves,$sugeridos15);
            $sugeridos15=$this->removeArrayAfromBV2($sugeridos15,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos15);

            //Sugeridos_16 CRIADOR
            $params11=array(
                'criador'=>"'".$toro->getCriador()."'",
            );
            $sugeridos16=$repo->DinamycGet($toro,$params11);
            $sugeridos16=$this->removeArrayAfromBV2($sugeridos16,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos16);
        }

        // Caso2 FP 15 MESES	CONCEPT PLUS	<30% PF o P. Año	CABAÑA	MOCHO	CRIADOR
        else if($toro->getCP()==true
            && ($this->getDatoFromTablaGenMenor30($toro,'RANKING','PF')|| $this->getDatoFromTablaGenMenor30($toro,'RANKING','P.Año')) &&
            $this->TienePalabrasClave($toro,$claves)  && $toro->getCriador()!=null)

        {
            //Sugeridos_1 FP 15 MESES y CONCEPT PLUS Y MOCHO y <30% PF o P. Año  y  CABAÑA Y CRIADOR
            $params=array(
                'CP'=>1,
                'facilidadparto'=>$toro->getFacilidadparto(),

                'criador'=>"'".$toro->getCriador()."'"
            );
            $sugeridos=  $repo->DinamycGet($toro,$params);
            $sugeridos=  $this->removeNoTablaGenMenor30($sugeridos,'PF');
            $sugeridos=  $this->removeNoTablaGenMenor30($sugeridos,'P.Año');
            $sugeridos=$this->removeNotKeyWords($myclaves,$sugeridos);
            $this->setSugeridos($toro,$sugeridos);

            // Sugeridos_2 FP 15 MESES y CONCEPT PLUS Y MOCHO y <30% PF o P. Año  y  CABAÑA
            $params=array(
                'CP'=>1,
                'facilidadparto'=>$toro->getFacilidadparto(),

            );

            $sugeridos2=$sugeridos4base=$repo->DinamycGet($toro,$params);
            $sugeridos2=  $this->removeNoTablaGenMenor30($sugeridos2,'PF');
            $sugeridos2=$sugeridos3Base=$this->removeNoTablaGenMenor30($sugeridos2,'P.Año');//Tomando sugeridos2base como punto de partida para sugeridos3
            $sugeridos2=$this->removeNotKeyWords($myclaves,$sugeridos2);
            $sugeridos2=$this->removeArrayAfromBV2($sugeridos2,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos2);
            //Sugeridos_3 FP 15 MESES y CONCEPT PLUS Y MOCHO y <30% PF o P. Año
            $sugeridos3=$this->removeArrayAfromBV2($sugeridos3Base,$sugeridos2);
            $this->setSugeridos($toro,$sugeridos3);

            //Sugeridos_4 FP 15 MESES y CONCEPT PLUS Y MOCHO
            $sugeridos4=$this->removeArrayAfromBV2($sugeridos4base,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos4);

            //Sugeridos_5 FP 15 MESES y CONCEPT PLUS
            $params=array(
                'CP'=>1,
                'facilidadparto'=>$toro->getFacilidadparto()

            );
            $sugeridos5=$repo->DinamycGet($toro,$params);
            $sugeridos5=$this->removeArrayAfromBV2($sugeridos5,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos5);

            //Sugeridos_6 FP 15 MESES y <30% PF o P. Año
            $params=array(
                'facilidadparto'=>$toro->getFacilidadparto()
            );
            $sugeridos6=$sugeridos9Base=$repo->DinamycGet($toro,$params);
            $sugeridos6=  $this->removeNoTablaGenMenor30($sugeridos6,'PF');
            $sugeridos6=  $this->removeNoTablaGenMenor30($sugeridos6,'P.Año');
            $sugeridos6=$this->removeArrayAfromBV2($sugeridos6,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos6);

            //Sugeridos_7 FP 15 MESES y MOCHO
            $params=array(
                'facilidadparto'=>$toro->getFacilidadparto(),

            );
            $sugeridos7=$repo->DinamycGet($toro,$params);
            $sugeridos7=$this->removeArrayAfromBV2($sugeridos7,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos7);

            //Sugeridos8 FP 15 MESES y CRIADOR
            $params=array(
                'facilidadparto'=>$toro->getFacilidadparto(),
                'criador'=>"'".$toro->getCriador()."'"
            );
            $sugeridos8=$repo->DinamycGet($toro,$params);
            $sugeridos8=$this->removeArrayAfromBV2($sugeridos8,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos8);

            //Especifico SIN FP
            if($toro->getFacilidadparto()==0 || $toro->getFacilidadparto()==null){

                $params=array(
                    'facilidadparto'=>24
                );
                $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                $this->setSugeridos($toro,$sugeridosFPMenor);

                $params=array(
                    'facilidadparto'=>18
                );
                $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                $this->setSugeridos($toro,$sugeridosFPMenor);

            }

            //Sugeridos_9 15 MESES
            $sugeridos9=$this->removeArrayAfromBV2($sugeridos9Base,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos9);

            if($toro->getFacilidadparto()==18){

                $params=array(
                    'facilidadparto'=>15
                );
                $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                $this->setSugeridos($toro,$sugeridosFPMenor);
            }else if($toro->getFacilidadparto()==24){

                $params=array(
                    'facilidadparto'=>18
                );
                $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                $this->setSugeridos($toro,$sugeridosFPMenor);

            }

            //Sugeridos_10 MOCHO Y CRIADOR
            $params=array(
                'criador'=>"'".$toro->getCriador()."'",

            );
            $sugeridos10=$repo->DinamycGet($toro,$params);
            $sugeridos10=$this->removeArrayAfromBV2($sugeridos10,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos10);

            //Sugeridos_11 MOCHO
            $params=array(
                'criador'=>"'".$toro->getCriador()."'",

            );
            $sugeridos11=$repo->DinamycGet($toro,$params);
            $sugeridos11=$this->removeArrayAfromBV2($sugeridos11,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos11);

            //Sugeridos_12 CABAña
            $params=array(
            );

            $sugeridos12=$repo->DinamycGet($toro,$params);
            $sugeridos12=$this->removeNotKeyWords($myclaves,$sugeridos12);
            $sugeridos12=$this->removeArrayAfromBV2($sugeridos12,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos12);

            //Sugeridos_13 CRIADOR
            $params=array(
                'criador'=>"'".$toro->getCriador()."'"
            );
            $sugeridos13=$repo->DinamycGet($toro,$params);
            $sugeridos13=$this->removeArrayAfromBV2($sugeridos13,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos13);

        }

        //Caso 3 FP 15 MESES y CONCEPT PLUS Y MOCHO y <30% PD y  CABAÑA Y CRIADOR
        elseif($toro->getCP()==true
            && $this->getDatoFromTablaGenMenor30($toro,'RANKING','PD') &&
            $this->TienePalabrasClave($toro,$claves)  && $toro->getCriador()!=null)

        {
            //Sugeridos_1 FP 15 MESES y CONCEPT PLUS Y MOCHO y <30% PD y  CABAÑA Y CRIADOR
            $params=array(
                'CP'=>1,
                'facilidadparto'=>$toro->getFacilidadparto(),

                'criador'=>"'".$toro->getCriador()."'"

            );
            $sugeridos=  $repo->DinamycGet($toro,$params);
            $sugeridos=  $this->removeNoTablaGenMenor30($sugeridos,'PD');
            $sugeridos=$this->removeNotKeyWords($myclaves,$sugeridos);
            $this->setSugeridos($toro,$sugeridos);

            //Sugeridos_2 FP 15 MESES y CONCEPT PLUS Y MOCHO y <30% PD y  CABAÑA
            $params=array(
                'CP'=>1,
                'facilidadparto'=>$toro->getFacilidadparto(),


            );
            $sugeridos2=$repo->DinamycGet($toro,$params);
            $sugeridos2=$sugeridos3Base=  $this->removeNoTablaGenMenor30($sugeridos2,'PD');
            $sugeridos2=$this->removeNotKeyWords($myclaves,$sugeridos2);
            $sugeridos2=$this->removeArrayAfromBV2($sugeridos2,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos2);

            //Sugeridos_3 FP 15 MESES y CONCEPT PLUS Y MOCHO y <30% PD
            $sugeridos3=$this->removeArrayAfromBV2($sugeridos3Base,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos3);

            //sugeridos_4 FP 15 MESES y CONCEPT PLUS Y MOCHO
            $params=array(
                'CP'=>1,
                'facilidadparto'=>$toro->getFacilidadparto(),


            );
            $sugeridos4=$repo->DinamycGet($toro,$params);
            $sugeridos4=$this->removeArrayAfromBV2($sugeridos4,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos4);

            //Sugeridos_5 FP 15 MESES y CONCEPT PLUS
            $params=array(
                'CP'=>1,
                'facilidadparto'=>$toro->getFacilidadparto(),

            );
            $sugeridos5=$repo->DinamycGet($toro,$params);
            $sugeridos5=$this->removeArrayAfromBV2($sugeridos5,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos5);

            //Sugeridos_6 FP 15 MESES y <30% PD
            $params=array(
                'facilidadparto'=>$toro->getFacilidadparto(),
            );
            $sugeridos6=$repo->DinamycGet($toro,$params);
            $sugeridos6=$this->removeArrayAfromBV2($sugeridos6,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos6);

            //Sugeridos_7 FP 15 MESES y MOCHO
            $params=array(
                'facilidadparto'=>$toro->getFacilidadparto(),


            );
            $sugeridos7=$repo->DinamycGet($toro,$params);
            $sugeridos7=$this->removeArrayAfromBV2($sugeridos7,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos7);

            //Especifico SIN FP
            if($toro->getFacilidadparto()==0 || $toro->getFacilidadparto()==null){

                $params=array(
                    'facilidadparto'=>24
                );
                $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                $this->setSugeridos($toro,$sugeridosFPMenor);

                $params=array(
                    'facilidadparto'=>18
                );
                $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                $this->setSugeridos($toro,$sugeridosFPMenor);

            }

            //Sugeridos_8 FP 15 MESES y CRIADOR
            $params=array(
                'facilidadparto'=>$toro->getFacilidadparto(),
                'criador'=>"'".$toro->getCriador()."'"

            );
            $sugeridos8=$repo->DinamycGet($toro,$params);
            $sugeridos8=$this->removeArrayAfromBV2($sugeridos8,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos8);


            if($toro->getFacilidadparto()==18){

                $params=array(
                    'facilidadparto'=>15
                );
                $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                $this->setSugeridos($toro,$sugeridosFPMenor);
            }else if($toro->getFacilidadparto()==24){

                $params=array(
                    'facilidadparto'=>18
                );
                $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                $this->setSugeridos($toro,$sugeridosFPMenor);

            }

            //Sugeridos_9 MOCHO Y CRIADOR
            $params=array(

                'criador'=>"'".$toro->getCriador()."'"

            );
            $sugeridos9=$repo->DinamycGet($toro,$params);
            $sugeridos9=$this->removeArrayAfromBV2($sugeridos9,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos9);

            //Sugeridos_10 MOCHO
            $params=array(

            );
            $sugeridos10=$repo->DinamycGet($toro,$params);
            $sugeridos10=$this->removeArrayAfromBV2($sugeridos10,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos10);

            //Sugeridos_11 CABAÑA
            $params=array(
            );
            $sugeridos11=$repo->DinamycGet($toro,$params);
            $sugeridos11=$this->removeNotKeyWords($myclaves,$sugeridos11);
            $sugeridos11=$this->removeArrayAfromBV2($sugeridos11,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos11);

            //Sugeridos_12 CRIADOR
            $params=array(
                'criador'=>"'".$toro->getCriador()."'"
            );
            $sugeridos12=$repo->DinamycGet($toro,$params);
            $sugeridos12=$this->removeArrayAfromBV2($sugeridos12,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos12);
        }

        //Caso 4 FP 15 MESES	CONCEPT PLUS	CABAÑA	MOCHO	CRIADOR
        elseif($toro->getCP()==true && $this->TienePalabrasClave($toro,$claves)  && $toro->getCriador()!=null)
        {

            //Sugeridos_1 FP 15 MESES y CONCEPT PLUS Y MOCHO Y CABAÑA Y CRIADOR
            $params=array(
                'CP'=>1,
                'facilidadparto'=>$toro->getFacilidadparto(),

                'criador'=>"'".$toro->getCriador()."'"

            );
            $sugeridos=  $repo->DinamycGet($toro,$params);
            $sugeridos=$this->removeNotKeyWords($myclaves,$sugeridos);
            $this->setSugeridos($toro,$sugeridos);

            //Sugeridos_2 FP 15 MESES y CONCEPT PLUS Y MOCHO Y CABAÑA
            $params=array(
                'CP'=>1,
                'facilidadparto'=>$toro->getFacilidadparto(),

            );
            $sugeridos2= $sugeridos3Base= $repo->DinamycGet($toro,$params);
            $sugeridos2=$this->removeNotKeyWords($myclaves,$sugeridos2);
            $sugeridos2=$this->removeArrayAfromBV2($sugeridos2,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos2);

            //Sugeridos_3 FP 15 MESES y CONCEPT PLUS Y MOCHO
            $sugeridos3=$this->removeArrayAfromBV2($sugeridos3Base,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos3);

            //Sugeridos_4 FP 15 MESES y CONCEPT PLUS
            $params=array(
                'CP'=>1,
                'facilidadparto'=>$toro->getFacilidadparto(),
            );
            $sugeridos4=  $repo->DinamycGet($toro,$params);
            $sugeridos4=$this->removeArrayAfromBV2($sugeridos4,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos4);

            //Sugeridos_5 FP 15 MESES y MOCHO
            $params=array(

                'facilidadparto'=>$toro->getFacilidadparto(),
            );
            $sugeridos5=  $repo->DinamycGet($toro,$params);
            $sugeridos5=$this->removeArrayAfromBV2($sugeridos5,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos5);

            //Sugeridos_6 FP 15 MESES y CRIADOR
            $params=array(
                'facilidadparto'=>$toro->getFacilidadparto(),
                'criador'=>"'".$toro->getCriador()."'"
            );
            $sugeridos6=  $repo->DinamycGet($toro,$params);
            $sugeridos6=$this->removeArrayAfromBV2($sugeridos6,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos6);

            //Especifico SIN FP
            if($toro->getFacilidadparto()==0 || $toro->getFacilidadparto()==null){

                $params=array(
                    'facilidadparto'=>24
                );
                $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                $this->setSugeridos($toro,$sugeridosFPMenor);

                $params=array(
                    'facilidadparto'=>18
                );
                $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                $this->setSugeridos($toro,$sugeridosFPMenor);

            }

            //Sugeridos_7 15 MESES
            $params=array(
                'facilidadparto'=>$toro->getFacilidadparto(),
            );
            $sugeridos7=  $repo->DinamycGet($toro,$params);
            $sugeridos7=$this->removeArrayAfromBV2($sugeridos7,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos7);

            if($toro->getFacilidadparto()==18){

                $params=array(
                    'facilidadparto'=>15
                );
                $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                $this->setSugeridos($toro,$sugeridosFPMenor);
            }else if($toro->getFacilidadparto()==24){

                $params=array(
                    'facilidadparto'=>18
                );
                $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                $this->setSugeridos($toro,$sugeridosFPMenor);

            }

            //Sugeridos_8 MOCHO Y CRIADOR
            $params=array(

                'criador'=>"'".$toro->getCriador()."'"
            );
            $sugeridos8=  $repo->DinamycGet($toro,$params);
            $sugeridos8=$this->removeArrayAfromBV2($sugeridos8,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos8);

            //Sugeridos_9 MOCHO
            $params=array(

            );
            $sugeridos9=  $repo->DinamycGet($toro,$params);
            $sugeridos9=$this->removeArrayAfromBV2($sugeridos9,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos9);

            //Sugeridos_10 CABAÑA
            $params=array(

            );
            $sugeridos10=  $repo->DinamycGet($toro,$params);
            $sugeridos10=$this->removeNotKeyWords($myclaves,$sugeridos10);
            $sugeridos10=$this->removeArrayAfromBV2($sugeridos10,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos10);

            //Sugeridos_11 CRIADOR
            $params=array(
                'criador'=>"'".$toro->getCriador()."'"
            );
            $sugeridos11=  $repo->DinamycGet($toro,$params);
            $sugeridos11=$this->removeArrayAfromBV2($sugeridos11,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos11);
        }

        //Caso 5 FP 15 MESES	<30% PD	<30% PF o P. Año	CABAÑA	MOCHO	CRIADOR

        elseif($this->getDatoFromTablaGenMenor30($toro,'RANKING','PD') &&
            ($this->getDatoFromTablaGenMenor30($toro,'RANKING','PF')|| $this->getDatoFromTablaGenMenor30($toro,'RANKING','P.Año'))
            && $this->TienePalabrasClave($toro,$claves)  && $toro->getCriador()!=null)
        {
            //Sugerido_1 FP 15 MESES Y MOCHO y <30% PD y <30% PF o P. Año  y  CABAÑA Y CRIADOR
            $params=array(
                'facilidadparto'=>$toro->getFacilidadparto(),

                'criador'=>"'".$toro->getCriador()."'"

            );
            $sugeridos=  $repo->DinamycGet($toro,$params);
            $sugeridos=  $this->removeNoTablaGenMenor30($sugeridos,'PD');
            $sugeridos=  $this->removeNoTablaGenMenor30($sugeridos,'PF');
            $sugeridos=  $this->removeNoTablaGenMenor30($sugeridos,'P.Año');
            $sugeridos=$this->removeNotKeyWords($myclaves,$sugeridos);
            $this->setSugeridos($toro,$sugeridos);

            //Sugeridos_2 FP 15 MESES Y MOCHO y <30% PD y <30% PF o P. Año  y  CABAÑA
            $params=array(
                'facilidadparto'=>$toro->getFacilidadparto(),


            );
            $sugeridos2=  $repo->DinamycGet($toro,$params);
            $sugeridos2=  $this->removeNoTablaGenMenor30($sugeridos2,'PD');
            $sugeridos2=  $this->removeNoTablaGenMenor30($sugeridos2,'PF');
            $sugeridos2=  $this->removeNoTablaGenMenor30($sugeridos2,'P.Año');
            $sugeridos2=$this->removeNotKeyWords($myclaves,$sugeridos2);
            $sugeridos2=$this->removeArrayAfromBV2($sugeridos2,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos2);

            //Sugeridos_3 FP 15 MESES Y MOCHO y <30% PD y <30% PF o P. Año

            $params=array(
                'facilidadparto'=>$toro->getFacilidadparto(),


            );
            $sugeridos3=$sugeridos4Base=$sugeridos8Base= $repo->DinamycGet($toro,$params);
            $sugeridos3=  $this->removeNoTablaGenMenor30($sugeridos3,'PD');
            $sugeridos3=  $this->removeNoTablaGenMenor30($sugeridos3,'PF');
            $sugeridos3=  $this->removeNoTablaGenMenor30($sugeridos3,'P.Año');
            $sugeridos3=$this->removeArrayAfromBV2($sugeridos3,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos3);

            //Sugeridos_4 FP 15 MESES Y MOCHO y <30% PD
            $sugeridos4=$this->removeNoTablaGenMenor30($sugeridos4Base,'PD');
            $sugeridos4=$this->removeArrayAfromBV2($sugeridos4,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos4);

            //Sugeridos_5 FP 15 MESES y <30% PD y <30% PF o P. Año
            $params=array(
                'facilidadparto'=>$toro->getFacilidadparto(),

            );
            $sugeridos5=$sugeridos7Base=$sugeridos10Base= $repo->DinamycGet($toro,$params);
            $sugeridos5= $sugeridos6Base= $this->removeNoTablaGenMenor30($sugeridos5,'PD');
            $sugeridos5=  $this->removeNoTablaGenMenor30($sugeridos5,'PF');
            $sugeridos5=  $this->removeNoTablaGenMenor30($sugeridos5,'P.Año');
            $sugeridos5=$this->removeArrayAfromBV2($sugeridos5,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos5);

            //Sugeridos_6 FP 15 MESES y <30% PD
            $sugeridos6=$this->removeArrayAfromBV2($sugeridos6Base,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos6);

            //Sugeridos_7 FP 15 MESES y <30% PF o P. Año
            $sugeridos7=$this->removeNoTablaGenMenor30($sugeridos7Base,'PF');
            $sugeridos7=$this->removeNoTablaGenMenor30($sugeridos7,'P.Año');
            $sugeridos7=$this->removeArrayAfromBV2($sugeridos7,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos7);

            //Sugeridos_8 FP 15 MESES y MOCHO
            $sugeridos8=$this->removeArrayAfromBV2($sugeridos8Base,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos8);

            //Sugeridos_9 FP 15 MESES y CRIADOR
            $params=array(
                'facilidadparto'=>$toro->getFacilidadparto(),
                'criador'=>"'".$toro->getCriador()."'"

            );
            $sugeridos9= $repo->DinamycGet($toro,$params);
            $sugeridos9=$this->removeArrayAfromBV2($sugeridos9,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos9);

            //Especifico SIN FP
            if($toro->getFacilidadparto()==0 || $toro->getFacilidadparto()==null){

                $params=array(
                    'facilidadparto'=>24
                );
                $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                $this->setSugeridos($toro,$sugeridosFPMenor);

                $params=array(
                    'facilidadparto'=>18
                );
                $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                $this->setSugeridos($toro,$sugeridosFPMenor);

            }

            //Sugeridos_10 FP 15 MESES
            $sugeridos10=$this->removeArrayAfromBV2($sugeridos10Base,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos10);

            if($toro->getFacilidadparto()==18){

                $params=array(
                    'facilidadparto'=>15
                );
                $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                $this->setSugeridos($toro,$sugeridosFPMenor);
            }else if($toro->getFacilidadparto()==24){

                $params=array(
                    'facilidadparto'=>18
                );
                $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                $this->setSugeridos($toro,$sugeridosFPMenor);

            }

            //Sugeridos_11 MOCHO Y CRIADOR
            $params=array(


                'criador'=>"'".$toro->getCriador()."'"

            );
            $sugeridos11=  $repo->DinamycGet($toro,$params);
            $sugeridos11=$this->removeArrayAfromBV2($sugeridos11,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos11);

            //Sugeridos_12 MOCHO
            $params=array(

            );
            $sugeridos12=  $repo->DinamycGet($toro,$params);
            $sugeridos12=$this->removeArrayAfromBV2($sugeridos12,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos12);

            //Sugeridos_13 CABAÑA
            $params=array(
            );
            $sugeridos13=  $repo->DinamycGet($toro,$params);
            $sugeridos13=$this->removeArrayAfromBV2($sugeridos13,$toro->getTorosSugeridos());
            $sugeridos13=$this->removeNotKeyWords($myclaves,$sugeridos13);
            $this->setSugeridos($toro,$sugeridos13);

            //Sugeridos_14 CRIADOR
            $params=array(
                'criador'=>"'".$toro->getCriador()."'"
            );
            $sugeridos14=  $repo->DinamycGet($toro,$params);
            $sugeridos14=$this->removeArrayAfromBV2($sugeridos14,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos14);
        }

        //Caso 6 FP 15 MESES	<30% PD	CABAÑA	MOCHO	CRIADOR

        elseif($this->getDatoFromTablaGenMenor30($toro,'RANKING','PD')
            && $this->TienePalabrasClave($toro,$claves)  && $toro->getCriador()!=null)
        {
            //Sugeridos_1 FP 15 MESES Y MOCHO y <30% PD  y  CABAÑA Y CRIADOR
            $params=array(
                'facilidadparto'=>$toro->getFacilidadparto(),

                'criador'=>"'".$toro->getCriador()."'"

            );
            $sugeridos=  $repo->DinamycGet($toro,$params);
            $sugeridos=  $this->removeNoTablaGenMenor30($sugeridos,'PD');
            $sugeridos=$this->removeNotKeyWords($myclaves,$sugeridos);
            $this->setSugeridos($toro,$sugeridos);

            //Sugeridos_2 FP 15 MESES Y MOCHO y <30% PD  y  CABAÑA
            $params=array(
                'facilidadparto'=>$toro->getFacilidadparto(),


            );
            $sugeridos2=  $repo->DinamycGet($toro,$params);
            $sugeridos2=  $this->removeNoTablaGenMenor30($sugeridos2,'PD');
            $sugeridos2=$this->removeNotKeyWords($myclaves,$sugeridos2);
            $sugeridos2=$this->removeArrayAfromBV2($sugeridos2,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos2);

            //Sugeridos_3 FP 15 MESES Y MOCHO y <30% PD
            $params=array(
                'facilidadparto'=>$toro->getFacilidadparto(),


            );
            $sugeridos3= $sugeridos4Base=$repo->DinamycGet($toro,$params);
            $sugeridos3=  $this->removeNoTablaGenMenor30($sugeridos3,'PD');
            $sugeridos3=$this->removeArrayAfromBV2($sugeridos3,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos3);

            //Sugeridos_4 FP 15 MESES Y MOCHO
            $sugeridos4=$this->removeArrayAfromBV2($sugeridos4Base,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos4);

            //Sugeridos_5 FP 15 MESES y <30% PD
            $params=array(
                'facilidadparto'=>$toro->getFacilidadparto(),
            );
            $sugeridos5=$sugeridos7Base=$repo->DinamycGet($toro,$params);
            $sugeridos5=  $this->removeNoTablaGenMenor30($sugeridos5,'PD');
            $sugeridos5=$this->removeArrayAfromBV2($sugeridos5,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos5);

            //Sugeridos_6 FP 15 MESES y CRIADOR
            $params=array(
                'facilidadparto'=>$toro->getFacilidadparto(),
                'criador'=>"'".$toro->getCriador()."'"
            );
            $sugeridos6=$repo->DinamycGet($toro,$params);
            $sugeridos6=$this->removeArrayAfromBV2($sugeridos6,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos6);

            //Especifico SIN FP
            if($toro->getFacilidadparto()==0 || $toro->getFacilidadparto()==null){

                $params=array(
                    'facilidadparto'=>24
                );
                $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                $this->setSugeridos($toro,$sugeridosFPMenor);

                $params=array(
                    'facilidadparto'=>18
                );
                $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                $this->setSugeridos($toro,$sugeridosFPMenor);

            }

            //Sugeridos_7 15 MESES
            $sugeridos7=$this->removeArrayAfromBV2($sugeridos7Base,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos7);

            if($toro->getFacilidadparto()==18){

                $params=array(
                    'facilidadparto'=>15
                );
                $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                $this->setSugeridos($toro,$sugeridosFPMenor);
            }else if($toro->getFacilidadparto()==24){

                $params=array(
                    'facilidadparto'=>18
                );
                $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                $this->setSugeridos($toro,$sugeridosFPMenor);

            }

            //Sugeridos_8 MOCHO Y CRIADOR
            $params=array(

                'criador'=>"'".$toro->getCriador()."'"
            );
            $sugeridos8=$repo->DinamycGet($toro,$params);
            $sugeridos8=$this->removeArrayAfromBV2($sugeridos8,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos8);

            //Sugeridos_9 MOCHO
            $params=array(

            );
            $sugeridos9=$repo->DinamycGet($toro,$params);
            $sugeridos9=$this->removeArrayAfromBV2($sugeridos9,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos9);

            //Sugeridos_10 CABAÑA
            $params=array(
            );
            $sugeridos10=$repo->DinamycGet($toro,$params);
            $sugeridos10=$this->removeNotKeyWords($myclaves,$sugeridos10);
            $sugeridos10=$this->removeArrayAfromBV2($sugeridos10,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos10);

            //Sugeridos_11 CRIADOR
            $params=array(
                'criador'=>"'".$toro->getCriador()."'"
            );
            $sugeridos11=$repo->DinamycGet($toro,$params);
            $sugeridos11=$this->removeArrayAfromBV2($sugeridos11,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos11);
        }

        // Caso 7 FP 15 MESES	<30% PF o P. Año	CABAÑA	MOCHO	CRIADOR
        elseif(($this->getDatoFromTablaGenMenor30($toro,'RANKING','PF')|| $this->getDatoFromTablaGenMenor30($toro,'RANKING','P.Año'))
            && $this->TienePalabrasClave($toro,$claves)  && $toro->getCriador()!=null)
        {

            //Sugeridos_1 FP 15 MESES y <30% PF o P. Año y  CABAÑA Y MOCHO Y CRIADOR
            $params=array(
                'facilidadparto'=>$toro->getFacilidadparto(),

                'criador'=>"'".$toro->getCriador()."'"

            );
            $sugeridos=  $repo->DinamycGet($toro,$params);
            $sugeridos=  $this->removeNoTablaGenMenor30($sugeridos,'PF');
            $sugeridos=  $this->removeNoTablaGenMenor30($sugeridos,'P.Año');
            $sugeridos=$this->removeNotKeyWords($myclaves,$sugeridos);
            $this->setSugeridos($toro,$sugeridos);

            //Sugeridos_2 FP 15 MESES y <30% PF o P. Año y  CABAÑA Y MOCHO
            $params=array(
                'facilidadparto'=>$toro->getFacilidadparto(),


            );
            $sugeridos2= $sugeridos5Base= $repo->DinamycGet($toro,$params);
            $sugeridos2=  $this->removeNoTablaGenMenor30($sugeridos2,'PF');
            $sugeridos2=  $this->removeNoTablaGenMenor30($sugeridos2,'P.Año');
            $sugeridos2=$this->removeNotKeyWords($myclaves,$sugeridos2);
            $sugeridos2=$this->removeArrayAfromBV2($sugeridos2,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos2);

            //Sugeridos_3 FP 15 MESES y <30% PF o P. Año y CABAÑA
            $params=array(
                'facilidadparto'=>$toro->getFacilidadparto(),
            );
            $sugeridos3=  $repo->DinamycGet($toro,$params);
            $sugeridos3=  $this->removeNoTablaGenMenor30($sugeridos3,'PF');
            $sugeridos3=  $this->removeNoTablaGenMenor30($sugeridos3,'P.Año');
            $sugeridos3=$this->removeNotKeyWords($myclaves,$sugeridos3);
            $this->setSugeridos($toro,$sugeridos3);

            //Sugeridos_4 FP 15 MESES y <30% PF o P. Año
            $params=array(
                'facilidadparto'=>$toro->getFacilidadparto(),
            );
            $sugeridos4= $sugeridos7Base= $repo->DinamycGet($toro,$params);
            $sugeridos4=  $this->removeNoTablaGenMenor30($sugeridos4,'PF');
            $sugeridos4=  $this->removeNoTablaGenMenor30($sugeridos4,'P.Año');
            $sugeridos4=$this->removeNotKeyWords($myclaves,$sugeridos4);
            $this->setSugeridos($toro,$sugeridos4);

            //Sugeridos_5 FP 15 MESES y MOCHO
            $sugeridos5=$this->removeNotKeyWords($myclaves,$sugeridos5Base);
            $this->setSugeridos($toro,$sugeridos5);

            //Sugeridos_6 FP 15 MESES y CRIADOR
            $params=array(
                'facilidadparto'=>$toro->getFacilidadparto(),
                'criador'=>"'".$toro->getCriador()."'"

            );
            $sugeridos6= $sugeridos5Base= $repo->DinamycGet($toro,$params);
            $sugeridos6=$this->removeArrayAfromBV2($sugeridos6,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos6);

            //Especifico SIN FP
            if($toro->getFacilidadparto()==0 || $toro->getFacilidadparto()==null){

                $params=array(
                    'facilidadparto'=>24
                );
                $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                $this->setSugeridos($toro,$sugeridosFPMenor);

                $params=array(
                    'facilidadparto'=>18
                );
                $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                $this->setSugeridos($toro,$sugeridosFPMenor);

            }

            //Sugeridos_7 15 MESES
            $sugeridos7= $sugeridos6=$this->removeArrayAfromBV2($sugeridos7Base,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos7);

            if($toro->getFacilidadparto()==18){

                $params=array(
                    'facilidadparto'=>15
                );
                $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                $this->setSugeridos($toro,$sugeridosFPMenor);
            }else if($toro->getFacilidadparto()==24){

                $params=array(
                    'facilidadparto'=>18
                );
                $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                $this->setSugeridos($toro,$sugeridosFPMenor);

            }

            //Sugeridos_8 MOCHO Y CRIADOR
            $params=array(

                'criador'=>"'".$toro->getCriador()."'"

            );
            $sugeridos8=  $repo->DinamycGet($toro,$params);
            $sugeridos8=$this->removeArrayAfromBV2($sugeridos8,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos8);

            //Sugeridos_9
            $params=array(

            );
            $sugeridos9=  $repo->DinamycGet($toro,$params);
            $sugeridos9=$this->removeArrayAfromBV2($sugeridos9,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos9);

            //Sugeridos_10 CABAÑA
            $params=array(
            );
            $sugeridos10=  $repo->DinamycGet($toro,$params);
            $sugeridos10=$this->removeNotKeyWords($myclaves,$sugeridos10);
            $sugeridos10=$this->removeArrayAfromBV2($sugeridos10,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos10);

            //Sugeridos_11 CRIADOR
            $params=array(
                'criador'=>"'".$toro->getCriador()."'"
            );
            $sugeridos11=  $repo->DinamycGet($toro,$params);
            $sugeridos11=$this->removeArrayAfromBV2($sugeridos11,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos11);
        }

        //Caso 8 FP 15 MESES	CABAÑA	MOCHO	CRIADOR
        elseif($this->TienePalabrasClave($toro,$claves)  && $toro->getCriador()!=null)
        {
            //Sugeridos_1 FP 15 MESES Y CABAÑA Y MOCHO Y CRIADOR
            $params=array(
                'facilidadparto'=>$toro->getFacilidadparto(),

                'criador'=>"'".$toro->getCriador()."'"

            );
            $sugeridos=  $repo->DinamycGet($toro,$params);
            $sugeridos=$this->removeNotKeyWords($myclaves,$sugeridos);
            $this->setSugeridos($toro,$sugeridos);

            //Sugeridos_2 FP 15 MESES Y CABAÑA Y MOCHO
            $params=array(
                'facilidadparto'=>$toro->getFacilidadparto(),

            );
            $sugeridos2=$sugeridos4Base=  $repo->DinamycGet($toro,$params);
            $sugeridos2=$this->removeNotKeyWords($myclaves,$sugeridos2);
            $sugeridos2=$this->removeArrayAfromBV2($sugeridos2,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos2);

            //Sugeridos_3 FP 15 MESES Y CABAÑA
            $params=array(
                'facilidadparto'=>$toro->getFacilidadparto(),
            );
            $sugeridos3= $sugeridos6Base= $repo->DinamycGet($toro,$params);
            $sugeridos3=$this->removeNotKeyWords($myclaves,$sugeridos3);
            $sugeridos3=$this->removeArrayAfromBV2($sugeridos3,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos3);

            //Sugeridos_4 FP 15 MESES Y MOCHO
            $sugeridos4=$this->removeArrayAfromBV2($sugeridos4Base,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos4);

            //Sugeridos_5 FP 15 MESES y CRIADOR
            $params=array(
                'facilidadparto'=>$toro->getFacilidadparto(),
                'criador'=>"'".$toro->getCriador()."'"
            );
            $sugeridos5=  $repo->DinamycGet($toro,$params);
            $sugeridos5=$this->removeArrayAfromBV2($sugeridos5,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos5);

            //Especifico SIN FP
            if($toro->getFacilidadparto()==0 || $toro->getFacilidadparto()==null){

                $params=array(
                    'facilidadparto'=>24
                );
                $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                $this->setSugeridos($toro,$sugeridosFPMenor);

                $params=array(
                    'facilidadparto'=>18
                );
                $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                $this->setSugeridos($toro,$sugeridosFPMenor);

            }

            //Sugeridos_6 15 MESES
            $sugeridos6=$this->removeArrayAfromBV2($sugeridos6Base,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos6);

            if($toro->getFacilidadparto()==18){

                $params=array(
                    'facilidadparto'=>15
                );
                $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                $this->setSugeridos($toro,$sugeridosFPMenor);
            }else if($toro->getFacilidadparto()==24){

                $params=array(
                    'facilidadparto'=>18
                );
                $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                $this->setSugeridos($toro,$sugeridosFPMenor);

            }

            //Sugeridos_7 MOCHO Y CRIADOR
            $params=array(

                'criador'=>"'".$toro->getCriador()."'"
            );
            $sugeridos7=  $repo->DinamycGet($toro,$params);
            $sugeridos7=$this->removeArrayAfromBV2($sugeridos7,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos7);

            //Sugeridos_8 MOCHO
            $params=array(

            );
            $sugeridos8=  $repo->DinamycGet($toro,$params);
            $sugeridos8=$this->removeArrayAfromBV2($sugeridos8,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos8);

            //Sugeridos_9 CABAÑA
            $params=array(
            );
            $sugeridos9=  $repo->DinamycGet($toro,$params);
            $sugeridos9=$this->removeNotKeyWords($myclaves,$sugeridos9);
            $sugeridos9=$this->removeArrayAfromBV2($sugeridos9,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos9);

            //Sugeridos 10 CRIADOR
            $params=array(
                'criador'=>"'".$toro->getCriador()."'"
            );
            $sugeridos10=  $repo->DinamycGet($toro,$params);
            $sugeridos10=$this->removeArrayAfromBV2($sugeridos10,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos10);
        }

        //Caso 9 FP 15 MESES	MOCHO	CRIADOR
        elseif( $toro->getCriador()!=null)
        {
            //Sugeridos_1 FP 15 MESES Y MOCHO Y CRIADOR
            $params=array(
                'facilidadparto'=>$toro->getFacilidadparto(),

                'criador'=>"'".$toro->getCriador()."'"
            );
            $sugeridos=  $repo->DinamycGet($toro,$params);
            $this->setSugeridos($toro,$sugeridos);

            //Sugeridos_2 FP 15 MESES y MOCHO
            $params=array(
                'facilidadparto'=>$toro->getFacilidadparto(),

            );
            $sugeridos2= $sugeridos6Base= $repo->DinamycGet($toro,$params);
            $sugeridos2=$this->removeArrayAfromBV2($sugeridos2,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos2);

            //Sugeridos_3 FP 15 MESES Y CRIADOR
            $params=array(
                'facilidadparto'=>$toro->getFacilidadparto(),
                'criador'=>"'".$toro->getCriador()."'"
            );
            $sugeridos3=  $repo->DinamycGet($toro,$params);
            $sugeridos3=$this->removeArrayAfromBV2($sugeridos3,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos3);


            //Especifico SIN FP
            if($toro->getFacilidadparto()==0 || $toro->getFacilidadparto()==null){

                $params=array(
                    'facilidadparto'=>24
                );
                $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                $this->setSugeridos($toro,$sugeridosFPMenor);

                $params=array(
                    'facilidadparto'=>18
                );
                $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                $this->setSugeridos($toro,$sugeridosFPMenor);

            }

            //Sugeridos_4 15 MESES
            $params=array(
                'facilidadparto'=>$toro->getFacilidadparto(),
            );
            $sugeridos4=  $repo->DinamycGet($toro,$params);
            $sugeridos4=$this->removeArrayAfromBV2($sugeridos4,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos4);

            if($toro->getFacilidadparto()==18){

                $params=array(
                    'facilidadparto'=>15
                );
                $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                $this->setSugeridos($toro,$sugeridosFPMenor);
            }else if($toro->getFacilidadparto()==24){

                $params=array(
                    'facilidadparto'=>18
                );
                $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                $this->setSugeridos($toro,$sugeridosFPMenor);

            }

            //Sugeridos_5 MOCHO Y CRIADOR
            $params=array(

                'criador'=>"'".$toro->getCriador()."'"
            );
            $sugeridos5=  $repo->DinamycGet($toro,$params);
            $sugeridos5=$this->removeArrayAfromBV2($sugeridos5,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos5);

            //Sugeridos_6 MOCHO
            $params=array(

            );
            $sugeridos6=  $repo->DinamycGet($toro,$params);
            $sugeridos6=$this->removeArrayAfromBV2($sugeridos6,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos6);

            //Sugeridos_7 CRIADOR
            $params=array(
                'criador'=>"'".$toro->getCriador()."'"
            );
            $sugeridos7=  $repo->DinamycGet($toro,$params);
            $sugeridos7=$this->removeArrayAfromBV2($sugeridos7,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos7);
        }

        //Caso 10 FP 15 MESES	CRIADOR

        elseif($toro->getCriador()!=null)
        {
            //Sugeridos_1 FP 15 MESES Y CRIADOR
            $params=array(
                'facilidadparto'=>$toro->getFacilidadparto(),
                'criador'=>"'".$toro->getCriador()."'"
            );
            $sugeridos=  $repo->DinamycGet($toro,$params);
            $this->setSugeridos($toro,$sugeridos);

            //Especifico SIN FP
            if($toro->getFacilidadparto()==0 || $toro->getFacilidadparto()==null){

                $params=array(
                    'facilidadparto'=>24
                );
                $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                $this->setSugeridos($toro,$sugeridosFPMenor);

                $params=array(
                    'facilidadparto'=>18
                );
                $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                $this->setSugeridos($toro,$sugeridosFPMenor);

            }

            //Sugeridos_2 FP 15 MESES
            $params=array(
                'facilidadparto'=>$toro->getFacilidadparto(),
            );
            $sugeridos2= $sugeridos6Base= $repo->DinamycGet($toro,$params);
            $sugeridos2=$this->removeArrayAfromBV2($sugeridos2,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos2);

            if($toro->getFacilidadparto()==18){

                $params=array(
                    'facilidadparto'=>15
                );
                $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                $this->setSugeridos($toro,$sugeridosFPMenor);
            }else if($toro->getFacilidadparto()==24){

                $params=array(
                    'facilidadparto'=>18
                );
                $sugeridosFPMenor=$repo->DinamycGet($toro,$params);
                $sugeridosFPMenor=$this->removeArrayAfromBV2($sugeridosFPMenor,$toro->getTorosSugeridos());
                $this->setSugeridos($toro,$sugeridosFPMenor);

            }

            //Sugeridos_3 CRIADOR
            $params=array(
                'criador'=>"'".$toro->getCriador()."'"
            );
            $sugeridos3=  $repo->DinamycGet($toro,$params);
            $sugeridos3=$this->removeArrayAfromBV2($sugeridos3,$toro->getTorosSugeridos());
            $this->setSugeridos($toro,$sugeridos3);

        }


        $em->persist($toro);
        $em->flush();

    }



    function TienePalabrasClave($toro,$claves){
        if($claves==null)
            return 0;
        return count($this->PalabrasClave($toro,$claves))>0;
    }

    function PalabrasClave($toro,$claves){

        $descripc=$toro->getDescripcion();

        $clavesgenarray=explode(',',$claves);


        $myclavesarray=array();
        foreach($clavesgenarray as $clave)
            if (strpos($descripc, $clave) !== false)
                $myclavesarray[]=$clave;
        return $myclavesarray;


    }



     function getDatoFromTablaGen($toro,$rowhead,$dato)
     {
         try{
             if ($toro->getTablagenetica() != null) {
                 $datos = json_decode($toro->getTablagenetica(), true);
                 $tablaname = array_keys($datos);
                 $tablarname = $tablaname[0];
                 $tablasflag = $datos[$tablarname];
                 $pdDato = null;
                 //  print_r($tablasflag);die();
                 foreach ($tablasflag as $row) {

                     if ($row['rowhead'] == $rowhead) {

                         if (isset($row[$dato])) {
                             return floatval(str_replace('%','',$row[$dato]));
                         }
                     }
                 }
             }
             return null;
         }
         catch( \Exception $e){
             return null;
         }

     }


    function getDatoFromTablaGenMenor30($toro,$rowhead,$dato)
    {
        try{
            if ($toro->getTablagenetica() != null) {
                $datos = json_decode($toro->getTablagenetica(), true);
                $tablaname = array_keys($datos);
                $tablarname = $tablaname[0];
                $tablasflag = $datos[$tablarname];
                $pdDato = null;
                //  print_r($tablasflag);die();
                foreach ($tablasflag as $row) {

                    if ($row['rowhead'] == $rowhead) {

                        if (isset($row[$dato])) {
                            if(floatval(str_replace('%','',$row[$dato]))<=30)
                                return true;
                            return false;

                        }
                    }
                }
            }
            return false;
        }
        catch( \Exception $e){
            return false;
        }

    }

    function removeNoTablaGenMenor30($torosList,$attr){
        if(count($torosList)>0)

        foreach($torosList as $key=>$toro){

//            if($toro->getId()==325 && $attr=='PD')
//            {print('here');print($this->getDatoFromTablaGenMenor30($toro,'RANKING',$attr));}

            if($toro->getTablagenetica()==null)
                unset($torosList[$key]);
                elseif($attr=='PF'){
                   if($this->getDatoFromTablaGenMenor30($toro,'RANKING',$attr)!==true && $this->getDatoFromTablaGenMenor30($toro,'RANKING','P.Año')!==true)
                       unset($torosList[$key]);
                }
            elseif($attr=='P.Año'){
                if($this->getDatoFromTablaGenMenor30($toro,'RANKING',$attr)!==true && $this->getDatoFromTablaGenMenor30($toro,'RANKING','PF')!==true)
                    unset($torosList[$key]);
            }
           elseif( $this->getDatoFromTablaGenMenor30($toro,'RANKING',$attr)!==true)
           unset($torosList[$key]);
        }
        return $torosList;

    }

    function removeNotKeyWords($claves,$torosList){
        if(count($torosList)>0)
            foreach($torosList as $toro){
            if(!$this->TienePalabrasClave($toro,$claves))
                unset($toro);
            return $torosList;
        }


    }

    function TorosFindBy($array,$em){

        $repo=$em->getRepository('gemaBundle:Toro');
        return $repo->findBy($array);

    }

    function setSugeridos($toro,$arrayToros){
        if(count($arrayToros)>0)
        foreach($arrayToros as $t){
            $toro->setToroSugerido($t);
        }
    }



    function removeArrayAfromB($torosg, $actualsug, $id){
         foreach ($torosg as $key => $torog) {
             foreach($actualsug as $as){
                 if($torog->getId()==$as->getId() || $torog->getId()==$id)
                 {
                     unset($torosg[$key]);
                     break;
                 }
             }
         }
         return $torosg;
     }


    /**
     * Elimina los toros de A que ya esten en B o sea elimina de los actuales los ya existentes para q no existan repeticiones
     * @param $torosg
     * @param $actualsug     *
     * @return mixed
     */

    function removeArrayAfromBV2($torosg, $actualsug){
        if(count($torosg)>0 && count($actualsug)>0)
        foreach ($torosg as $key => $torog) {
            foreach($actualsug as $as){
                if($torog->getId()==$as->getId())
                {
                    unset($torosg[$key]);
                    break;
                }
            }
        }
        return $torosg;
    }

    function restantesRaza($toro,$repo,$em){
        if($toro->getRaza()->getFather()!=null){

            $qb = new QueryBuilder($em);
            $qb
                ->select("T","R","P")
                ->from('gemaBundle:Toro', "T")
                ->leftJoin('T.raza', "R")
                ->leftJoin('R.father', "P")
                ->Where("T.publico=1");
            $qb  ->andWhere('P.id='.$toro->getRaza()->getFather()->getId());
            $qb  ->andWhere('T.id<>'.$toro->getId());
            $torosg=$qb->getQuery()->getResult();
        }
        else
        $torosg=$repo->findBy(
            array(
                'raza'=>$toro->getRaza()
            )
        );
        $actualsug=$toro->getTorosSugeridos();
        return $this->removeArrayAfromB($torosg,$actualsug,$toro->getId());
    }

    function findClaveinToro($toro,$clave){
        $descripcion=$toro->getDescripcion();
        if($descripcion!=null and $descripcion!=''){
            if (strpos($descripcion, $clave) !== false)
                return true;
        }
        return false;
    }

}
