<?php

namespace GEMA\gemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Accion
 *
 * @ORM\Table(name="accion")
 * @ORM\Entity(repositoryClass="GEMA\gemaBundle\Entity\AccionRepository")
 */
class Accion {

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
     * @ORM\Column(name="nombre", type="string",length=100)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="var", type="string",length=255)
     */
    private $var;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string",length=255)
     */
    private $value;

    /**
     * @var string
     *
     * @ORM\Column(name="phpcode", type="string",length=255)
     */
    private $phpcode;



    /**
     * @var string
     *
     * @ORM\Column(name="tipo", type="string",length=20)
     */
    private $tipo;

    /**
     * @ORM\OneToMany(targetEntity="GEMA\gemaBundle\Entity\ComportamientoAccion" , mappedBy="accionnphp", cascade={"persist"})
     */

    private $comportamientoacciones;





    /**
     * Constructor
     */
    public function __construct()
    {
        $this->comportamientocondiciones = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Condicion
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
     * Set phpcode
     *
     * @param string $phpcode
     * @return Condicion
     */
    public function setPhpcode($phpcode)
    {
        $this->phpcode = $phpcode;

        return $this;
    }

    /**
     * Get phpcode
     *
     * @return string 
     */
    public function getPhpcode()
    {
        return $this->phpcode;
    }

    /**
     * Add comportamientocondiciones
     *
     * @param \GEMA\gemaBundle\Entity\ComportamientoCondicion $comportamientocondiciones
     * @return Condicion
     */
    public function addComportamientocondicione(\GEMA\gemaBundle\Entity\ComportamientoCondicion $comportamientocondiciones)
    {
        $this->comportamientocondiciones[] = $comportamientocondiciones;

        return $this;
    }

    /**
     * Remove comportamientocondiciones
     *
     * @param \GEMA\gemaBundle\Entity\ComportamientoCondicion $comportamientocondiciones
     */
    public function removeComportamientocondicione(\GEMA\gemaBundle\Entity\ComportamientoCondicion $comportamientocondiciones)
    {
        $this->comportamientocondiciones->removeElement($comportamientocondiciones);
    }

    /**
     * Get comportamientocondiciones
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getComportamientocondiciones()
    {
        return $this->comportamientocondiciones;
    }

    /**
     * Add comportamientoacciones
     *
     * @param \GEMA\gemaBundle\Entity\ComportamientoAccion $comportamientoacciones
     * @return Accion
     */
    public function addComportamientoaccione(\GEMA\gemaBundle\Entity\ComportamientoAccion $comportamientoacciones)
    {
        $this->comportamientoacciones[] = $comportamientoacciones;

        return $this;
    }

    /**
     * Remove comportamientoacciones
     *
     * @param \GEMA\gemaBundle\Entity\ComportamientoAccion $comportamientoacciones
     */
    public function removeComportamientoaccione(\GEMA\gemaBundle\Entity\ComportamientoAccion $comportamientoacciones)
    {
        $this->comportamientoacciones->removeElement($comportamientoacciones);
    }

    /**
     * Get comportamientoacciones
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getComportamientoacciones()
    {
        return $this->comportamientoacciones;
    }

    /**
     * Set tipo
     *
     * @param string $tipo
     * @return Accion
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

    /**
     * Set var
     *
     * @param string $var
     * @return Accion
     */
    public function setVar($var)
    {
        $this->var = $var;

        return $this;
    }

    /**
     * Get var
     *
     * @return string 
     */
    public function getVar()
    {
        return $this->var;
    }

    /**
     * Set value
     *
     * @param string $value
     * @return Accion
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
    }
}
