<?php
// Note that the namespace must match with
// your project !
namespace GEMA\gemaBundle\Extensions;

use Symfony\Bridge\Doctrine\RegistryInterface;
use GEMA\gemaBundle\Helpers\MyHelper;

class TwigExtensions extends \Twig_Extension
{
    public function getFunctions()
    {
        // Register the function in twig :
        // In your template you can use it as : {{find(123)}}
        return array(
            new \Twig_SimpleFunction('find', array($this, 'find')),
            new \Twig_SimpleFunction('isNumber', array($this, 'isNumber')),
            new \Twig_SimpleFunction('recortar', array($this, 'recortar')),
            new \Twig_SimpleFunction('productos', array($this, 'productos')),
            new \Twig_SimpleFunction('programas', array($this, 'programas')),
            new \Twig_SimpleFunction('tipoTest', array($this, 'tipoTest')),

        );
    }

    protected $doctrine;
    // Retrieve doctrine from the constructor
    public function __construct(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function find($param){
        $em = $this->doctrine->getManager();
        $myRepo = $em->getRepository('gemaBundle:Configuracion');
        if($param=='nombresitio')
        return $myRepo->find(1)->getNombresitio();
        if($param=='title')
            return $myRepo->find(1)->getTitulopagprinc();
        if($param=='lecheinter')
            return $myRepo->find(1)->getUrlnoticiasinternacionales();
        if($param=='textofooter'){
            return $myRepo->find(1)->getTextopie();
        }
        if($param=='youtube'){
            return $myRepo->find(1)->getUrlyoutube();
        }
        if($param=='facebook'){
            return $myRepo->find(1)->getUrlfacebook();
        }
        if($param=='linkedin'){
            return $myRepo->find(1)->getUrllinkedin();
        }
        if($param=='koepon'){
            return $myRepo->find(1)->getUrlkoepon();
        }
        if($param=='afip'){
            return $myRepo->find(1)->getUrldatafiscalafip();
        }
        if($param=='mailer'){
            return $myRepo->find(1)->getUrlwebmail();
        }

    }

    function isNumber($text){
      $text=str_replace('%','',$text);
        if(is_numeric($text)==true)
            return '1';
        return '0';
    }

    function recortar($number){
        $number=str_replace('%','',$number);
        $part= explode('.',$number);

        if(!isset($part[1]))
            return $part[0];

        $numberchar= strlen($part[1]);
        if($numberchar>=3){
            $partdecimal=substr($part[1], 0,  3);
            if($this->checkZeros($partdecimal))
                return $part[0];
            if(substr($partdecimal,-1)=='0')
                $partdecimal=substr($part[1], 0,  2);
        }
        else{
            $partdecimal=$part[1];
        }

          return $part[0].'.'.$partdecimal;
    }

    function checkZeros($strinf){
        for ($i = 1; $i < strlen($strinf); $i++) {

            if($strinf[$i]!='0')
                return false;
        }
        return true;
    }

    public function productos(){
        $em=$this->doctrine->getManager();
        return $em->getRepository('gemaBundle:Productosprogramas')->findBytipo('Producto');

    }

    public function programas(){
        $em=$this->doctrine->getManager();
        return $em->getRepository('gemaBundle:Productosprogramas')->findBytipo('Programa');

    }
    function IsNullOrEmptyString($str){
        return (!isset($str) || trim($str) === '');
    }

    public function tipoTest($toro){     
       $helper=new MyHelper();  
       $razaid = $toro->getRaza()->getId(); 

        if ($helper->isPelaje($razaid)==true){
            if(!$this->IsNullOrEmptyString($toro->getPruebapelaje()))
              return '<strong class="pelajedos pelaje" style="color: #187ac8;">TEST DE PELAJE:</strong>&nbsp;'.$toro->getPruebapelaje(); 
        }
  
        else  if ($helper->isAstado($razaid)==true) {
            if(!$this->IsNullOrEmptyString($toro->getPruebapelaje()))
             return    '<strong class="pelajedos pelaje" style="color: #187ac8;">Test de MOCHO/ASTADO:</strong>&nbsp;'.$toro->getPruebapelaje(); 
        }
      
        else  if ($helper->isAmbosTests($razaid)==true) {
            $var ='';
            if(!$this->IsNullOrEmptyString($toro->getPruebapelaje()))
               $var.='<strong class="pelajedos pelaje" style="color: #187ac8;">Test de PELAJE:</strong>&nbsp;'.$toro->getPruebapelaje().'<br>';
               if(!$this->IsNullOrEmptyString($toro->getPruebaastadoOtrasRzas()))
                 $var.='<strong class="pelajedos pelaje" style="color: #187ac8;">Test de MOCHO/ASTADO:</strong>&nbsp;'.$toro->getPruebaastadoOtrasRzas();    
            return $var;
        }       
        
    }


    public function getName()
    {
        return 'Twig myCustomName Extensions';
    }
}