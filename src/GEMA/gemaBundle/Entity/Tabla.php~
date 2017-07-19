<?php

namespace GEMA\gemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Tabla
 *
 * @ORM\Table(name="tabla")
 * @ORM\Entity(repositoryClass="GEMA\gemaBundle\Entity\TablaRepository")
 * @UniqueEntity(fields="nombre", message="Esta tabla ya existe")
 * @ORM\HasLifecycleCallbacks()
 */
class Tabla
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
     * @ORM\Column(name="nombre", type="string", length=255,nullable=true)
     */
    private $nombre;


    /**
     * @var string
     * @ORM\Column(name="nombreresumido", type="string", length=255)
     */
    private $nombreresumido;



    /**
     * @ORM\ManyToOne(targetEntity="GEMA\gemaBundle\Entity\TipoTabla",inversedBy="tablas", cascade={"persist"})
     */

    private $tipotabla;

    /**
     * @ORM\OneToMany(targetEntity="GEMA\gemaBundle\Entity\TablaDatos" , mappedBy="tabla", cascade={"persist"})
     */

    private $tabladatos;

    /**
     * @ORM\OneToMany(targetEntity="GEMA\gemaBundle\Entity\TablaBody" , mappedBy="tabla", cascade={"persist"})
     */

    private $tablabody;




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
     * @return Tabla
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
     * Set tipotabla
     *
     * @param \GEMA\gemaBundle\Entity\TipoTabla $tipotabla
     * @return Tabla
     */
    public function setTipotabla(\GEMA\gemaBundle\Entity\TipoTabla $tipotabla = null)
    {
        $this->tipotabla = $tipotabla;

        return $this;
    }

    /**
     * Get tipotabla
     *
     * @return \GEMA\gemaBundle\Entity\TipoTabla 
     */
    public function getTipotabla()
    {
        return $this->tipotabla;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tabladatos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add tabladatos
     *
     * @param \GEMA\gemaBundle\Entity\TablaDatos $tabladatos
     * @return Tabla
     */
    public function addTabladato(\GEMA\gemaBundle\Entity\TablaDatos $tabladatos)
    {
        $this->tabladatos[] = $tabladatos;

        return $this;
    }

    /**
     * Remove tabladatos
     *
     * @param \GEMA\gemaBundle\Entity\TablaDatos $tabladatos
     */
    public function removeTabladato(\GEMA\gemaBundle\Entity\TablaDatos $tabladatos)
    {
        $this->tabladatos->removeElement($tabladatos);
    }

    /**
     * Get tabladatos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTabladatos()
    {
        return $this->tabladatos;
    }

    /**
     * Add tablabody
     *
     * @param \GEMA\gemaBundle\Entity\TablaBody $tablabody
     * @return Tabla
     */
    public function addTablabody(\GEMA\gemaBundle\Entity\TablaBody $tablabody)
    {
        $this->tablabody[] = $tablabody;

        return $this;
    }

    /**
     * Remove tablabody
     *
     * @param \GEMA\gemaBundle\Entity\TablaBody $tablabody
     */
    public function removeTablabody(\GEMA\gemaBundle\Entity\TablaBody $tablabody)
    {
        $this->tablabody->removeElement($tablabody);
    }

    /**
     * Get tablabody
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTablabody()
    {
        return $this->tablabody;
    }

    /**
     * Set nombreresumido
     *
     * @param string $nombreresumido
     * @return Tabla
     */
    public function setNombreresumido($nombreresumido)
    {
        $this->nombreresumido = $nombreresumido;

        return $this;
    }

    /**
     * Get nombreresumido
     *
     * @return string 
     */
    public function getNombreresumido()
    {
        return $this->nombreresumido;
    }
}
