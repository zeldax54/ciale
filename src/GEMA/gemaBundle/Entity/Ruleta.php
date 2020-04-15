<?php

namespace GEMA\gemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Accion
 *
 * @ORM\Table(name="ruleta")
 * @ORM\Entity(repositoryClass="GEMA\gemaBundle\Entity\RuletaRepository")
 */
class Ruleta {

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
     * @ORM\Column(name="nombre", type="string")
     */
    private $nombre;

     /**
     * @var integer
     *
     * @ORM\Column(name="min", type="integer")
     */
    private $min;

     /**
     * @var string
     *
     * @ORM\Column(name="max", type="integer")
     */
    private $max;

     /**
     * @ORM\OneToMany(targetEntity="GEMA\gemaBundle\Entity\Premio" , mappedBy="ruleta", cascade={"persist"})
     */

    private $premios;

   
    public function __toString(){

        return $this->getNombre();
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
     * Set nombre
     *
     * @param string $nombre
     * @return Ruleta
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
     * Set min
     *
     * @param integer $min
     * @return Ruleta
     */
    public function setMin($min)
    {
        $this->min = $min;

        return $this;
    }

    /**
     * Get min
     *
     * @return integer 
     */
    public function getMin()
    {
        return $this->min;
    }

    /**
     * Set max
     *
     * @param integer $max
     * @return Ruleta
     */
    public function setMax($max)
    {
        $this->max = $max;

        return $this;
    }

    /**
     * Get max
     *
     * @return integer 
     */
    public function getMax()
    {
        return $this->max;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->premios = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add premios
     *
     * @param \GEMA\gemaBundle\Entity\Premio $premios
     * @return Ruleta
     */
    public function addPremio(\GEMA\gemaBundle\Entity\Premio $premios)
    {
        $this->premios[] = $premios;

        return $this;
    }

    /**
     * Remove premios
     *
     * @param \GEMA\gemaBundle\Entity\Premio $premios
     */
    public function removePremio(\GEMA\gemaBundle\Entity\Premio $premios)
    {
        $this->premios->removeElement($premios);
    }

    /**
     * Get premios
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPremios()
    {
        return $this->premios;
    }
}
