<?php

namespace GEMA\gemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;




/**
 * Catalogo
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="GEMA\gemaBundle\Entity\CatalogoRepository")
 * @ORM\Table(name="catalogo")
 */
class Catalogo {

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
     * @var \DateTime
     * @ORM\Column(name="fecha", type="date",nullable=true)
     */
    private $fecha;

    /**
    * @ORM\OneToMany(targetEntity="GEMA\gemaBundle\Entity\Catalogohojas" , mappedBy="catalogo", cascade={"persist"})
    */

    private $hojas;

   
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
     * @return Catalogo
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
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Catalogo
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
     * Constructor
     */
    public function __construct()
    {
        $this->hojas = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add hojas
     *
     * @param \GEMA\gemaBundle\Entity\Catalogohojas $hojas
     * @return Catalogo
     */
    public function addHoja(\GEMA\gemaBundle\Entity\Catalogohojas $hojas)
    {
        $this->hojas[] = $hojas;

        return $this;
    }

    /**
     * Remove hojas
     *
     * @param \GEMA\gemaBundle\Entity\Catalogohojas $hojas
     */
    public function removeHoja(\GEMA\gemaBundle\Entity\Catalogohojas $hojas)
    {
        $this->hojas->removeElement($hojas);
    }

    /**
     * Get hojas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getHojas()
    {
        return $this->hojas;
    }
}
