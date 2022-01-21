<?php

namespace GEMA\gemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;




/**
 * Comerciables
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="GEMA\gemaBundle\Entity\ComerciablesRepository")
 * @ORM\Table(name="comerciables")

 */
class Comerciables {

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
     * @ORM\Column(name="guid", type="string",length=255 ,nullable=true)
     */
    private $guid;

     /**
     * @var string
     * @ORM\Column(name="tipo", type="string",length=255 ,nullable=true)
     */
     private $tipo; //merchadaising o promociones

      /**
     * @var \DateTime
     * @ORM\Column(name="fechainicio", type="date",nullable=true)
     */
     private $fechainicio;

      /**
     * @var \DateTime
     * @ORM\Column(name="fechafin", type="date",nullable=true)
     */
     private $fechafin;


    /**
     * @var string
     * @ORM\Column(name="titulo", type="string",length=255 ,nullable=true)
     */
    private $titulo;
   
   
    /**
     * @var string
     * @ORM\Column(name="precio", type="string",length=255 ,nullable=true)
     */
    private $precio; 


    /**
     * @var string
     * @ORM\Column(name="descripcion", type="string",length=255 ,nullable=true)
     */
    private $descripcion;

     /**
     * @var string
     * @ORM\Column(name="videos", type="string",length=255 ,nullable=true)
     */
    private $videos;


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
     * Set guid
     *
     * @param string $guid
     * @return Comerciables
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
     * Set tipo
     *
     * @param string $tipo
     * @return Comerciables
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return string 
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set titulo
     *
     * @param string $titulo
     * @return Comerciables
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;

        return $this;
    }

    /**
     * Get titulo
     *
     * @return string 
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * Set precio
     *
     * @param string $precio
     * @return Comerciables
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
     * Set descripcion
     *
     * @param string $descripcion
     * @return Comerciables
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
     * Set videos
     *
     * @param string $videos
     * @return Comerciables
     */
    public function setVideos($videos)
    {
        $this->videos = $videos;

        return $this;
    }

    /**
     * Get videos
     *
     * @return string 
     */
    public function getVideos()
    {
        return $this->videos;
    }

    /**
     * Set fechainicio
     *
     * @param \DateTime $fechainicio
     * @return Comerciables
     */
    public function setFechainicio($fechainicio)
    {
        $this->fechainicio = $fechainicio;

        return $this;
    }

    /**
     * Get fechainicio
     *
     * @return \DateTime 
     */
    public function getFechainicio()
    {
        return $this->fechainicio;
    }

    /**
     * Set fechafin
     *
     * @param \DateTime $fechafin
     * @return Comerciables
     */
    public function setFechafin($fechafin)
    {
        $this->fechafin = $fechafin;

        return $this;
    }

    /**
     * Get fechafin
     *
     * @return \DateTime 
     */
    public function getFechafin()
    {
        return $this->fechafin;
    }
}
