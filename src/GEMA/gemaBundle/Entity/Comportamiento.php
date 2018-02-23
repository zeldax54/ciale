<?php

namespace GEMA\gemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Comportamiento
 *
 * @ORM\Table(name="comportamiento")
 * @ORM\Entity(repositoryClass="GEMA\gemaBundle\Entity\ComportamientoRepository")
 */
class Comportamiento {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="lineaenexcel", type="integer")
     */
    private $lineaenexcel;

    /**
     * @var integer
     *
     * @ORM\Column(name="tipo", type="string",length=20)
     */
    private $tipo;

    /**
     * @var integer
     *
     * @ORM\Column(name="comentario", type="string",length=500)
     */
    private $comentario;



    /**
     * @ORM\OneToMany(targetEntity="GEMA\gemaBundle\Entity\ComportamientoCondicion" , mappedBy="comportamiento", cascade={"persist"})
     */

    private $condiciones;

    /**
     * @ORM\OneToMany(targetEntity="GEMA\gemaBundle\Entity\ComportamientoAccion" , mappedBy="comportamiento", cascade={"persist"})
     */

    private $acciones;




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
     * Set lineaenexcel
     *
     * @param integer $lineaenexcel
     * @return Comportamiento
     */
    public function setLineaenexcel($lineaenexcel)
    {
        $this->lineaenexcel = $lineaenexcel;

        return $this;
    }

    /**
     * Get lineaenexcel
     *
     * @return integer 
     */
    public function getLineaenexcel()
    {
        return $this->lineaenexcel;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->condiciones = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add condiciones
     *
     * @param \GEMA\gemaBundle\Entity\ComportamientoCondicion $condiciones
     * @return Comportamiento
     */
    public function addCondicione(\GEMA\gemaBundle\Entity\ComportamientoCondicion $condiciones)
    {
        $this->condiciones[] = $condiciones;

        return $this;
    }

    /**
     * Remove condiciones
     *
     * @param \GEMA\gemaBundle\Entity\ComportamientoCondicion $condiciones
     */
    public function removeCondicione(\GEMA\gemaBundle\Entity\ComportamientoCondicion $condiciones)
    {
        $this->condiciones->removeElement($condiciones);
    }

    /**
     * Get condiciones
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCondiciones()
    {
        return $this->condiciones;
    }

    /**
     * Set comentario
     *
     * @param string $comentario
     * @return Comportamiento
     */
    public function setComentario($comentario)
    {
        $this->comentario = $comentario;

        return $this;
    }

    /**
     * Get comentario
     *
     * @return string 
     */
    public function getComentario()
    {
        return $this->comentario;
    }

    /**
     * Add acciones
     *
     * @param \GEMA\gemaBundle\Entity\ComportamientoAccion $acciones
     * @return Comportamiento
     */
    public function addAccione(\GEMA\gemaBundle\Entity\ComportamientoAccion $acciones)
    {
        $this->acciones[] = $acciones;

        return $this;
    }

    /**
     * Remove acciones
     *
     * @param \GEMA\gemaBundle\Entity\ComportamientoAccion $acciones
     */
    public function removeAccione(\GEMA\gemaBundle\Entity\ComportamientoAccion $acciones)
    {
        $this->acciones->removeElement($acciones);
    }

    /**
     * Get acciones
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAcciones()
    {
        return $this->acciones;
    }

    /**
     * Set tipo
     *
     * @param string $tipo
     * @return Comportamiento
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return string 
     */
    public function getTipo()
    {
        return $this->tipo;
    }
}
