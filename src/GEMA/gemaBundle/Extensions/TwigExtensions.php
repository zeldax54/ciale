<?php
// Note that the namespace must match with
// your project !
namespace GEMA\gemaBundle\Extensions;

use Symfony\Bridge\Doctrine\RegistryInterface;

class TwigExtensions extends \Twig_Extension
{
    public function getFunctions()
    {
        // Register the function in twig :
        // In your template you can use it as : {{find(123)}}
        return array(
            new \Twig_SimpleFunction('find', array($this, 'find')),
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

    }

    public function getName()
    {
        return 'Twig myCustomName Extensions';
    }
}