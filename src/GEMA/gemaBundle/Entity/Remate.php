<?php

namespace GEMA\gemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;




/**
 * CategoriaNoticia
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="GEMA\gemaBundle\Entity\RemateRepository")
 * @ORM\Table(name="remate")

 */
class Remate {

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
     * @ORM\Column(name="localidad", type="string", length=1500,nullable=true)
     */
    private $localidad;

    /**
     * @var string
     * @ORM\Column(name="provincia", type="string", length=1500,nullable=true)
     */
    private $provincia;

    /**
     * @var string
     * @ORM\Column(name="sitioweb", type="string", length=1500,nullable=true)
     */
    private $sitioweb;

    /**
     * @var string
     * @ORM\Column(name="linksitioweb", type="string", length=1500,nullable=true)
     */
    private $linksitioweb;

    /**
     * @var string
     * @ORM\Column(name="contacto", type="string", length=1500,nullable=true)
     */
    private $contacto;

    /**
     * @var string
     * @ORM\Column(name="linkflyer", type="string", length=1500,nullable=true)
     */
    private $linkflyer;

    /**
     * @var string
     * @ORM\Column(name="linkcatalogo", type="string", length=1500,nullable=true)
     */
    private $linkcatalogo;





    /**
     * @var string
     * @ORM\Column(name="fecha", type="date",nullable=true)
     */
    private $fecha;



    /**
     * @var string
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
     * @return Remate
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
     * Set localidad
     *
     * @param string $localidad
     * @return Remate
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
     * @return Remate
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
     * Set sitioweb
     *
     * @param string $sitioweb
     * @return Remate
     */
    public function setSitioweb($sitioweb)
    {
        $this->sitioweb = $sitioweb;

        return $this;
    }

    /**
     * Get sitioweb
     *
     * @return string 
     */
    public function getSitioweb()
    {
        return $this->sitioweb;
    }

    /**
     * Set linksitioweb
     *
     * @param string $linksitioweb
     * @return Remate
     */
    public function setLinksitioweb($linksitioweb)
    {
        $this->linksitioweb = $linksitioweb;

        return $this;
    }

    /**
     * Get linksitioweb
     *
     * @return string 
     */
    public function getLinksitioweb()
    {
        return $this->linksitioweb;
    }

    /**
     * Set contacto
     *
     * @param string $contacto
     * @return Remate
     */
    public function setContacto($contacto)
    {
        $this->contacto = $contacto;

        return $this;
    }

    /**
     * Get contacto
     *
     * @return string 
     */
    public function getContacto()
    {
        return $this->contacto;
    }

    /**
     * Set linkflyer
     *
     * @param string $linkflyer
     * @return Remate
     */
    public function setLinkflyer($linkflyer)
    {
        $this->linkflyer = $linkflyer;

        return $this;
    }

    /**
     * Get linkflyer
     *
     * @return string 
     */
    public function getLinkflyer()
    {
        return $this->linkflyer;
    }

    /**
     * Set linkcatalogo
     *
     * @param string $linkcatalogo
     * @return Remate
     */
    public function setLinkcatalogo($linkcatalogo)
    {
        $this->linkcatalogo = $linkcatalogo;

        return $this;
    }

    /**
     * Get linkcatalogo
     *
     * @return string 
     */
    public function getLinkcatalogo()
    {
        return $this->linkcatalogo;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Remate
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
     * Set guid
     *
     * @param string $guid
     * @return Remate
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
