<?php

namespace GEMA\gemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Decorador
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="GEMA\gemaBundle\Entity\DecoradorRepository")
 * @ORM\Table(name="decorador")
 * @UniqueEntity(fields="nombre", message="Este decorador ya existe")
 * @UniqueEntity(fields="color", message="Este color ya esta usado en un decorador")
 * @ORM\HasLifecycleCallbacks()
 */
class Decorador
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
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var string
     * @ORM\Column(name="color", type="string", length=255)
     */
    private $color;

    /**
     * @ORM\OneToMany(targetEntity="GEMA\gemaBundle\Entity\Raza" , mappedBy="decorador", cascade={"persist"})
     */

    private $razas;


    public function __toString()
    {
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
     * @return Decorador
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
     * Set color
     *
     * @param string $color
     * @return Decorador
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @return string 
     */
    public function getColor()
    {
        return $this->color;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->razas = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add razas
     *
     * @param \GEMA\gemaBundle\Entity\Raza $razas
     * @return Decorador
     */
    public function addRaza(\GEMA\gemaBundle\Entity\Raza $razas)
    {
        $this->razas[] = $razas;

        return $this;
    }

    /**
     * Remove razas
     *
     * @param \GEMA\gemaBundle\Entity\Raza $razas
     */
    public function removeRaza(\GEMA\gemaBundle\Entity\Raza $razas)
    {
        $this->razas->removeElement($razas);
    }

    /**
     * Get razas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRazas()
    {
        return $this->razas;
    }
}
