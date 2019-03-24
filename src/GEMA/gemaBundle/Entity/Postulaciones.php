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
}
