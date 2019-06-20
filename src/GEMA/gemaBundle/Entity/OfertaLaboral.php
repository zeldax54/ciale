<?php

namespace GEMA\gemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;




/**
 * CategoriaNoticia
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="GEMA\gemaBundle\Entity\OfertaLaboralRepository")
 * @ORM\Table(name="ofertalaboral")

 */
class OfertaLaboral {

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
     * @ORM\Column(name="titulo", type="string", length=1500)
     */
    private $titulo;

    /**
     * @ORM\Column(name="activa", type="boolean", nullable=true)
     */
    private $activa;


    /**
     * @var string
     * @ORM\Column(name="area", type="string", length=1500)
     */
    private $area;

    /**
     * @var string
     * @ORM\Column(name="descripcion", type="string", length=1500)
     */
    private $descripcion;

    /**
     * @ORM\OneToMany(targetEntity="GEMA\gemaBundle\Entity\Postulaciones" , mappedBy="oferta", cascade={"persist"})
     */

    private $postulaciones;



    public function __toString()
    {
        return $this->getTitulo();
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
     * Set titulo
     *
     * @param string $titulo
     * @return OfertaLaboral
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
     * Set activa
     *
     * @param boolean $activa
     * @return OfertaLaboral
     */
    public function setActiva($activa)
    {
        $this->activa = $activa;

        return $this;
    }

    /**
     * Get activa
     *
     * @return boolean 
     */
    public function getActiva()
    {
        return $this->activa;
    }

    /**
     * Set area
     *
     * @param string $area
     * @return OfertaLaboral
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
     * Set descripcion
     *
     * @param string $descripcion
     * @return OfertaLaboral
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
     * Constructor
     */
    public function __construct()
    {
        $this->postulaciones = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add postulaciones
     *
     * @param \GEMA\gemaBundle\Entity\Postulaciones $postulaciones
     * @return OfertaLaboral
     */
    public function addPostulacione(\GEMA\gemaBundle\Entity\Postulaciones $postulaciones)
    {
        $this->postulaciones[] = $postulaciones;

        return $this;
    }

    /**
     * Remove postulaciones
     *
     * @param \GEMA\gemaBundle\Entity\Postulaciones $postulaciones
     */
    public function removePostulacione(\GEMA\gemaBundle\Entity\Postulaciones $postulaciones)
    {
        $this->postulaciones->removeElement($postulaciones);
    }

    /**
     * Get postulaciones
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPostulaciones()
    {
        return $this->postulaciones;
    }
}