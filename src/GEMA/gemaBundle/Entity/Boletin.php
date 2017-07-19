<?php

namespace GEMA\gemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;




/**
 * Boletin
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="GEMA\gemaBundle\Entity\BoletinRepository")
 * @UniqueEntity(fields="titulo", message="Este título de Boletin ya Existe")
 * @ORM\Table(name="boletin")
 */
class Boletin {

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
     *
     * @ORM\Column(name="titulo", type="text",nullable=false )
     */
    private $titulo;

    /**
     * @ORM\OneToMany(targetEntity="GEMA\gemaBundle\Entity\BoletinMedia" , mappedBy="boletin", cascade={"persist", "detach"})
     */
     private $medias;



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
     * @ORM\Column(name="fechaboletin", type="date",nullable=false)
     */
    private $fechaboletin;

    /**
     * @ORM\Column(name="publico", type="boolean", nullable=true)
     */
    private $publico;

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
     * @return Boletin
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
     * Constructor
     */
    public function __construct()
    {
        $this->medias = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add medias
     *
     * @param \GEMA\gemaBundle\Entity\BoletinMedia $medias
     * @return Boletin
     */
    public function addMedia(\GEMA\gemaBundle\Entity\BoletinMedia $medias)
    {
        $this->medias[] = $medias;

        return $this;
    }

    /**
     * Remove medias
     *
     * @param \GEMA\gemaBundle\Entity\BoletinMedia $medias
     */
    public function removeMedia(\GEMA\gemaBundle\Entity\BoletinMedia $medias)
    {
        $this->medias->removeElement($medias);
    }

    /**
     * Get medias
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMedias()
    {
        return $this->medias;
    }

    /**
     * Set introduccion
     *
     * @param string $introduccion
     * @return Boletin
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
     * Set videoyoutube1
     *
     * @param string $videoyoutube1
     * @return Boletin
     */
    public function setVideoyoutube1($videoyoutube1)
    {
        $this->videoyoutube1 = $videoyoutube1;

        return $this;
    }

    /**
     * Get videoyoutube1
     *
     * @return string 
     */
    public function getVideoyoutube1()
    {
        return $this->videoyoutube1;
    }

    /**
     * Set videoyoutube2
     *
     * @param string $videoyoutube2
     * @return Boletin
     */
    public function setVideoyoutube2($videoyoutube2)
    {
        $this->videoyoutube2 = $videoyoutube2;

        return $this;
    }

    /**
     * Get videoyoutube2
     *
     * @return string 
     */
    public function getVideoyoutube2()
    {
        return $this->videoyoutube2;
    }

    /**
     * Set cuerpo
     *
     * @param string $cuerpo
     * @return Boletin
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
     * Set fechaboletin
     *
     * @param \DateTime $fechaboletin
     * @return Boletin
     */
    public function setFechaboletin($fechaboletin)
    {
        $this->fechaboletin = $fechaboletin;

        return $this;
    }

    /**
     * Get fechaboletin
     *
     * @return \DateTime 
     */
    public function getFechaboletin()
    {
        return $this->fechaboletin;
    }

    /**
     * Set publico
     *
     * @param boolean $publico
     * @return Boletin
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
}
