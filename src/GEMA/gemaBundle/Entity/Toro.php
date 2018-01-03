<?php

namespace GEMA\gemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\SerializedName;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;

/**
 * Toro
 *
 * @ORM\Table(name="toro")
 * @ORM\Entity(repositoryClass="GEMA\gemaBundle\Entity\ToroRepository")
 * @UniqueEntity(fields="nombre", message="Ya existe un toro con ese nombre")
 * @UniqueEntity(fields="nombreinterno", message="Ya existe un toro con ese nombre interno")
 * @ORM\HasLifecycleCallbacks()
 * @ExclusionPolicy("None")
 */
class Toro
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
     * @ORM\ManyToOne(targetEntity="GEMA\gemaBundle\Entity\Raza",inversedBy="toros", cascade={"persist"})
     * @Exclude
     */

    private $raza;


    /**
     * @var string
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var string
     * @ORM\Column(name="nacionalidad", type="string", length=255)
     */
    private $nacionalidad;


    /**
     * @var string
     * @ORM\Column(name="nombreinterno", type="string", length=255)
     */
    private $nombreinterno;

    /**
     * @var string
     * @ORM\Column(name="apodo", type="string", length=255)
     */
    private $apodo;

    /**
     * @var string
     * @ORM\Column(name="criador", type="string", length=255)
     */
    private $criador;

    /**
     * @var string
     * @ORM\Column(name="propietario", type="string",nullable=true, length=255)
     */
    private $propietario;

    /**
     * @var text
     * @ORM\Column(name="descripcion", type="text",nullable=true)
     */
    private $descripcion;

    /**
     * @ORM\Column(name="nuevo", type="boolean", nullable=true)
     */
    private $nuevo;

    /**
     * @ORM\Column(name="publico", type="boolean", nullable=true)
     */
    private $publico;


    //Pedrigree
    /**
     * @var string
     * @ORM\Column(name="padre", type="string", length=255, nullable=true)
     */
    private $padre;

    /**
     * @var string
     * @ORM\Column(name="madre", type="string", length=255, nullable=true)
     */
    private $madre;

    /**
     * @var string
     * @ORM\Column(name="padrepadre", type="string", length=255,nullable=true)
     */
    private $padrepadre;//Abuelo Paterno

    /**
     * @var string
     * @ORM\Column(name="madrepadre", type="string", length=255,nullable=true)
     */
    private $madrepadre;//Abuela Paterna

    /**
     * @var string
     * @ORM\Column(name="padremadre", type="string", length=255,nullable=true)
     */
    private $padremadre;//Abuelo materno

    /**
     * @var string
     * @ORM\Column(name="madremadre", type="string", length=255,nullable=true)
     */
    private $madremadre;//Abuela materna

    /**
     * @var string
     * @ORM\Column(name="padrepadrepadre", type="string", length=255,nullable=true)
     */
    private $padrepadrepadre;//Bisabuelo paterno paterno (Padre del padre del padre)

    /**
     * @var string
     * @ORM\Column(name="madrepadrepadre", type="string", length=255,nullable=true)
     */
    private $madrepadrepadre;//Bisabuela paterna paterna (Madre del padre del padre)

    /**
     * @var string
     * @ORM\Column(name="padremadrepadre", type="string", length=255,nullable=true)
     */
    private $padremadrepadre;//Bisabuelo materno paterno (Padre de la madre del padre)

    /**
     * @var string
     * @ORM\Column(name="madremadrepadre", type="string", length=255,nullable=true)
     */
    private $madremadrepadre;//Bisabuela materna paterna (Madre de la madre del padre)

    /**
     * @var string
     * @ORM\Column(name="padrepadremadre", type="string", length=255,nullable=true)
     */
    private $padrepadremadre;//Bisabuelo paterno materna (Padre del padre de la madre)

    /**
     * @var string
     * @ORM\Column(name="madrepadremadre", type="string", length=255,nullable=true)
     */
    private $madrepadremadre;//Bisabuela paterna materna (Madre del padre de la madre)

    /**
     * @var string
     * @ORM\Column(name="padremadremadre", type="string", length=255,nullable=true)
     */
    private $padremadremadre;//Bisabuelo materno materno (Padre de la madre de la madre)

    /**
     * @var string
     * @ORM\Column(name="madremadremadre", type="string", length=255,nullable=true)
     */
    private $madremadremadre;//Bisabuela materna materna (Madre de la madre de la madre)


    //Datos Geneticos

    /**
     * @var string
     * @ORM\Column(name="evaluaciongenetica", type="string", length=500,nullable=true)
     */
    private $evaluaciongenetica;

    /**
     * @var string
     * @ORM\Column(name="lineagenetica", type="string", length=500,nullable=true)
     */
    private $lineagenetica;


    /**
     * @var integer
     * @ORM\Column(name="facilidadparto", type="integer",nullable=true)
     */
    private $facilidadparto;

    /**
     * @ORM\Column(name="cp", type="boolean", nullable=true)
     * @SerializedName("cp")
     */
    private $CP;
    /**
     * @var string
     * @ORM\Column(name="rp", type="string",length=255,nullable=true)
     */
    private $rp;

    /**
     * @var string
     * @ORM\Column(name="HBA", type="string",length=255,nullable=true)
     * @SerializedName("hba")
     */
    private $HBA;

    /**
     * @var string
     * @ORM\Column(name="senasa", type="string",length=255,nullable=true)
     */
    private $senasa;

    /**
     * @var \DateTime
     * @ORM\Column(name="fechanacimiento", type="date",nullable=true)
     */
    private $fechanacimiento;

    /**
     * @var string
     * @ORM\Column(name="ADN", type="string",length=255,nullable=true)
     * @SerializedName("adn")
     */
    private $ADN;

    /**
     * @var string
     * @ORM\Column(name="circunferenciaescrotal", type="string",length=255,nullable=true)
     */
    private $circunferenciaescrotal;

    /**
     * @var string
     * @ORM\Column(name="largogrupa", type="string",length=255,nullable=true)
     */
    private $largogrupa;

    /**
     * @var string
     * @ORM\Column(name="anchogrupa", type="string",length=255,nullable=true)
     */
    private $anchogrupa;

    /**
     * @var string
     * @ORM\Column(name="altogrupa", type="string",length=255,nullable=true)
     */
    private $altogrupa;

    /**
     * @var string
     * @ORM\Column(name="largocorporal", type="string",length=255,nullable=true)
     */
    private $largocorporal;

    /**
     * @var string
     * @ORM\Column(name="peso", type="string",length=255,nullable=true)
     */
    private $peso;

    /**
     * @var string
     * @ORM\Column(name="enlacerefexterna", type="string",length=255,nullable=true)
     */
    private $enlacerefexterna;

    /**
     * @var text
     * @ORM\Column(name="tablagenetica", type="text",nullable=true)
     */
    private $tablagenetica;


    /**
     * @var string
     * @ORM\Column(name="pn1", type="string",length=255,nullable=true)
     */
    private $pn1;

    /**
     * @var string
     * @ORM\Column(name="p205d", type="string",length=255,nullable=true)
     */
    private $p205d;


    /**
     * @var string
     * @ORM\Column(name="p365d", type="string",length=255,nullable=true)
     */
    private $p365d;

    /**
     * @var string
     * @ORM\Column(name="p550d", type="string",length=255,nullable=true)
     */
    private $p550d;

    /**
     * @ORM\Column(name="guid", type="string",length=255 ,nullable=false)
     */
    private $guid;

    /**
     * @ORM\Column(name="precio", type="string",length=20 ,nullable=true)
     */
    private $precio;

    /**
     * @var integer
     * @ORM\Column(name="tipotablaselected", type="integer",nullable=true)
     */
    private $tipotablaselected;


    /**
     * @ORM\OneToMany(targetEntity="GEMA\gemaBundle\Entity\Youtube" , mappedBy="toro", cascade={"persist"})
     * @Exclude
     */

    private $youtubes;

    /**
     * @ORM\Column(name="mocho", type="boolean", nullable=true)
     */
    private $mocho;

    /**
     * @var string
     * @ORM\Column(name="nombreraza", type="string",length=255,nullable=true)
     */
    private $nombreraza;


    // ...

    /**
     * Many Users have Many Users.
     * @ManyToMany(targetEntity="Toro", mappedBy="torosSugeridos",cascade={"persist"})
     */
    private $torosquemeSugierem;

    /**
     * Many Users have many Users.
     * @ManyToMany(targetEntity="Toro", inversedBy="torosquemeSugierem",cascade={"persist"})
     * @JoinTable(name="sugeridos",
     *      joinColumns={@JoinColumn(name="toro_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="sugerido_toro_id", referencedColumnName="id")}
     *      )
     */
    private $torosSugeridos;


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
     * Set raza
     *
     * @param \GEMA\gemaBundle\Entity\Raza $raza
     * @return Toro
     */
    public function setRaza(\GEMA\gemaBundle\Entity\Raza $raza = null)
    {
        $this->raza = $raza;
        return $this;
    }

    /**
     * Get raza
     *
     * @return \GEMA\gemaBundle\Entity\Raza 
     */
    public function getRaza()
    {
        return $this->raza;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Toro
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
     * Set nombreinterno
     *
     * @param string $nombreinterno
     * @return Toro
     */
    public function setNombreinterno($nombreinterno)
    {
        $this->nombreinterno = $nombreinterno;

        return $this;
    }

    /**
     * Get nombreinterno
     *
     * @return string 
     */
    public function getNombreinterno()
    {
        return $this->nombreinterno;
    }

    /**
     * Set apodo
     *
     * @param string $apodo
     * @return Toro
     */
    public function setApodo($apodo)
    {
        $this->apodo = $apodo;

        return $this;
    }

    /**
     * Get apodo
     *
     * @return string 
     */
    public function getApodo()
    {
        return $this->apodo;
    }

    /**
     * Set criador
     *
     * @param string $criador
     * @return Toro
     */
    public function setCriador($criador)
    {
        $this->criador = $criador;

        return $this;
    }

    /**
     * Get criador
     *
     * @return string 
     */
    public function getCriador()
    {
        return $this->criador;
    }

    /**
     * Set propietario
     *
     * @param string $propietario
     * @return Toro
     */
    public function setPropietario($propietario)
    {
        $this->propietario = $propietario;

        return $this;
    }

    /**
     * Get propietario
     *
     * @return string 
     */
    public function getPropietario()
    {
        return $this->propietario;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return Toro
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
     * Set nuevo
     *
     * @param boolean $nuevo
     * @return Toro
     */
    public function setNuevo($nuevo)
    {
        $this->nuevo = $nuevo;

        return $this;
    }

    /**
     * Get nuevo
     *
     * @return boolean 
     */
    public function getNuevo()
    {
        return $this->nuevo;
    }

    /**
     * Set publico
     *
     * @param boolean $publico
     * @return Toro
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
     * Set padre
     *
     * @param string $padre
     * @return Toro
     */
    public function setPadre($padre)
    {
        $this->padre = $padre;

        return $this;
    }

    /**
     * Get padre
     *
     * @return string 
     */
    public function getPadre()
    {
        return $this->padre;
    }

    /**
     * Set madre
     *
     * @param string $madre
     * @return Toro
     */
    public function setMadre($madre)
    {
        $this->madre = $madre;

        return $this;
    }

    /**
     * Get madre
     *
     * @return string 
     */
    public function getMadre()
    {
        return $this->madre;
    }

    /**
     * Set padrepadre
     *
     * @param string $padrepadre
     * @return Toro
     */
    public function setPadrepadre($padrepadre)
    {
        $this->padrepadre = $padrepadre;

        return $this;
    }

    /**
     * Get padrepadre
     *
     * @return string 
     */
    public function getPadrepadre()
    {
        return $this->padrepadre;
    }

    /**
     * Set madrepadre
     *
     * @param string $madrepadre
     * @return Toro
     */
    public function setMadrepadre($madrepadre)
    {
        $this->madrepadre = $madrepadre;

        return $this;
    }

    /**
     * Get madrepadre
     *
     * @return string 
     */
    public function getMadrepadre()
    {
        return $this->madrepadre;
    }

    /**
     * Set padremadre
     *
     * @param string $padremadre
     * @return Toro
     */
    public function setPadremadre($padremadre)
    {
        $this->padremadre = $padremadre;

        return $this;
    }

    /**
     * Get padremadre
     *
     * @return string 
     */
    public function getPadremadre()
    {
        return $this->padremadre;
    }

    /**
     * Set madremadre
     *
     * @param string $madremadre
     * @return Toro
     */
    public function setMadremadre($madremadre)
    {
        $this->madremadre = $madremadre;

        return $this;
    }

    /**
     * Get madremadre
     *
     * @return string 
     */
    public function getMadremadre()
    {
        return $this->madremadre;
    }

    /**
     * Set padrepadrepadre
     *
     * @param string $padrepadrepadre
     * @return Toro
     */
    public function setPadrepadrepadre($padrepadrepadre)
    {
        $this->padrepadrepadre = $padrepadrepadre;

        return $this;
    }

    /**
     * Get padrepadrepadre
     *
     * @return string 
     */
    public function getPadrepadrepadre()
    {
        return $this->padrepadrepadre;
    }

    /**
     * Set madrepadrepadre
     *
     * @param string $madrepadrepadre
     * @return Toro
     */
    public function setMadrepadrepadre($madrepadrepadre)
    {
        $this->madrepadrepadre = $madrepadrepadre;

        return $this;
    }

    /**
     * Get madrepadrepadre
     *
     * @return string 
     */
    public function getMadrepadrepadre()
    {
        return $this->madrepadrepadre;
    }

    /**
     * Set padremadrepadre
     *
     * @param string $padremadrepadre
     * @return Toro
     */
    public function setPadremadrepadre($padremadrepadre)
    {
        $this->padremadrepadre = $padremadrepadre;

        return $this;
    }

    /**
     * Get padremadrepadre
     *
     * @return string 
     */
    public function getPadremadrepadre()
    {
        return $this->padremadrepadre;
    }

    /**
     * Set madremadrepadre
     *
     * @param string $madremadrepadre
     * @return Toro
     */
    public function setMadremadrepadre($madremadrepadre)
    {
        $this->madremadrepadre = $madremadrepadre;

        return $this;
    }

    /**
     * Get madremadrepadre
     *
     * @return string 
     */
    public function getMadremadrepadre()
    {
        return $this->madremadrepadre;
    }

    /**
     * Set padrepadremadre
     *
     * @param string $padrepadremadre
     * @return Toro
     */
    public function setPadrepadremadre($padrepadremadre)
    {
        $this->padrepadremadre = $padrepadremadre;

        return $this;
    }

    /**
     * Get padrepadremadre
     *
     * @return string 
     */
    public function getPadrepadremadre()
    {
        return $this->padrepadremadre;
    }

    /**
     * Set madrepadremadre
     *
     * @param string $madrepadremadre
     * @return Toro
     */
    public function setMadrepadremadre($madrepadremadre)
    {
        $this->madrepadremadre = $madrepadremadre;

        return $this;
    }

    /**
     * Get madrepadremadre
     *
     * @return string 
     */
    public function getMadrepadremadre()
    {
        return $this->madrepadremadre;
    }

    /**
     * Set padremadremadre
     *
     * @param string $padremadremadre
     * @return Toro
     */
    public function setPadremadremadre($padremadremadre)
    {
        $this->padremadremadre = $padremadremadre;

        return $this;
    }

    /**
     * Get padremadremadre
     *
     * @return string 
     */
    public function getPadremadremadre()
    {
        return $this->padremadremadre;
    }

    /**
     * Set madremadremadre
     *
     * @param string $madremadremadre
     * @return Toro
     */
    public function setMadremadremadre($madremadremadre)
    {
        $this->madremadremadre = $madremadremadre;

        return $this;
    }

    /**
     * Get madremadremadre
     *
     * @return string 
     */
    public function getMadremadremadre()
    {
        return $this->madremadremadre;
    }

    /**
     * Set evaluaciongenetica
     *
     * @param string $evaluaciongenetica
     * @return Toro
     */
    public function setEvaluaciongenetica($evaluaciongenetica)
    {
        $this->evaluaciongenetica = $evaluaciongenetica;

        return $this;
    }

    /**
     * Get evaluaciongenetica
     *
     * @return string 
     */
    public function getEvaluaciongenetica()
    {
        return $this->evaluaciongenetica;
    }



    /**
     * Set facilidadparto
     *
     * @param integer $facilidadparto
     * @return Toro
     */
    public function setFacilidadparto($facilidadparto)
    {
        $this->facilidadparto = $facilidadparto;

        return $this;
    }

    /**
     * Get facilidadparto
     *
     * @return integer 
     */
    public function getFacilidadparto()
    {
        return $this->facilidadparto;
    }

    /**
     * Set rp
     *
     * @param float $rp
     * @return Toro
     */
    public function setRp($rp)
    {
        $this->rp = $rp;

        return $this;
    }

    /**
     * Get rp
     *
     * @return float 
     */
    public function getRp()
    {
        return $this->rp;
    }

    /**
     * Set HBA
     *
     * @param string $hBA
     * @return Toro
     */
    public function setHBA($hBA)
    {
        $this->HBA = $hBA;

        return $this;
    }

    /**
     * Get HBA
     *
     * @return string 
     */
    public function getHBA()
    {
        return $this->HBA;
    }

    /**
     * Set senasa
     *
     * @param string $senasa
     * @return Toro
     */
    public function setSenasa($senasa)
    {
        $this->senasa = $senasa;

        return $this;
    }

    /**
     * Get senasa
     *
     * @return string 
     */
    public function getSenasa()
    {
        return $this->senasa;
    }

    /**
     * Set fechanacimiento
     *
     * @param \DateTime $fechanacimiento
     * @return Toro
     */
    public function setFechanacimiento($fechanacimiento)
    {
        $this->fechanacimiento = $fechanacimiento;

        return $this;
    }

    /**
     * Get fechanacimiento
     *
     * @return \DateTime 
     */
    public function getFechanacimiento()
    {
        return $this->fechanacimiento;
    }

    /**
     * Set ADN
     *
     * @param string $aDN
     * @return Toro
     */
    public function setADN($aDN)
    {
        $this->ADN = $aDN;

        return $this;
    }

    /**
     * Get ADN
     *
     * @return string 
     */
    public function getADN()
    {
        return $this->ADN;
    }

    /**
     * Set circunferenciaescrotal
     *
     * @param string $circunferenciaescrotal
     * @return Toro
     */
    public function setCircunferenciaescrotal($circunferenciaescrotal)
    {
        $this->circunferenciaescrotal = $circunferenciaescrotal;

        return $this;
    }

    /**
     * Get circunferenciaescrotal
     *
     * @return string 
     */
    public function getCircunferenciaescrotal()
    {
        return $this->circunferenciaescrotal;
    }

    /**
     * Set largogrupa
     *
     * @param string $largogrupa
     * @return Toro
     */
    public function setLargogrupa($largogrupa)
    {
        $this->largogrupa = $largogrupa;

        return $this;
    }

    /**
     * Get largogrupa
     *
     * @return string 
     */
    public function getLargogrupa()
    {
        return $this->largogrupa;
    }

    /**
     * Set anchogrupa
     *
     * @param string $anchogrupa
     * @return Toro
     */
    public function setAnchogrupa($anchogrupa)
    {
        $this->anchogrupa = $anchogrupa;

        return $this;
    }

    /**
     * Get anchogrupa
     *
     * @return string 
     */
    public function getAnchogrupa()
    {
        return $this->anchogrupa;
    }

    /**
     * Set altogrupa
     *
     * @param string $altogrupa
     * @return Toro
     */
    public function setAltogrupa($altogrupa)
    {
        $this->altogrupa = $altogrupa;

        return $this;
    }

    /**
     * Get altogrupa
     *
     * @return string 
     */
    public function getAltogrupa()
    {
        return $this->altogrupa;
    }

    /**
     * Set largocorporal
     *
     * @param string $largocorporal
     * @return Toro
     */
    public function setLargocorporal($largocorporal)
    {
        $this->largocorporal = $largocorporal;

        return $this;
    }

    /**
     * Get largocorporal
     *
     * @return string 
     */
    public function getLargocorporal()
    {
        return $this->largocorporal;
    }

    /**
     * Set peso
     *
     * @param string $peso
     * @return Toro
     */
    public function setPeso($peso)
    {
        $this->peso = $peso;

        return $this;
    }

    /**
     * Get peso
     *
     * @return string 
     */
    public function getPeso()
    {
        return $this->peso;
    }

    /**
     * Set enlacerefexterna
     *
     * @param string $enlacerefexterna
     * @return Toro
     */
    public function setEnlacerefexterna($enlacerefexterna)
    {
        $this->enlacerefexterna = $enlacerefexterna;

        return $this;
    }

    /**
     * Get enlacerefexterna
     *
     * @return string 
     */
    public function getEnlacerefexterna()
    {
        return $this->enlacerefexterna;
    }

    /**
     * Set tablagenetica
     *
     * @param string $tablagenetica
     * @return Toro
     */
    public function setTablagenetica($tablagenetica)
    {
        $this->tablagenetica = $tablagenetica;

        return $this;
    }

    /**
     * Get tablagenetica
     *
     * @return string 
     */
    public function getTablagenetica()
    {
        return $this->tablagenetica;
    }

    /**
     * Set pn1
     *
     * @param string $pn1
     * @return Toro
     */
    public function setPn1($pn1)
    {
        $this->pn1 = $pn1;

        return $this;
    }

    /**
     * Get pn1
     *
     * @return string 
     */
    public function getPn1()
    {
        return $this->pn1;
    }

    /**
     * Set p205d
     *
     * @param string $p205d
     * @return Toro
     */
    public function setP205d($p205d)
    {
        $this->p205d = $p205d;

        return $this;
    }

    /**
     * Get p205d
     *
     * @return string 
     */
    public function getP205d()
    {
        return $this->p205d;
    }

    /**
     * Set p365d
     *
     * @param string $p365d
     * @return Toro
     */
    public function setP365d($p365d)
    {
        $this->p365d = $p365d;

        return $this;
    }

    /**
     * Get p365d
     *
     * @return string 
     */
    public function getP365d()
    {
        return $this->p365d;
    }

    /**
     * Set p550d
     *
     * @param string $p550d
     * @return Toro
     */
    public function setP550d($p550d)
    {
        $this->p550d = $p550d;

        return $this;
    }

    /**
     * Get p550d
     *
     * @return string 
     */
    public function getP550d()
    {
        return $this->p550d;
    }

    /**
     * Set guid
     *
     * @param string $guid
     * @return Toro
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
     * Set lineagenetica
     *
     * @param string $lineagenetica
     * @return Toro
     */
    public function setLineagenetica($lineagenetica)
    {
        $this->lineagenetica = $lineagenetica;

        return $this;
    }

    /**
     * Get lineagenetica
     *
     * @return string 
     */
    public function getLineagenetica()
    {
        return $this->lineagenetica;
    }

    /**
     * Set CP
     *
     * @param boolean $cP
     * @return Toro
     */
    public function setCP($cP)
    {
        $this->CP = $cP;

        return $this;
    }

    /**
     * Get CP
     *
     * @return boolean 
     */
    public function getCP()
    {
        return $this->CP;
    }

    /**
     * Set tipotablaselected
     *
     * @param integer $tipotablaselected
     * @return Toro
     */
    public function setTipotablaselected($tipotablaselected)
    {
        $this->tipotablaselected = $tipotablaselected;

        return $this;
    }

    /**
     * Get tipotablaselected
     *
     * @return integer 
     */
    public function getTipotablaselected()
    {
        return $this->tipotablaselected;
    }

    /**
     * Set nacionalidad
     *
     * @param string $nacionalidad
     * @return Toro
     */
    public function setNacionalidad($nacionalidad)
    {
        $this->nacionalidad = $nacionalidad;

        return $this;
    }

    /**
     * Get nacionalidad
     *
     * @return string 
     */
    public function getNacionalidad()
    {
        return $this->nacionalidad;
    }

    /**
     * Set precio
     *
     * @param string $precio
     * @return Toro
     */
    public function setPrecio($precio)
    {
        $this->precio = $precio;

        return $this;
    }

    /**
     * Get precio
     *
     * @return string 
     */
    public function getPrecio()
    {
        return $this->precio;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->youtubes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->torossugeridos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->torosquemesigieren = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add youtubes
     *
     * @param \GEMA\gemaBundle\Entity\Youtube $youtube
     * @return Toro
     */
    public function addYoutube(\GEMA\gemaBundle\Entity\Youtube $youtube)
    {
        $youtube->setToro($this);
        $this->youtubes->add($youtube);
    }

    /**
     * Remove youtubes
     *
     * @param \GEMA\gemaBundle\Entity\Youtube $youtubes
     */
    public function removeYoutube(\GEMA\gemaBundle\Entity\Youtube $youtubes)
    {

        $this->youtubes->removeElement($youtubes);
    }

    /**
     * Get youtubes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getYoutubes()
    {
        return $this->youtubes;
    }

    public function setYoutubes(\Doctrine\Common\Collections\Collection $youtubes)
    {
        $this->youtubes = $youtubes;
        foreach ($youtubes as $y) {
            $y->setToro($this);
        }
    }

    public function __toString()
    {
        return $this->getNombre();
    }



    /**
     * Set mocho
     *
     * @param string $mocho
     * @return Toro
     */
    public function setMocho($mocho)
    {
        $this->mocho = $mocho;

        return $this;
    }

    /**
     * Get mocho
     *
     * @return string 
     */
    public function getMocho()
    {
        return $this->mocho;
    }

    /**
     * Set nombreraza
     *
     * @param string $nombreraza
     * @return Toro
     */
    public function setNombreraza($nombreraza)
    {
        $this->nombreraza = $nombreraza;

        return $this;
    }

    /**
     * Get nombreraza
     *
     * @return string 
     */
    public function getNombreraza()
    {
        return $this->nombreraza;
    }









    /**
     * Add torosquemeSugierem
     *
     * @param \GEMA\gemaBundle\Entity\Toro $torosquemeSugierem
     * @return Toro
     */
    public function addTorosquemeSugierem(\GEMA\gemaBundle\Entity\Toro $torosquemeSugierem)
    {
        $this->torosquemeSugierem[] = $torosquemeSugierem;
        $torosquemeSugierem->addTorosSugerido($this);
        return $this;
    }

    /**
     * Remove torosquemeSugierem
     *
     * @param \GEMA\gemaBundle\Entity\Toro $torosquemeSugierem
     */
    public function removeTorosquemeSugierem(\GEMA\gemaBundle\Entity\Toro $torosquemeSugierem)
    {
        $this->torosquemeSugierem->removeElement($torosquemeSugierem);
    }

    /**
     * Get torosquemeSugierem
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTorosquemeSugierem()
    {
        return $this->torosquemeSugierem;
    }

    /**
     * Add torosSugeridos
     *
     * @param \GEMA\gemaBundle\Entity\Toro $torosSugeridos
     * @return Toro
     */
    public function addTorosSugerido(\GEMA\gemaBundle\Entity\Toro $torosSugeridos)
    {
        $this->torosSugeridos[] = $torosSugeridos;
        $torosSugeridos->addTorosquemeSugierem($this);
        return $this;
    }

    /**
     * Remove torosSugeridos
     *
     * @param \GEMA\gemaBundle\Entity\Toro $torosSugeridos
     */
    public function removeTorosSugerido(\GEMA\gemaBundle\Entity\Toro $torosSugeridos)
    {
        $this->torosSugeridos->removeElement($torosSugeridos);
    }

    /**
     * Get torosSugeridos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTorosSugeridos()
    {
        return $this->torosSugeridos;
    }
}
