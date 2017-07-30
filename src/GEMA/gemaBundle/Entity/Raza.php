<?php

namespace GEMA\gemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Raza
 *
 * @ORM\Table(name="raza")
 * @ORM\Entity(repositoryClass="GEMA\gemaBundle\Entity\RazaRepository")
 * @UniqueEntity(fields="nombre", message="Este raza ya existe")
 * @ORM\HasLifecycleCallbacks() 
 */
class Raza
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
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var string
     * @ORM\Column(name="silueta", type="string", length=255)
     */
    private $silueta;

    /**
     * @ORM\ManyToOne(targetEntity="GEMA\gemaBundle\Entity\TipoRaza",inversedBy="razas", cascade={"persist"})
     */

    private $tiporaza;

    /**
     * @var string
     * @ORM\Column(name="descripcion", type="text")
     */
    private $descripcion;

    /**
     * @var string
     * @ORM\Column(name="nombretablagenetica", type="string", length=255, nullable=true )
     */
    private $nombretablagenetica;

    /**
     * @ORM\ManyToOne(targetEntity="GEMA\gemaBundle\Entity\TipoTabla",inversedBy="razas", cascade={"persist"})
     */

    private $tipotabla;

    /**
     * @ORM\ManyToOne(targetEntity="GEMA\gemaBundle\Entity\Mapa",inversedBy="razas", cascade={"persist"})
     */

    private $mapa;

    /**
     * @ORM\Column(name="haverazaName", type="boolean", nullable=true)
     */
    private $haverazaName;

    /**
     * @ORM\Column(name="haveactEnlace", type="boolean", nullable=true)
     */
    private $haveactEnlace;

    /**
     * @ORM\ManyToOne(targetEntity="GEMA\gemaBundle\Entity\Decorador",inversedBy="razas", cascade={"persist"})
     */

    private $decorador;

    /**
     * @ORM\Column(name="publico", type="boolean", nullable=true)
     */
    private $publico;

    /**
     * @ORM\Column(name="redireccionarraza", type="boolean", nullable=true)
     */
    private $redireccionarraza;

    /**
     * @var string
     * @ORM\Column(name="redirUrl", type="string", length=700 )
     */
    private $redirUrl;

    /**
     * @ORM\Column(name="redirnewWindow", type="boolean", nullable=true)
     */
    private $redirnewWindow;

    /**
     * @ORM\Column(name="guid", type="string",length=255 ,nullable=false)
     */
    private $guid;

    /**
     * @ORM\OneToMany(targetEntity="GEMA\gemaBundle\Entity\Toro" , mappedBy="raza", cascade={"persist"})
     */

    private $toros;

    /**
     * @ORM\Column(name="mocho", type="boolean", nullable=true)
     */
    private $mocho;

    /**
     * @ORM\ManyToOne(targetEntity="GEMA\gemaBundle\Entity\Razafather",inversedBy="razas", cascade={"persist"})
     */

    private $father;

    /**
     * @ORM\Column(name="tablasmanual", type="boolean", nullable=true)
     */
    private $tablasmanual;


    public function __toString()
    {
        return $this->getNombre();
    }
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
     * Set nombre
     *
     * @param string $nombre
     * @return Raza
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return Raza
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set nombretablagenetica
     *
     * @param string $nombretablagenetica
     * @return Raza
     */
    public function setNombretablagenetica($nombretablagenetica)
    {
        $this->nombretablagenetica = $nombretablagenetica;

        return $this;
    }

    /**
     * Get nombretablagenetica
     *
     * @return string 
     */
    public function getNombretablagenetica()
    {
        return $this->nombretablagenetica;
    }

    /**
     * Set haverazaName
     *
     * @param boolean $haverazaName
     * @return Raza
     */
    public function setHaverazaName($haverazaName)
    {
        $this->haverazaName = $haverazaName;

        return $this;
    }

    /**
     * Get haverazaName
     *
     * @return boolean 
     */
    public function getHaverazaName()
    {
        return $this->haverazaName;
    }

    /**
     * Set haveactEnlace
     *
     * @param boolean $haveactEnlace
     * @return Raza
     */
    public function setHaveactEnlace($haveactEnlace)
    {
        $this->haveactEnlace = $haveactEnlace;

        return $this;
    }

    /**
     * Get haveactEnlace
     *
     * @return boolean 
     */
    public function getHaveactEnlace()
    {
        return $this->haveactEnlace;
    }

    /**
     * Set publico
     *
     * @param boolean $publico
     * @return Raza
     */
    public function setPublico($publico)
    {
        $this->publico = $publico;

        return $this;
    }

    /**
     * Get publico
     *
     * @return boolean 
     */
    public function getPublico()
    {
        return $this->publico;
    }

    /**
     * Set redireccionarraza
     *
     * @param boolean $redireccionarraza
     * @return Raza
     */
    public function setRedireccionarraza($redireccionarraza)
    {
        $this->redireccionarraza = $redireccionarraza;

        return $this;
    }

    /**
     * Get redireccionarraza
     *
     * @return boolean 
     */
    public function getRedireccionarraza()
    {
        return $this->redireccionarraza;
    }

    /**
     * Set redirUrl
     *
     * @param string $redirUrl
     * @return Raza
     */
    public function setRedirUrl($redirUrl)
    {
        $this->redirUrl = $redirUrl;

        return $this;
    }

    /**
     * Get redirUrl
     *
     * @return string 
     */
    public function getRedirUrl()
    {
        return $this->redirUrl;
    }

    /**
     * Set redirnewWindow
     *
     * @param boolean $redirnewWindow
     * @return Raza
     */
    public function setRedirnewWindow($redirnewWindow)
    {
        $this->redirnewWindow = $redirnewWindow;

        return $this;
    }

    /**
     * Get redirnewWindow
     *
     * @return boolean 
     */
    public function getRedirnewWindow()
    {
        return $this->redirnewWindow;
    }

    /**
     * Set tiporaza
     *
     * @param \GEMA\gemaBundle\Entity\TipoRaza $tiporaza
     * @return Raza
     */
    public function setTiporaza(\GEMA\gemaBundle\Entity\TipoRaza $tiporaza = null)
    {
        $this->tiporaza = $tiporaza;

        return $this;
    }

    /**
     * Get tiporaza
     *
     * @return \GEMA\gemaBundle\Entity\TipoRaza 
     */
    public function getTiporaza()
    {
        return $this->tiporaza;
    }

    /**
     * Set tipotabla
     *
     * @param \GEMA\gemaBundle\Entity\TipoTabla $tipotabla
     * @return Raza
     */
    public function setTipotabla(\GEMA\gemaBundle\Entity\TipoTabla $tipotabla = null)
    {
        $this->tipotabla = $tipotabla;

        return $this;
    }

    /**
     * Get tipotabla
     *
     * @return \GEMA\gemaBundle\Entity\TipoTabla 
     */
    public function getTipotabla()
    {
        return $this->tipotabla;
    }

    /**
     * Set decorador
     *
     * @param \GEMA\gemaBundle\Entity\Decorador $decorador
     * @return Raza
     */
    public function setDecorador(\GEMA\gemaBundle\Entity\Decorador $decorador = null)
    {
        $this->decorador = $decorador;

        return $this;
    }

    /**
     * Get decorador
     *
     * @return \GEMA\gemaBundle\Entity\Decorador 
     */
    public function getDecorador()
    {
        return $this->decorador;
    }

    /**
     * Set guid
     *
     * @param string $guid
     * @return Raza
     */
    public function setGuid($guid)
    {
        $this->guid = $guid;

        return $this;
    }

    /**
     * Get guid
     *
     * @return string 
     */
    public function getGuid()
    {
        return $this->guid;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->toros = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add toros
     *
     * @param \GEMA\gemaBundle\Entity\Toro $toros
     * @return Raza
     */
    public function addToro(\GEMA\gemaBundle\Entity\Toro $toros)
    {
        $this->toros[] = $toros;

        return $this;
    }

    /**
     * Remove toros
     *
     * @param \GEMA\gemaBundle\Entity\Toro $toros
     */
    public function removeToro(\GEMA\gemaBundle\Entity\Toro $toros)
    {
        $this->toros->removeElement($toros);
    }

    /**
     * Get toros
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getToros()
    {
        return $this->toros;
    }

    /**
     * Set mapa
     *
     * @param \GEMA\gemaBundle\Entity\Mapa $mapa
     * @return Raza
     */
    public function setMapa(\GEMA\gemaBundle\Entity\Mapa $mapa = null)
    {
        $this->mapa = $mapa;

        return $this;
    }

    /**
     * Get mapa
     *
     * @return \GEMA\gemaBundle\Entity\Mapa 
     */
    public function getMapa()
    {
        return $this->mapa;
    }

    /**
     * Set silueta
     *
     * @param string $silueta
     * @return Raza
     */
    public function setSilueta($silueta)
    {
        $this->silueta = $silueta;

        return $this;
    }

    /**
     * Get silueta
     *
     * @return string 
     */
    public function getSilueta()
    {
        return $this->silueta;
    }

    /**
     * Set mocho
     *
     * @param boolean $mocho
     * @return Raza
     */
    public function setMocho($mocho)
    {
        $this->mocho = $mocho;

        return $this;
    }

    /**
     * Get mocho
     *
     * @return boolean 
     */
    public function getMocho()
    {
        return $this->mocho;
    }

    /**
     * Set father
     *
     * @param \GEMA\gemaBundle\Entity\Razafather $father
     * @return Raza
     */
    public function setFather(\GEMA\gemaBundle\Entity\Razafather $father = null)
    {
        $this->father = $father;

        return $this;
    }

    /**
     * Get father
     *
     * @return \GEMA\gemaBundle\Entity\Razafather 
     */
    public function getFather()
    {
        return $this->father;
    }

    /**
     * Set tablasmanual
     *
     * @param boolean $tablasmanual
     * @return Raza
     */
    public function setTablasmanual($tablasmanual)
    {
        $this->tablasmanual = $tablasmanual;

        return $this;
    }

    /**
     * Get tablasmanual
     *
     * @return boolean 
     */
    public function getTablasmanual()
    {
        return $this->tablasmanual;
    }
}
