<?php

namespace GEMA\gemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ComportamientoCondicion
 *
 * @ORM\Table(name="comportamientoaccion")
 * @ORM\Entity(repositoryClass="GEMA\gemaBundle\Entity\ComportamientoAccionRepository")
 */
class ComportamientoAccion {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="GEMA\gemaBundle\Entity\Comportamiento",inversedBy="acciones", cascade={"persist"})
     */

    private $comportamiento;

    /**
     * @ORM\ManyToOne(targetEntity="GEMA\gemaBundle\Entity\Accion",inversedBy="comportamientoacciones", cascade={"persist"})
     */

    private $accion;

    /**
     * @var integer
     * @ORM\Column(name="orden", type="integer")
     */
    private $orden;

    /**
     * @var integer
     *@ORM\OrderBy
     * @ORM\Column(name="sugeridosnumber", type="integer")
     */
    private $sugeridosnumber;




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
     * Set orden
     *
     * @param integer $orden
     * @return ComportamientoAccion
     */
    public function setOrden($orden)
    {
        $this->orden = $orden;

        return $this;
    }

    /**
     * Get orden
     *
     * @return integer 
     */
    public function getOrden()
    {
        return $this->orden;
    }

    /**
     * Set comportamiento
     *
     * @param \GEMA\gemaBundle\Entity\Comportamiento $comportamiento
     * @return ComportamientoAccion
     */
    public function setComportamiento(\GEMA\gemaBundle\Entity\Comportamiento $comportamiento = null)
    {
        $this->comportamiento = $comportamiento;

        return $this;
    }

    /**
     * Get comportamiento
     *
     * @return \GEMA\gemaBundle\Entity\Comportamiento 
     */
    public function getComportamiento()
    {
        return $this->comportamiento;
    }

    /**
     * Set accionnphp
     *
     * @param \GEMA\gemaBundle\Entity\Accion $accionnphp
     * @return ComportamientoAccion
     */
    public function setAccionnphp(\GEMA\gemaBundle\Entity\Accion $accionnphp = null)
    {
        $this->accionnphp = $accionnphp;

        return $this;
    }

    /**
     * Get accionnphp
     *
     * @return \GEMA\gemaBundle\Entity\Accion 
     */
    public function getAccionnphp()
    {
        return $this->accionnphp;
    }

    /**
     * Set sugeridosnumber
     *
     * @param integer $sugeridosnumber
     * @return ComportamientoAccion
     */
    public function setSugeridosnumber($sugeridosnumber)
    {
        $this->sugeridosnumber = $sugeridosnumber;

        return $this;
    }

    /**
     * Get sugeridosnumber
     *
     * @return integer 
     */
    public function getSugeridosnumber()
    {
        return $this->sugeridosnumber;
    }

    /**
     * Set accion
     *
     * @param \GEMA\gemaBundle\Entity\Accion $accion
     * @return ComportamientoAccion
     */
    public function setAccion(\GEMA\gemaBundle\Entity\Accion $accion = null)
    {
        $this->accion = $accion;

        return $this;
    }

    /**
     * Get accion
     *
     * @return \GEMA\gemaBundle\Entity\Accion 
     */
    public function getAccion()
    {
        return $this->accion;
    }
}
