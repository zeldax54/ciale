<?php

namespace GEMA\gemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Raza
 *
 * @ORM\Table(name="correo")
 * @ORM\Entity(repositoryClass="GEMA\gemaBundle\Entity\CorreoRepository")
 * @ORM\HasLifecycleCallbacks() 
 */
class Correo
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
     * @ORM\Column(name="nombre", type="string", length=255,nullable=true)
     */
    private $nombre;

    /**
     * @var string
     * @ORM\Column(name="apellido", type="string", length=500,nullable=true)
     */
    private $apellido;

    /**
     * @var string
     * @ORM\Column(name="direccion", type="string", length=500,nullable=true)
     */
    private $direccion;

    /**
     * @var string
     * @ORM\Column(name="localidad", type="string", length=500,nullable=true)
     */
    private $localidad;
    /**
     * @var string
     * @ORM\Column(name="provincia", type="string", length=500,nullable=true)
     */
    private $provincia;

    /**
     * @var string
     * @ORM\Column(name="pais", type="string", length=500,nullable=true)
     */
    private $pais;

    /**
     * @var string
     * @ORM\Column(name="codigopostal", type="string", length=500,nullable=true)
     */
    private $codigopostal;

    /**
     * @var string
     * @ORM\Column(name="email", type="string", length=500,nullable=true)
     */
    private $email;

    /**
     * @var string
     * @ORM\Column(name="telefono", type="string", length=500,nullable=true)
     */
    private $telefono;

    /**
     * @var string
     * @ORM\Column(name="empresa", type="string", length=500,nullable=true)
     */
    private $empresa;

    /**
     * @var string
     * @ORM\Column(name="razas", type="string", length=500,nullable=true)
     */
    private $razas;

    /**
     * @var string
     * @ORM\Column(name="consulta", type="string", length=500,nullable=true)
     */
    private $consulta;

    /**
     * @var string
     * @ORM\Column(name="mailChimpResponse", type="text",nullable=true)
     */
    private $mailChimpResponse;

    /**
     * @var string
     * @ORM\Column(name="fechahora", type="string",length=500,nullable=true)
     */
    private $fechahora;



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
     * @return Correo
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
     * @return Correo
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
     * Set direccion
     *
     * @param string $direccion
     * @return Correo
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return string 
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set provincia
     *
     * @param string $provincia
     * @return Correo
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
     * Set codigopostal
     *
     * @param string $codigopostal
     * @return Correo
     */
    public function setCodigopostal($codigopostal)
    {
        $this->codigopostal = $codigopostal;

        return $this;
    }

    /**
     * Get codigopostal
     *
     * @return string 
     */
    public function getCodigopostal()
    {
        return $this->codigopostal;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Correo
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
     * @return Correo
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
     * Set empresa
     *
     * @param string $empresa
     * @return Correo
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
     * @return Correo
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
     * Set consulta
     *
     * @param string $consulta
     * @return Correo
     */
    public function setConsulta($consulta)
    {
        $this->consulta = $consulta;

        return $this;
    }

    /**
     * Get consulta
     *
     * @return string 
     */
    public function getConsulta()
    {
        return $this->consulta;
    }

    /**
     * Set localidad
     *
     * @param string $localidad
     * @return Correo
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
     * Set fechahora
     *
     * @param string $fechahora
     * @return Correo
     */
    public function setFechahora($fechahora)
    {
        $this->fechahora = $fechahora;

        return $this;
    }

    /**
     * Get fechahora
     *
     * @return string
     */
    public function getFechahora()
    {
        return $this->fechahora;
    }

    /**
     * Set pais
     *
     * @param string $pais
     * @return Correo
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
     * Set mailChimpResponse
     *
     * @param string $mailChimpResponse
     * @return Correo
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
