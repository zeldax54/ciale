<?php

namespace GEMA\gemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;




/**
 * Noticia
 *
 * @ORM\Table(name="noticia")
 * @ORM\Entity(repositoryClass="GEMA\gemaBundle\Entity\NoticiaRepository")
 * @UniqueEntity(fields="titulo", message="Este título de Noticia ya Existe")
 */
class Noticia {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="GEMA\gemaBundle\Entity\CategoriaNoticia",inversedBy="noticias", cascade={"persist"})
     */

    private $categoria;

    /**
     * @var string
     *
     * @ORM\Column(name="titulo", type="text",nullable=false )
     */
    private $titulo;



    /**
     * @var string
     *
     * @ORM\Column(name="introduccion", type="text",nullable=false)
     */
     private $introduccion;


    /**
     * @var string
     *
     * @ORM\Column(name="cuerpo", type="text",nullable=false)
     */
     private $cuerpo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechanoticia", type="date",nullable=false)
     */
    private $fechanoticia;

    /**
     * @ORM\Column(name="publico", type="boolean", nullable=true)
     */
    private $publico;

    /**
     * @var text
     * @ORM\Column(name="youtube", type="text",nullable=true)
     */
    private $youtube;


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
     * Set titulo
     *
     * @param string $titulo
     * @return Noticia
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
     * Set introduccion
     *
     * @param string $introduccion
     * @return Noticia
     */
    public function setIntroduccion($introduccion)
    {
        $this->introduccion = $introduccion;

        return $this;
    }

    /**
     * Get introduccion
     *
     * @return string 
     */
    public function getIntroduccion()
    {
        return $this->introduccion;
    }

    /**
     * Set cuerpo
     *
     * @param string $cuerpo
     * @return Noticia
     */
    public function setCuerpo($cuerpo)
    {
        $this->cuerpo = $cuerpo;

        return $this;
    }

    /**
     * Get cuerpo
     *
     * @return string 
     */
    public function getCuerpo()
    {
        return $this->cuerpo;
    }

    /**
     * Set fechanoticia
     *
     * @param \DateTime $fechanoticia
     * @return Noticia
     */
    public function setFechanoticia($fechanoticia)
    {
        $this->fechanoticia = $fechanoticia;

        return $this;
    }

    /**
     * Get fechanoticia
     *
     * @return \DateTime 
     */
    public function getFechanoticia()
    {
        return $this->fechanoticia;
    }

    /**
     * Set publico
     *
     * @param boolean $publico
     * @return Noticia
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
     * Set categoria
     *
     * @param \GEMA\gemaBundle\Entity\CategoriaNoticia $categoria
     * @return Noticia
     */
    public function setCategoria(\GEMA\gemaBundle\Entity\CategoriaNoticia $categoria = null)
    {
        $this->categoria = $categoria;

        return $this;
    }

    /**
     * Get categoria
     *
     * @return \GEMA\gemaBundle\Entity\CategoriaNoticia 
     */
    public function getCategoria()
    {
        return $this->categoria;
    }

    /**
     * Set guid
     *
     * @param string $guid
     * @return Noticia
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
     * Set youtube
     *
     * @param string $youtube
     * @return Noticia
     */
    public function setYoutube($youtube)
    {
        $this->youtube = $youtube;

        return $this;
    }

    /**
     * Get youtube
     *
     * @return string 
     */
    public function getYoutube()
    {
        return $this->youtube;
    }
}
