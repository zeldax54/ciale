<?php

namespace GEMA\gemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;




/**
 * CategoriaNoticia
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="GEMA\gemaBundle\Entity\VisitaRepository")
 * @ORM\Table(name="visita")

 */
class Visita {

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
     * @ORM\Column(name="localidad", type="string", length=1500)
     */
    private $localidad;

    /**
     * @var string
     * @ORM\Column(name="provincia", type="string", length=1500)
     */
    private $provincia;

    /**
     * @var string
     * @ORM\Column(name="pais", type="string", length=1500)
     */
    private $pais;

    /**
     * @var string
     * @ORM\Column(name="fecha", type="date",nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="email", type="string",length=500 ,nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(name="telefono", type="string",length=500 ,nullable=true)
     */
    private $telefono;

    /**
     * @ORM\Column(name="ocupacion", type="string",length=500 ,nullable=true)
     */
    private $ocupacion;

    /**
     * @ORM\Column(name="empresa", type="string",length=500 ,nullable=true)
     */
    private $empresa;

    /**
     * @ORM\Column(name="razas", type="string",length=1500 ,nullable=true)
    */
    private $razas;

    /**
     * @ORM\Column(name="calificacion", type="string",length=2 ,nullable=true)
     */
    private $calificacion;

    /**
     * @ORM\Column(name="sugerencias", type="string",length=1500 ,nullable=true)
     */
    private $sugerencias;

    /**
     * @ORM\Column(name="archivo", type="string",length=500 ,nullable=true)
     */
    private $archivo;

    /**
     * @var string
     * @ORM\Column(name="mailChimpResponse", type="text",nullable=true)
     */
    private $mailChimpResponse;

    /**
     * @ORM\Column(name="guid", type="string",length=255 ,nullable=true)
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
     * @return Visita
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
     * @return Visita
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
     * @return Visita
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
     * @param \email $email
     * @return Visita
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return \email 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set localidad
     *
     * @param string $localidad
     * @return Visita
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
     * Set provincia
     *
     * @param string $provincia
     * @return Visita
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
     * Set pais
     *
     * @param string $pais
     * @return Visita
     */
    public function setPais($pais)
    {
        $this->pais = $pais;

        return $this;
    }

    /**
     * Get pais
     *
     * @return string 
     */
    public function getPais()
    {
        return $this->pais;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     * @return Visita
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
     * Set ocupacion
     *
     * @param string $ocupacion
     * @return Visita
     */
    public function setOcupacion($ocupacion)
    {
        $this->ocupacion = $ocupacion;

        return $this;
    }

    /**
     * Get ocupacion
     *
     * @return string 
     */
    public function getOcupacion()
    {
        return $this->ocupacion;
    }

    /**
     * Set empresa
     *
     * @param string $empresa
     * @return Visita
     */
    public function setEmpresa($empresa)
    {
        $this->empresa = $empresa;

        return $this;
    }

    /**
     * Get empresa
     *
     * @return string 
     */
    public function getEmpresa()
    {
        return $this->empresa;
    }

    /**
     * Set razas
     *
     * @param string $razas
     * @return Visita
     */
    public function setRazas($razas)
    {
        $this->razas = $razas;

        return $this;
    }

    /**
     * Get razas
     *
     * @return string 
     */
    public function getRazas()
    {
        return $this->razas;
    }

    /**
     * Set calificacion
     *
     * @param string $calificacion
     * @return Visita
     */
    public function setCalificacion($calificacion)
    {
        $this->calificacion = $calificacion;

        return $this;
    }

    /**
     * Get calificacion
     *
     * @return string 
     */
    public function getCalificacion()
    {
        return $this->calificacion;
    }

    /**
     * Set sugerencias
     *
     * @param string $sugerencias
     * @return Visita
     */
    public function setSugerencias($sugerencias)
    {
        $this->sugerencias = $sugerencias;

        return $this;
    }

    /**
     * Get sugerencias
     *
     * @return string 
     */
    public function getSugerencias()
    {
        return $this->sugerencias;
    }

    /**
     * Set archivo
     *
     * @param string $archivo
     * @return Visita
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
     * Set guid
     *
     * @param string $guid
     * @return Visita
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
     * Set mailChimpResponse
     *
     * @param string $mailChimpResponse
     * @return Visita
     */
    public function setMailChimpResponse($mailChimpResponse)
    {
        $this->mailChimpResponse = $mailChimpResponse;

        return $this;
    }

    /**
     * Get mailChimpResponse
     *
     * @return string 
     */
    public function getMailChimpResponse()
    {
        return $this->mailChimpResponse;
    }
}
