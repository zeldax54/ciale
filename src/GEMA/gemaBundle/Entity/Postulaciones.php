<?php

namespace GEMA\gemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;




/**
 * CategoriaNoticia
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="GEMA\gemaBundle\Entity\PostulacionesRepository")
 * @ORM\Table(name="postulaciones")

 */
class Postulaciones {

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
     * @ORM\Column(name="nombre", type="string", length=1500)
     */
    private $nombre;

    /**
     * @var string
     * @ORM\Column(name="apellido", type="string", length=1500)
     */
    private $apellido;

    /**
     * @var string
     * @ORM\Column(name="fecha", type="date",nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="email", type="string",length=500 ,nullable=false)
     */
    private $email;

    /**
     * @ORM\Column(name="telefono", type="string",length=500 ,nullable=false)
     */
    private $telefono;

    /**
     * @ORM\Column(name="archivo", type="string",length=500 ,nullable=false)
     */
    private $archivo;

    /**
     * @ORM\ManyToOne(targetEntity="GEMA\gemaBundle\Entity\OfertaLaboral",inversedBy="postulaciones", cascade={"persist"})
     */

    private $oferta;

    /**
 * @ORM\Column(name="nacionalidad", type="string",length=500 ,nullable=false)
 */
    private $nacionalidad;

    /**
     * @ORM\Column(name="provincia", type="string",length=500 ,nullable=false)
     */
    private $provincia;

    /**
     * @ORM\Column(name="localidad", type="string",length=500 ,nullable=false)
     */
    private $localidad;

    /**
     * @ORM\Column(name="fechanacimiento", type="string",length=30 ,nullable=false)
     */
    private $fechanacimiento;

    /**
     * @ORM\Column(name="sexo", type="string",length=2 ,nullable=false)
     */
    private $sexo;

    /**
     * @ORM\Column(name="estadocivil", type="string",length=20 ,nullable=false)
     */
    private $estadocivil;

    /**
     * @ORM\Column(name="hijos", type="string",length=2 ,nullable=false)
     */
    private $hijos;

    /**
     * @ORM\Column(name="actividad", type="string",length=200 ,nullable=false)
     */
    private $actividad;

    /**
     * @ORM\Column(name="area", type="string",length=200 ,nullable=false)
     */
    private $area;

    /**
     * @ORM\Column(name="$trabajo", type="string",length=200 ,nullable=false)
     */
    private $trabajo;


    /**
     * @ORM\Column(name="guid", type="string",length=255 ,nullable=false)
     */
    private $guid;



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
     * @return Postulaciones
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
     * Set apellido
     *
     * @param string $apellido
     * @return Postulaciones
     */
    public function setApellido($apellido)
    {
        $this->apellido = $apellido;

        return $this;
    }

    /**
     * Get apellido
     *
     * @return string 
     */
    public function getApellido()
    {
        return $this->apellido;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Postulaciones
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime 
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Postulaciones
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     * @return Postulaciones
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return string 
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set archivo
     *
     * @param string $archivo
     * @return Postulaciones
     */
    public function setArchivo($archivo)
    {
        $this->archivo = $archivo;

        return $this;
    }

    /**
     * Get archivo
     *
     * @return string 
     */
    public function getArchivo()
    {
        return $this->archivo;
    }

    /**
     * Set oferta
     *
     * @param \GEMA\gemaBundle\Entity\OfertaLaboral $oferta
     * @return Postulaciones
     */
    public function setOferta(\GEMA\gemaBundle\Entity\OfertaLaboral $oferta = null)
    {
        $this->oferta = $oferta;

        return $this;
    }

    /**
     * Get oferta
     *
     * @return \GEMA\gemaBundle\Entity\OfertaLaboral 
     */
    public function getOferta()
    {
        return $this->oferta;
    }

    /**
     * Set guid
     *
     * @param string $guid
     * @return Postulaciones
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
     * Set nacionalidad
     *
     * @param string $nacionalidad
     * @return Postulaciones
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
     * Set provincia
     *
     * @param string $provincia
     * @return Postulaciones
     */
    public function setProvincia($provincia)
    {
        $this->provincia = $provincia;

        return $this;
    }

    /**
     * Get provincia
     *
     * @return string 
     */
    public function getProvincia()
    {
        return $this->provincia;
    }

    /**
     * Set localidad
     *
     * @param string $localidad
     * @return Postulaciones
     */
    public function setLocalidad($localidad)
    {
        $this->localidad = $localidad;

        return $this;
    }

    /**
     * Get localidad
     *
     * @return string 
     */
    public function getLocalidad()
    {
        return $this->localidad;
    }

    /**
     * Set fechanacimiento
     *
     * @param string $fechanacimiento
     * @return Postulaciones
     */
    public function setFechanacimiento($fechanacimiento)
    {
        $this->fechanacimiento = $fechanacimiento;

        return $this;
    }

    /**
     * Get fechanacimiento
     *
     * @return string 
     */
    public function getFechanacimiento()
    {
        return $this->fechanacimiento;
    }

    /**
     * Set sexo
     *
     * @param string $sexo
     * @return Postulaciones
     */
    public function setSexo($sexo)
    {
        $this->sexo = $sexo;

        return $this;
    }

    /**
     * Get sexo
     *
     * @return string 
     */
    public function getSexo()
    {
        return $this->sexo;
    }

    /**
     * Set estadocivil
     *
     * @param string $estadocivil
     * @return Postulaciones
     */
    public function setEstadocivil($estadocivil)
    {
        $this->estadocivil = $estadocivil;

        return $this;
    }

    /**
     * Get estadocivil
     *
     * @return string 
     */
    public function getEstadocivil()
    {
        return $this->estadocivil;
    }

    /**
     * Set hijos
     *
     * @param string $hijos
     * @return Postulaciones
     */
    public function setHijos($hijos)
    {
        $this->hijos = $hijos;

        return $this;
    }

    /**
     * Get hijos
     *
     * @return string 
     */
    public function getHijos()
    {
        return $this->hijos;
    }

    /**
     * Set actividad
     *
     * @param string $actividad
     * @return Postulaciones
     */
    public function setActividad($actividad)
    {
        $this->actividad = $actividad;

        return $this;
    }

    /**
     * Get actividad
     *
     * @return string 
     */
    public function getActividad()
    {
        return $this->actividad;
    }

    /**
     * Set area
     *
     * @param string $area
     * @return Postulaciones
     */
    public function setArea($area)
    {
        $this->area = $area;

        return $this;
    }

    /**
     * Get area
     *
     * @return string 
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * Set trabajo
     *
     * @param string $trabajo
     * @return Postulaciones
     */
    public function setTrabajo($trabajo)
    {
        $this->trabajo = $trabajo;

        return $this;
    }

    /**
     * Get trabajo
     *
     * @return string 
     */
    public function getTrabajo()
    {
        return $this->trabajo;
    }
}
