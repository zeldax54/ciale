<?php
namespace GEMA\gemaBundle\Helpers;
use \stdClass;
use GEMA\gemaBundle\Helpers\MyHelper;
class PdfHelper
{
    public static $razasdata = array(15,21,22,23,24);
    public function untoroPure($em,$hoja=null,$basedet=null){
          
        if($hoja==null){           
            $hoja = $em->getRepository('gemaBundle:Catalogohojas')->find(6);            
        }     
        $toro = $hoja->getToros()[0];
       return $this->unToroData($toro,$em,$basedet);
    }

    public function dostorospure($em,$hoja=null,$basedet=null){        
         
        if($hoja==null)        
            $hoja = $em->getRepository('gemaBundle:Catalogohojas')->find(5);          
         $toro1 = $hoja->getToros()[0];
         $toro2 = $hoja->getToros()[1];    
         return $this->DosTorosData($toro1,$toro2,$em,$basedet);      
        
      }

    public function unToroData($toro,$em,$basedet=null){
        $helper=new MyHelper();     
        $toro->foto = $helper->randomPic('toro'.DIRECTORY_SEPARATOR.$toro->getGuid().'P'.DIRECTORY_SEPARATOR);
        $toro->silueta = $helper->silueta($toro);         
       
        $toro->nacionalidadflag=$helper->Nacionalidad($toro->getNacionalidad());
        $toro->facilidadglag=$helper->imgFacilidadParto($toro->getFacilidadparto());
        if(count($toro->getYoutubes())>0)
        $toro->video=$toro->getYoutubes()[0]->getUrl();
        else
        $toro->video='#';
        $toro->detalleurl = $basedet.$helper->remove_accents($toro->getApodo());
        //Raza
        $helper->razaName($toro);       
        //////
        //table
				
           $tab = $helper->tablaSet($toro,$em);
           $tablasflag=$tab['tablaflag'];
           $tabla =$tab['tabla'];   
		   
        $t = new stdClass();
        $t->toro=$toro;
        $t->tabla=$tabla;             
        $t->tablagenetica=$tablasflag;        
        $t->razasdata=self::$razasdata;
        return $t;
    }
    
   

      public function DosTorosData($toro1,$toro2,$em,$basedet=null){
        $helper = new MyHelper();   
        $toro1->foto = $helper->randomPic('toro'.DIRECTORY_SEPARATOR.$toro1->getGuid().'P'.DIRECTORY_SEPARATOR);
        $toro2->foto = $helper->randomPic('toro'.DIRECTORY_SEPARATOR.$toro2->getGuid().'P'.DIRECTORY_SEPARATOR);

        $toro1->nacionalidadflag=$helper->Nacionalidad($toro1->getNacionalidad());
        $toro2->nacionalidadflag=$helper->Nacionalidad($toro2->getNacionalidad());

        $toro1->facilidadglag=$helper->imgFacilidadParto($toro1->getFacilidadparto());
        $toro2->facilidadglag=$helper->imgFacilidadParto($toro2->getFacilidadparto());  
        
        $toro1->silueta = $helper->silueta($toro1);  
        $toro2->silueta = $helper->silueta($toro2);  

        //raza        
         $helper->razaName($toro1);
         $helper->razaName($toro2);

        if(count($toro1->getYoutubes())>0)
          $toro1->video=$toro1->getYoutubes()[0]->getUrl();
        else
          $toro1->video='#';   
          $toro1->detalleurl = $basedet.$helper->remove_accents($toro1->getApodo());       
       if(count($toro2->getYoutubes())>0)
          $toro2->video=$toro2->getYoutubes()[0]->getUrl();
        else
          $toro2->video='#';    
          $toro2->detalleurl = $basedet.$helper->remove_accents($toro2->getApodo());      
          $tab1 = $helper->tablaSet($toro1,$em);
          $tablaflag1=$tab1['tablaflag'];
          $tabla1 =$tab1['tabla'];		
          //Val tabla 1
         
             
          $tab2 = $helper->tablaSet($toro2,$em);
          $tablaflag2 = $tab2['tablaflag'];
          $tabla2 = $tab2['tabla'];		 
          //val table2
        

       ////////////////////////////////////////////////
       $t = new stdClass();
       $t->toro1=$toro1;
       $t->toro2=$toro2;
       $t->tablaflag1=$tablaflag1;
       $t->tabla1=$tabla1;
       $t->tablaflag2=$tablaflag2;
       $t->tabla2=$tabla2;      
       $t->razasdata=self::$razasdata;
       return $t;

      }

      public function UnificadosDataFormed($torosIds,$impresion,$em,$basedet)
      {
        $unificados = array();	
        for($i=0;$i<count($torosIds);$i=$i+$impresion)
        {
             if($impresion==1)
             {
                $toro=$em->getRepository('gemaBundle:Toro')->find($torosIds[$i]);
                $u = new stdClass();
                $u->tipo=1;
                $u->data= $this->unToroData($toro,$em,$basedet);          
                $unificados[] = $u;            
             }
             else
             {
                $u = new stdClass();
                 if(!array_key_exists($i+1,$torosIds))
                 {                    
                    $toro=$em->getRepository('gemaBundle:Toro')->find($torosIds[$i]);
                    $u->tipo=1;
                    $u->data= $this->unToroData($toro,$em,$basedet);          
                    $unificados[] = $u; 
                 }
                 else
                 {
                    $toro1=$em->getRepository('gemaBundle:Toro')->find($torosIds[$i]);
                    $toro2=$em->getRepository('gemaBundle:Toro')->find($torosIds[$i+1]);                   
                    $u->tipo=2;
                    $u->data= $this->DosTorosData($toro1,$toro2,$em,$basedet);          
                    $unificados[] = $u; 
                 }
             }                 
        }
        return $unificados;

      }

      public function GeneratePdf($html,$pdfGenerator,$options,$filename,$webPath,$footer=false,$urlfooter='',$request=null){
                  
         if (!file_exists($webPath)) {
             mkdir($webPath, 0777, true);
         }
         $webPath=$webPath.$filename.'.pdf';
         $pdfGenerator->setTimeout(10000);
         foreach ($options as $margin => $value) {
            $pdfGenerator->setOption($margin, $value);
         } 
        
         if($footer)          
           $pdfGenerator->setOption('footer-html', $request->getScheme() . '://' . $request->getHttpHost() .$urlfooter);          
       
           $pdfGenerator->generateFromHtml(
            $html,
            $webPath
        );
        return $webPath;

      }

      private function valTabla($tabla){
         try{
         
         if($tabla['tablaflag'] == null || $tabla['tabla']==null)
             return false;
          return true;
        	
         }
         catch(\Exception $inner){
            return false;
            
         }    
        

      }
      private function GenerateBaseTableGen(){
      
         $final = array();
         $tabla = array();
         $heads = array('rowhead','A','B','C','D','E','F','PN');
         $val1 = array('PREC',1,2,3,4,5,6,7);
         $val2 = array('RANKING',1,2,3,4,5,6,7);
         $val3 = array('PROMEDIO',1,2,3,4,5,6);
         $tabla[]=array_combine($heads, $val1);
         $tabla[]=array_combine($heads, $val2);
         $tabla[]=array_combine($heads, $val3);      
         $final['tablaflag'] = $tabla;
          return $final;
      }

  
 
}