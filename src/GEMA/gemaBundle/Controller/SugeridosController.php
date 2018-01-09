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
            $queryEntities1 = $em->createQuery('select t from gemaBundle:Toro t');
            $iterableEntities1 = $queryEntities1->iterate();
            while (($toro = $iterableEntities1->next()) !== false) {
                $this->UpdateToro($toro[0]);

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
            $toro=$repo->find($id);
            $this->UpdateToro($toro);

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

     function removeArrayAfromB($torosg,$actualsug,$id){
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
