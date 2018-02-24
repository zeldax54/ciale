<?php

namespace GEMA\gemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ComportamientoCondicion
 *
 * @ORM\Table(name="comportamientocondicion")
 * @ORM\Entity(repositoryClass="GEMA\gemaBundle\Entity\ComportamientoCondicionRepository")
 */
class ComportamientoCondicion {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;



    /**
     * @ORM\ManyToOne(targetEntity="GEMA\gemaBundle\Entity\Comportamiento",inversedBy="condiciones", cascade={"persist"})
     */

    private $comportamiento;

    /**
     * @ORM\ManyToOne(targetEntity="GEMA\gemaBundle\Entity\Condicion",inversedBy="comportamientocondiciones", cascade={"persist"})
     */

    private $condicionphp;

    /**
     * @var integer
     *@ORM\OrderBy
     * @ORM\Column(name="orden", type="integer")
     */
    private $orden;




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
     * @return ComportamientoCondicion
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
     * Set accion
     *
     * @param string $accion
     * @return ComportamientoCondicion
     */
    public function setAccion($accion)
    {
        $this->accion = $accion;

        return $this;
    }

    /**
     * Get accion
     *
     * @return string 
     */
    public function getAccion()
    {
        return $this->accion;
    }

    /**
     * Set condicionphp
     *
     * @param \GEMA\gemaBundle\Entity\Condicion $condicionphp
     * @return ComportamientoCondicion
     */
    public function setCondicionphp(\GEMA\gemaBundle\Entity\Condicion $condicionphp)
    {
        $this->condicionphp = $condicionphp;

        return $this;
    }

    /**
     * Get condicionphp
     *
     * @return \GEMA\gemaBundle\Entity\Condicion
     */
    public function getCondicionphp()
    {
        return $this->condicionphp;
    }

    /**
     * Set comportamiento
     *
     * @param \GEMA\gemaBundle\Entity\Comportamiento $comportamiento
     * @return ComportamientoCondicion
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
     * Set orden
     *
     * @param integer $orden
     * @return ComportamientoCondicion
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
}