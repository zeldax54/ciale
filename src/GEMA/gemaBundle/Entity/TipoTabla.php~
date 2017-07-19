<?php

namespace GEMA\gemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * TipoTabla
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="GEMA\gemaBundle\Entity\TipoTablaRepository")
 * @UniqueEntity(fields="nombre", message="Este tipo de tabla ya existe")
 * @ORM\Table(name="tipotabla")
 * @ORM\HasLifecycleCallbacks()
 */
class TipoTabla
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
     * @ORM\OneToMany(targetEntity="GEMA\gemaBundle\Entity\Tabla" , mappedBy="tipotabla", cascade={"persist"})
     */

    private $tablas;

    /**
     * @ORM\OneToMany(targetEntity="GEMA\gemaBundle\Entity\Raza" , mappedBy="tipotabla", cascade={"persist"})
     */

    private $razas;

    public function __toString() {
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
     * @return TipoTabla
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
     * Constructor
     */
    public function __construct()
    {
        $this->tablas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->razas = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add tablas
     *
     * @param \GEMA\gemaBundle\Entity\Tabla $tablas
     * @return TipoTabla
     */
    public function addTabla(\GEMA\gemaBundle\Entity\Tabla $tablas)
    {
        $this->tablas[] = $tablas;

        return $this;
    }

    /**
     * Remove tablas
     *
     * @param \GEMA\gemaBundle\Entity\Tabla $tablas
     */
    public function removeTabla(\GEMA\gemaBundle\Entity\Tabla $tablas)
    {
        $this->tablas->removeElement($tablas);
    }

    /**
     * Get tablas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTablas()
    {
        return $this->tablas;
    }

    /**
     * Add razas
     *
     * @param \GEMA\gemaBundle\Entity\Raza $razas
     * @return TipoTabla
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
