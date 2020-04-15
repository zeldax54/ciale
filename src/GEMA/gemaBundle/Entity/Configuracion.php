<?php

namespace GEMA\gemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Configuracion
 *
 * @ORM\Table(name="configuracion")
 *  @ORM\Entity(repositoryClass="GEMA\gemaBundle\Entity\ConfiguracionRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Configuracion
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="nombresitio", type="string", length=255)
     */
    private $nombresitio;

    /**
     * @var string
     * @ORM\Column(name="titulopagprinc", type="string", length=255)
     */
    private $titulopagprinc;

    /**
     * @var string
     * @ORM\Column(name="descripcgeneral", type="string", length=255)
     */
    private $descripcgeneral;

    /**
     * @var string
     * @ORM\Column(name="textopie", type="string", length=255)
     */
    private $textopie;

    /**
     * @var string
     * @ORM\Column(name="urldatafiscalafip", type="string", length=255)
     */
    private $urldatafiscalafip;

    /**
     * @var string
     * @ORM\Column(name="urlalta", type="string", length=255)
     */
    private $urlalta;

    /**
     * @var string
     * @ORM\Column(name="urlkoepon", type="string", length=255)
     */
    private $urlkoepon;

    /**
     * @var string
     * @ORM\Column(name="urlwebmail", type="string", length=255)
     */
    private $urlwebmail;

    /**
     * @var string
     * @ORM\Column(name="urlgplus", type="string", length=255)
     */
    private $urlgplus;

    /**
     * @var string
     * @ORM\Column(name="urlfacebook", type="string", length=255)
     */
    private $urlfacebook;

    /**
     * @var string
     * @ORM\Column(name="urlyoutube", type="string", length=255)
     */
    private $urlyoutube;

    /**
     * @var string
     * @ORM\Column(name="urllinkedin", type="string", length=255)
     */
    private $urllinkedin;

    /**
     * @var string
     * @ORM\Column(name="urlnoticiasinternacionales", type="string", length=255)
     */
    private $urlnoticiasinternacionales;

    /**
     * @var string
     * @ORM\Column(name="coordenadas", type="string", length=500)
     */
    private $coordenadas;

    /**
     * @var string
     * @ORM\Column(name="coordenadaslab", type="string", length=500)
     */
    private $coordenadaslab;

    /**
     * @ORM\Column(name="enviarmaildestinos", type="boolean", nullable=true)
     */
    private $enviarmaildestinos;


    /**
     * @ORM\Column(name="virtualurl", type="boolean", nullable=true)
     */
    private $virtualurl;

    /**
     * @var string
     * @ORM\Column(name="palabrasclave", type="string", length=500)
     */
    private $palabrasclave;

    /**
     * @var string
     * @ORM\Column(name="zoompdf", type="string", length=4)
     */
    private $zoompdf;

    /**
     * @ORM\Column(name="registerMailChimp", type="boolean", nullable=true)
     */
    private $registerMailChimp;

    /**
     * @var string
     * @ORM\Column(name="nombreContacto", type="string", length=100)
     */
    private $nombreContacto;

    /**
     * @var string
     * @ORM\Column(name="keyContacto", type="string", length=100)
     */
    private $keyContacto;

    /**
     * @var string
     * @ORM\Column(name="nombreVisita", type="string", length=100)
     */
    private $nombreVisita;

    /**
     * @var string
     * @ORM\Column(name="keyVisita", type="string", length=100)
     */
    private $keyVisita;

   /**
     * @ORM\Column(name="activarCompras", type="boolean", nullable=true)
     */
    private $activarCompras;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nombresitio
     *
     * @param string $nombresitio
     * @return Configuracion
     */
    public function setNombresitio($nombresitio)
    {
        $this->nombresitio = $nombresitio;

        return $this;
    }

    /**
     * Get nombresitio
     *
     * @return string 
     */
    public function getNombresitio()
    {
        return $this->nombresitio;
    }

    /**
     * Set titulopagprinc
     *
     * @param string $titulopagprinc
     * @return Configuracion
     */
    public function setTitulopagprinc($titulopagprinc)
    {
        $this->titulopagprinc = $titulopagprinc;

        return $this;
    }

    /**
     * Get titulopagprinc
     *
     * @return string 
     */
    public function getTitulopagprinc()
    {
        return $this->titulopagprinc;
    }

    /**
     * Set descripcgeneral
     *
     * @param string $descripcgeneral
     * @return Configuracion
     */
    public function setDescripcgeneral($descripcgeneral)
    {
        $this->descripcgeneral = $descripcgeneral;

        return $this;
    }

    /**
     * Get descripcgeneral
     *
     * @return string 
     */
    public function getDescripcgeneral()
    {
        return $this->descripcgeneral;
    }

    /**
     * Set textopie
     *
     * @param string $textopie
     * @return Configuracion
     */
    public function setTextopie($textopie)
    {
        $this->textopie = $textopie;

        return $this;
    }

    /**
     * Get textopie
     *
     * @return string 
     */
    public function getTextopie()
    {
        return $this->textopie;
    }

    /**
     * Set urldatafiscalafip
     *
     * @param string $urldatafiscalafip
     * @return Configuracion
     */
    public function setUrldatafiscalafip($urldatafiscalafip)
    {
        $this->urldatafiscalafip = $urldatafiscalafip;

        return $this;
    }

    /**
     * Get urldatafiscalafip
     *
     * @return string 
     */
    public function getUrldatafiscalafip()
    {
        return $this->urldatafiscalafip;
    }

    /**
     * Set urlalta
     *
     * @param string $urlalta
     * @return Configuracion
     */
    public function setUrlalta($urlalta)
    {
        $this->urlalta = $urlalta;

        return $this;
    }

    /**
     * Get urlalta
     *
     * @return string 
     */
    public function getUrlalta()
    {
        return $this->urlalta;
    }

    /**
     * Set urlkoepon
     *
     * @param string $urlkoepon
     * @return Configuracion
     */
    public function setUrlkoepon($urlkoepon)
    {
        $this->urlkoepon = $urlkoepon;

        return $this;
    }

    /**
     * Get urlkoepon
     *
     * @return string 
     */
    public function getUrlkoepon()
    {
        return $this->urlkoepon;
    }

    /**
     * Set urlwebmail
     *
     * @param string $urlwebmail
     * @return Configuracion
     */
    public function setUrlwebmail($urlwebmail)
    {
        $this->urlwebmail = $urlwebmail;

        return $this;
    }

    /**
     * Get urlwebmail
     *
     * @return string 
     */
    public function getUrlwebmail()
    {
        return $this->urlwebmail;
    }

    /**
     * Set urlgplus
     *
     * @param string $urlgplus
     * @return Configuracion
     */
    public function setUrlgplus($urlgplus)
    {
        $this->urlgplus = $urlgplus;

        return $this;
    }

    /**
     * Get urlgplus
     *
     * @return string 
     */
    public function getUrlgplus()
    {
        return $this->urlgplus;
    }

    /**
     * Set urlyoutube
     *
     * @param string $urlyoutube
     * @return Configuracion
     */
    public function setUrlyoutube($urlyoutube)
    {
        $this->urlyoutube = $urlyoutube;

        return $this;
    }

    /**
     * Get urlyoutube
     *
     * @return string 
     */
    public function getUrlyoutube()
    {
        return $this->urlyoutube;
    }

    /**
     * Set urllinkedin
     *
     * @param string $urllinkedin
     * @return Configuracion
     */
    public function setUrllinkedin($urllinkedin)
    {
        $this->urllinkedin = $urllinkedin;

        return $this;
    }

    /**
     * Get urllinkedin
     *
     * @return string 
     */
    public function getUrllinkedin()
    {
        return $this->urllinkedin;
    }

    /**
     * Set urlnoticiasinternacionales
     *
     * @param string $urlnoticiasinternacionales
     * @return Configuracion
     */
    public function setUrlnoticiasinternacionales($urlnoticiasinternacionales)
    {
        $this->urlnoticiasinternacionales = $urlnoticiasinternacionales;

        return $this;
    }

    /**
     * Get urlnoticiasinternacionales
     *
     * @return string 
     */
    public function getUrlnoticiasinternacionales()
    {
        return $this->urlnoticiasinternacionales;
    }

    /**
     * Set coordenadas
     *
     * @param string $coordenadas
     * @return Configuracion
     */
    public function setCoordenadas($coordenadas)
    {
        $this->coordenadas = $coordenadas;

        return $this;
    }

    /**
     * Get coordenadas
     *
     * @return string 
     */
    public function getCoordenadas()
    {
        return $this->coordenadas;
    }

    /**
     * Set enviarmaildestinos
     *
     * @param boolean $enviarmaildestinos
     * @return Configuracion
     */
    public function setEnviarmaildestinos($enviarmaildestinos)
    {
        $this->enviarmaildestinos = $enviarmaildestinos;

        return $this;
    }

    /**
     * Get enviarmaildestinos
     *
     * @return boolean 
     */
    public function getEnviarmaildestinos()
    {
        return $this->enviarmaildestinos;
    }

    /**
     * Set urlfacebook
     *
     * @param string $urlfacebook
     * @return Configuracion
     */
    public function setUrlfacebook($urlfacebook)
    {
        $this->urlfacebook = $urlfacebook;

        return $this;
    }

    /**
     * Get urlfacebook
     *
     * @return string 
     */
    public function getUrlfacebook()
    {
        return $this->urlfacebook;
    }

    /**
     * Set coordenadaslab
     *
     * @param string $coordenadaslab
     * @return Configuracion
     */
    public function setCoordenadaslab($coordenadaslab)
    {
        $this->coordenadaslab = $coordenadaslab;

        return $this;
    }

    /**
     * Get coordenadaslab
     *
     * @return string 
     */
    public function getCoordenadaslab()
    {
        return $this->coordenadaslab;
    }

    /**
     * Set virtualurl
     *
     * @param boolean $virtualurl
     * @return Configuracion
     */
    public function setVirtualurl($virtualurl)
    {
        $this->virtualurl = $virtualurl;

        return $this;
    }

    /**
     * Get virtualurl
     *
     * @return boolean 
     */
    public function getVirtualurl()
    {
        return $this->virtualurl;
    }

    /**
     * Set palabrasclave
     *
     * @param string $palabrasclave
     * @return Configuracion
     */
    public function setPalabrasclave($palabrasclave)
    {
        $this->palabrasclave = $palabrasclave;

        return $this;
    }

    /**
     * Get palabrasclave
     *
     * @return string 
     */
    public function getPalabrasclave()
    {
        return $this->palabrasclave;
    }

    /**
     * Set zoompdf
     *
     * @param string $zoompdf
     * @return Configuracion
     */
    public function setZoompdf($zoompdf)
    {
        $this->zoompdf = $zoompdf;

        return $this;
    }

    /**
     * Get zoompdf
     *
     * @return string 
     */
    public function getZoompdf()
    {
        return $this->zoompdf;
    }

    /**
     * Set nombreContacto
     *
     * @param string $nombreContacto
     * @return Configuracion
     */
    public function setNombreContacto($nombreContacto)
    {
        $this->nombreContacto = $nombreContacto;

        return $this;
    }

    /**
     * Get nombreContacto
     *
     * @return string 
     */
    public function getNombreContacto()
    {
        return $this->nombreContacto;
    }

    /**
     * Set keyContacto
     *
     * @param string $keyContacto
     * @return Configuracion
     */
    public function setKeyContacto($keyContacto)
    {
        $this->keyContacto = $keyContacto;

        return $this;
    }

    /**
     * Get keyContacto
     *
     * @return string 
     */
    public function getKeyContacto()
    {
        return $this->keyContacto;
    }

    /**
     * Set nombreVisita
     *
     * @param string $nombreVisita
     * @return Configuracion
     */
    public function setNombreVisita($nombreVisita)
    {
        $this->nombreVisita = $nombreVisita;

        return $this;
    }

    /**
     * Get nombreVisita
     *
     * @return string 
     */
    public function getNombreVisita()
    {
        return $this->nombreVisita;
    }

    /**
     * Set keyVisita
     *
     * @param string $keyVisita
     * @return Configuracion
     */
    public function setKeyVisita($keyVisita)
    {
        $this->keyVisita = $keyVisita;

        return $this;
    }

    /**
     * Get keyVisita
     *
     * @return string 
     */
    public function getKeyVisita()
    {
        return $this->keyVisita;
    }

    /**
     * Set registerMailChimp
     *
     * @param boolean $registerMailChimp
     * @return Configuracion
     */
    public function setRegisterMailChimp($registerMailChimp)
    {
        $this->registerMailChimp = $registerMailChimp;

        return $this;
    }

    /**
     * Get registerMailChimp
     *
     * @return boolean 
     */
    public function getRegisterMailChimp()
    {
        return $this->registerMailChimp;
    }

    /**
     * Set activarCompras
     *
     * @param boolean $activarCompras
     * @return Configuracion
     */
    public function setActivarCompras($activarCompras)
    {
        $this->activarCompras = $activarCompras;

        return $this;
    }

    /**
     * Get activarCompras
     *
     * @return boolean 
     */
    public function getActivarCompras()
    {
        return $this->activarCompras;
    }
}
